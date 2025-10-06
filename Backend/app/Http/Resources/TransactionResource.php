<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $affiliat_amount = Transaction::where('order_sn', $this->order_sn)->where('type', 'agent_bouns')->first();
        $refer_amount = Transaction::where('order_sn', $this->order_sn)->where('type', 'refer_bonus')->first();

        return [
            'id' => $this->id,
            'user_nane' => $this->user->user_name,
            'affiliat_agent' => optional($this->affiliat_agent)->user_name,
            'refer_user' => optional($this->refer_user)->user_name,
            'type' => $this->type,
            'amount' => $this->amount,
            'affiliat_amount' =>  $affiliat_amount->amount ?? 0.00,
            'refer_amount' => $refer_amount->amount ?? 0.00,
            'provider' => $this->provider,
            'remark' => $this->remark,
            'order_sn' => $this->order_sn,
            'status' => $this->status,
            'created_time' => $this->created_at?->diffForHumans(),
        ];
    }
}
