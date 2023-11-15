<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = Restaurant::all();

        $responseRestaurants = $restaurants->map(function ($restaurant) {
            return [
                'id' => $restaurant->id,
                'title' => $restaurant->name,
                'address' => [
                    'address' => $restaurant->address,
                    'latitude' => $restaurant->latitude,
                    'longitude' => $restaurant->longitude,
                ],
                'is_open' => (bool)$restaurant->is_open,
                'score' => $restaurant->score ?? null,
            ];
        });
        return response($responseRestaurants);
    }


    public function show($id)
    {
        $restaurant = Restaurant::query()->find($id);
        $comments = Comment::query()->find($id);

        if (!$restaurant) {
            return response(['Message' => "This restaurant doesn't exist"], 404);
        }
        $schedule = [
            'saturday' => [
                'start' => '11:00',
                'end' => '23:00',
            ],
            'sunday' => [
                'start' => '11:00',
                'end' => '23:00',
            ],
            'monday' => [
                'start' => '11:00',
                'end' => '23:00',
            ],
            'tuesday' => [
                'start' => '11:00',
                'end' => '23:00',
            ],
            'wednesday' => [
                'start' => '11:00',
                'end' => '23:00',
            ],
            'thursday' => [
                'start' => '11:00',
                'end' => '23:00',
            ],
            'friday' => null,
        ];

        $response = [
            'id' => $restaurant->id,
            'title' => $restaurant->name,
            'address' => [
                'address' => $restaurant->address,
                'latitude' => $restaurant->latitude,
                'longitude' => $restaurant->longitude,
            ],
            'is_open' => (bool)$restaurant->is_open,
            'score' => $comments->score ?? null,
            'comments_count' => $restaurant->comments_count ?? null,
            'schedule' => $schedule,
        ];
        return response($response);
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
