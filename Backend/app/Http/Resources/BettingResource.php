<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->user_name,
            'game' => $this->game->english_name,
            'provider' => $this->provider,
            'game_type' => $this->game_type,
            'bet' => $this->bet,
            'win' => $this->win,
            'before_balance' => $this->before_balance,
            'after_balance' => $this->after_balance,
            'transaction_id' => $this->transaction_id,
            'round_id' => $this->round_id,
        ];
    }
}
