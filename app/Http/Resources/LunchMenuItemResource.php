<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LunchMenuItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section' => $this->section,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'is_spicy' => $this->is_spicy,
            'is_hit' => $this->is_hit,
            'is_new' => $this->is_new,
            'sort_order' => $this->sort_order,
        ];
    }
}
