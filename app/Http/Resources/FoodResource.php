<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $categoryName = Category::query()->find($this->category_id)->name;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $categoryName,
            'price' => $this->price,
            $this->mergeWhen(!is_null($this->discount_id),
                [
                    'off' =>
                        [
                            'label' => $this->discount?->value,
                            'factor' => (100 - $this->discount?->value)/100
                        ]
                ]
            ),
        ];
    }
}
