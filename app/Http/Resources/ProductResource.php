<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id,
            'name' => (string)$this?->name,
            'price' => $this?->price,
            'categories' => $this->categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name
                ];
            }),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this?->created_at?->toDateTimeString(),
            'user' => new UserBasicResource($this->whenLoaded('user'))
        ];
    }
}
