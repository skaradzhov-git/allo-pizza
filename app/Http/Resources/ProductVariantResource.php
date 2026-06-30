<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (float) $this->price,
            'size_label' => $this->size_label,
            'weight' => $this->weight,
            'diameter' => $this->diameter,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];
    }
}
