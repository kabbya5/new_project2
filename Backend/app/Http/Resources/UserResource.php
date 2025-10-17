<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'user_name' => $this->user_name,
            'refer_code' => $this->refer_code,
            'role' => $this->role,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'currency' => $this->currency ?? 'bdt',
            'balance' => $this->balance ?? 0,
            'turnover' => $this->turnover  > 0 ? $this->turnover : 0,
            'image_url' =>  $this->image_url ? asset('storage/'. $this->image_url) : null,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
