<?php

namespace App\Http\Resources;

use App\Models\Label;
use App\Models\Transaction;
use App\Models\UserLevel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $recent_7_days = [Carbon::now()->subDays(7), Carbon::now()];
        $deposits = Transaction::where('user_id', $this->id)->where('type','deposit')->where('status','success')->whereBetween('created_at', $recent_7_days)->get();
        $deposit_amount = $deposits->sum('amount');
        $levels = Label::orderBy('min_bet')->get();
        $currentLevel = $levels->where('min_bet', '<=', $deposit_amount)->last();
        $today_bonus = Transaction::where('user_id', $this->id)->where('type','bonus')->whereDate('created_at', Carbon::today())->first();

        $nextLevel = $levels->where('min_bet', '>', $deposit_amount)->first();

        if ($nextLevel) {
            $currentMin = $currentLevel ? $currentLevel->min_bet : 0;
            $nextMin = $nextLevel->min_bet;

            $percentage = (($deposit_amount - $currentMin) / ($nextMin - $currentMin)) * 100;
            $percentage = round($percentage, 2); // optional rounding
        } else {
            $percentage = 100; // user is already at max level
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_name' => $this->user_name,
            'refer_code' => $this->refer_code,
            'role' => $this->role,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'email' => $this->email,
            'currency' => $this->currency ?? 'bdt',
            'balance' => $this->balance ?? 0,
            'turnover' => $this->turnover  > 0 ? $this->turnover : 0,
            'image_url' =>  $this->image_url ? asset('storage/'. $this->image_url) : null,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'level' => $currentLevel,
            'deposit_amount' => $deposit_amount,
            'today_bonus' => $today_bonus ? true : false,
            'level_complete' => $percentage,
            'next_level_amount' => $nextLevel?$nextLevel->min_bet:0,
            'refer_users' => $this->role == 'user'? $this->refer_users->count() :$this->affiliate_users->count() ,
        ];
    }
}
