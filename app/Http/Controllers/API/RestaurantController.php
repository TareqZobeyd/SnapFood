<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantFoodsResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user && $user->addresses()->exists()) {
            $userAddress = $user->addresses()->where('active', '1')->first();

            if ($userAddress) {
                $userLatitude = $userAddress->latitude;
                $userLongitude = $userAddress->longitude;

            } else {
                return response(['error' => 'you do not have an active address.'], 400);
            }

            $maxDistance = 5;

            $restaurants = Restaurant::query()
                ->select(
                    'restaurants.id',
                    'restaurants.name',
                    'restaurants.address',
                    'restaurants.latitude',
                    'restaurants.longitude',
                    'restaurants.is_open',
                    DB::raw('(6371 * acos(cos(radians(?)) * cos(radians(restaurants.latitude)) * cos(radians(restaurants.longitude)
                     - radians(?)) + sin(radians(?)) * sin(radians(restaurants.latitude)))) AS distance'),
                    DB::raw('5 as userLatitude')
                )
                ->addBinding([$userLatitude, $userLongitude, $userLatitude], 'select')
                ->having('distance', '<=', $maxDistance)
                ->orderBy('distance', 'asc')
                ->get();

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
        } else {
            return response(['error' => 'user does not have a valid address.'], 400);
        }
    }

    public function show($id)
    {
        $restaurant = Restaurant::query()->find($id);
        $comments = Comment::query()->find($id);

        if (!$restaurant) {
            return response(['message' => "this restaurant doesn't exist"], 404);
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

        $restaurant_IDs = Restaurant::all()->pluck('id')->toArray();
        if (!in_array($id, $restaurant_IDs)) return \response(['message' => "'this restaurant isn't exist"]);

        return response(["foods details of restaurant number $id:" => new RestaurantFoodsResource(Restaurant::query()->find($id))]);
    }
}
