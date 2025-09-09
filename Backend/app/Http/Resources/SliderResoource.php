<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SliderResoource extends JsonResource
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
            'slider_name' => $this->slider_name,
            'slug' => $this->slug,
            'status' => $this->status,
            'slider_content' => $this->slider_content,
            'desktop_image' => $this->desktop_image ? asset('storage/'. $this->desktop_image)  : null,
            'mobile_image' => $this->mobile_image ? asset('storage/'. $this->mobile_image)  : null,
        ];
    }
}
