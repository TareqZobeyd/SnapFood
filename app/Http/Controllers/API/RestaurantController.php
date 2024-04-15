<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantFoodsResource;
use App\Http\Resources\RestaurantResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    public function index()
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
                    DB::raw('(6371 * acos(cos(radians(?)) * cos(radians(restaurants.latitude)) *
                     cos(radians(restaurants.longitude) - radians(?)) + sin(radians(?)) * sin(radians(restaurants.latitude))))
                      AS distance'),
                    DB::raw('5 as userLatitude')
                )
                ->addBinding([$userLatitude, $userLongitude, $userLatitude], 'select')
                ->having('distance', '<=', $maxDistance)
                ->orderBy('distance', 'asc')
                ->get();

            $responseRestaurants = RestaurantResource::collection($restaurants);

            return response($responseRestaurants);
        } else {
            return response(['error' => 'user does not have a valid address.'], 400);
        }
    }

    public function show($id)
    {
        $restaurant = Restaurant::query()->withCount('comments')->find($id);

        if (!$restaurant) {
            return response(['message' => "this restaurant doesn't exist"], 404);
        }

        return new RestaurantResource($restaurant);
    }

    public function food($id)
    {

        $restaurant_IDs = Restaurant::all()->pluck('id')->toArray();
        if (!in_array($id, $restaurant_IDs)) return \response(['message' => "'this restaurant isn't exist"]);

        return response(["foods details of restaurant number $id:"
        => new RestaurantFoodsResource(Restaurant::query()->find($id))]);
    }
}
