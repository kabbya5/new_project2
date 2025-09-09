<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecenlyPlayResoure extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->game->id,
            'provider_id' => $this->game->provider_id,
            'bangla_name' => $this->game->bangla_name,
            'english_name' => $this->game->english_name,
            'hindi_name' => $this->game->hindi_name,
            'position' => $this->game->position,
            'popularity' => $this->game->popularity,
            'url' => $this->game->url,
            'is_active' => (bool) $this->game->is_active,
            'thumbnail' => $this->game->thumbnail
                ? asset('storage/' . $this->game->thumbnail)
                : null,
            'provider' => new ProviderResource($this->game->provider),
            'categories' => CategoryResource::collection($this->game->categories),
        ];
    }
}
