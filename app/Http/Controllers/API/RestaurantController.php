<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{

    public function index(Request $request)
    {
        $restaurants = Restaurant::all();
        return response(['Restaurants' => $restaurants]);
    }


    public function show($id)
    {
        $restaurant_IDs = Restaurant::all()->pluck('id')->toArray();
        if (!in_array($id, $restaurant_IDs)) return \response(['Message' => "'This restaurant isn't exist"]);

        return \response(["Restaurants number $id details:" => (Restaurant::query()->find($id))]);
    }

    public function food($id)
    {
        $restaurant = Restaurant::query()->find($id);

        if (!$restaurant) {
            return response(['Message' => "This restaurant doesn't exist"], 404);
        }
        $categories = Category::query()->where('id', $restaurant->category_id)->get();

        $responseCategories = $categories->map(function ($category) use ($restaurant) {
            $foods = $category->foods;

            $foodDetails = $foods->map(function ($food) {
                $foodDetails = [
                    'id' => $food->id,
                    'title' => $food->name,
                    'price' => $food->price,
                ];

                if ($food->food_discount_id) {
                    $foodDetails['off'] = [
                        'label' => $food->food_discount_id,
                    ];
                }
                return $foodDetails;
            });
            return [
                'id' => $category->id,
                'title' => $category->name,
                'foods' => $foodDetails,
            ];
        });
        return response(['categories' => $responseCategories]);
    }


}
