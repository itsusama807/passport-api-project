<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
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
            'imageable_type' => class_basename($this->imageable_type),
            'imageable_id' => $this->imageable_id,
            'url' => $this->url,
            'full_url'=> $this->full_url,
            'created_at'=> $this->created_at?->toDateTimeString(),
        ];
    }
}
