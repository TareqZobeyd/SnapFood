<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\FoodDiscount;
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
        $discount = $this->food_discount;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'category' => $categoryName,
            'price' => $this->price,
            'off' => $this->when($discount, function () use ($discount) {
                $customDiscount = $this->custom_discount;
                return [
                    'label' => $customDiscount !== null ? $customDiscount . '%' : $discount->discount_percentage . '%',
                    'factor' => $customDiscount !== null ? (100 - $customDiscount) / 100 : (100 - $discount->discount_percentage) / 100,
                ];
            }),
        ];
    }
}
