<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'foods' => FoodResource::collection(Food::query()->where('restaurant_id', $this->id)->get()),

        ];
    }
}
