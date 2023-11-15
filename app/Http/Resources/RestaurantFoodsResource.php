<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantFoodsResource extends JsonResource
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
            'category' => $categoryName,
            'foods' => FoodResource::collection(Food::where('restaurant_id', $this->id)->get()),

        ];
    }
}
