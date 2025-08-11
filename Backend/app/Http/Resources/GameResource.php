<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'provider_id' => $this->provider_id,
            'bangla_name' => $this->bangla_name,
            'english_name' => $this->english_name,
            'hindi_name' => $this->hindi_name,
            'position' => $this->position,
            'popularity' => $this->popularity,
            'url' => $this->url,
            'is_activce' => (bool) $this->is_active,
            'thumbnail' => $this->thumbnail ? asset('storage/'. $this->thumbnail)  : null,
            'provider' => new ProviderResource($this->whenLoaded('provider')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
        ];
    }
}
