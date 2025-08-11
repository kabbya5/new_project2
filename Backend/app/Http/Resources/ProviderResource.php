<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
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
            'english_name' => $this->english_name,
            'bangla_name' => $this->bangla_name,
            'hindi_name' => $this->hindi_name,
            'slug' => $this->slug,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'position' => $this->position,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'games' => GameResource::collection($this->whenLoaded('games')),
        ];
    }
}
