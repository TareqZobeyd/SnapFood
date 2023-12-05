<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'food_id' => 'required',
            'count' => 'required|integer|min:1',
        ]);

        $foodId = $request->food_id;
        $count = $request->count;

        $food = Food::query()->find($foodId);

        $userId = $request->user()->id;

        if ($this->cartExistsForFood($userId, $foodId)) {
            return response(['error' => 'a cart with the specified food already exists.']);
        }

        $cartId = Redis::incr("cart_counter:$userId");

        Redis::set("cart:$userId:$foodId", $cartId);

        $cart = $this->getRedisCart($userId, $cartId);

        $cart['foods'] = $cart['foods'] ?? [];

        $cart['id'] = $cartId;
        $cart['user_id'] = $userId;
        $cart['restaurant_id'] = $food->restaurant_id;

        $this->updateCartWithFood($cart, $food, $count);

        return response(['message' => 'Food added to the cart successfully']);
    }

    private function cartExistsForFood($userId, $foodId)
    {
        return Redis::exists("cart:$userId:$foodId");
    }

    private function transformCart($cart)
    {

        return [
            'id' => $cart['id'],
            'food' => collect($cart['foods'])->map(function ($food) {
                return [
                    'id' => $food['id'],
                    'title' => $food['name'],
                    'count' => $food['count'],
                    'price' => $food['price'],
                ];
            }),
            'created_at' => "",
            'updated_at' => "",
        ];
    }

    private function updateCartWithFood($cart, $food, $count)
    {
        $existingFood = collect($cart['foods'])->where('id', $food->id)->first();

        if ($existingFood) {
            $existingFood['count'] += $count;
        } else {
            $cart['foods'][] = [
                'id' => $food->id,
                'name' => $food->name,
                'count' => $count,
                'price' => $food->discounted_price,
                'restaurant_id' => $food->restaurant->id,
            ];
        }

        $cart['total_amount'] = $cart['total_amount'] ?? 0;

        $cart['total_amount'] += $food->discounted_price * $count;

        $this->storeRedisCart($cart['id'], $cart);
    }

    private function getRedisCart($userId, $cartId)
    {
        return json_decode(Redis::get("cart:$userId:$cartId"), true);
    }

    private function storeRedisCart($id, $cart)
    {
        Redis::set("cart:$id", json_encode($cart));
    }
}
