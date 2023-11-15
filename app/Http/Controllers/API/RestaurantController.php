<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantFoodsResource;
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


    public function food($id){

        $restaurant_IDs = Restaurant::all()->pluck('id')->toArray();
        if (!in_array($id, $restaurant_IDs)) return \response(['Message' => "'This restaurant isn't exist"]);

        return \response(["Foods details of restaurant number $id:" => new RestaurantFoodsResource(Restaurant::query()->find($id))]);
    }

}
