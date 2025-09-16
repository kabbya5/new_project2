<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->id,
            'english_name' => $this->english_name,
            'bangla_name' => $this->bangla_name,
            'hindi_name' => $this->hindi_name,
            'slug' => $this->slug,
            'position' => $this->position,
            'image_url' => $this->image_url ? asset('storage/'. $this->image_url)  : null,
            'providers' => ProviderResource::collection($this->whenLoaded('providers')),
            'games' => GameResource::collection($this->whenLoaded('games')),
        ];
    }
}
