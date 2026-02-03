<?php

namespace App\Http\Resources;

use App\Models\Product;
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
        return [
            'id' => $this?->id,
            'name' => (string)$this?->name,
            'products' => $this->products->map(function ($products) {
                return [
                    'id' => $products->id,
                    'name' => $products->name,
                    'price' => $products->price,
                    'created_at' => $products->created_at->toDateTimeString(),
                ];
            }),
            'images' => ImageResource::collection($this->whenLoaded('images')),
            'created_at' => $this?->created_at?->toDateTimeString(),
            'user' => new UserBasicResource($this->whenLoaded('user'))
        ];
    }
}
