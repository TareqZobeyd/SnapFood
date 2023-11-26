<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    public function getAllCarts()
    {
        $carts = $this->getRedisCarts();
        $transformedOrders = $carts->map(function ($order) {
            return [
                'id' => $order->id,
                'restaurant' => [
                    'title' => $order->restaurant->name,
                    'image' => $order->restaurant->image,
                ],
                'food' => $order->foods->map(function ($food) {
                    return [
                        'id' => $food->id,
                        'title' => $food->name,
                        'count' => $food->pivot->count,
                        'price' => $food->discounted_price,
                    ];
                }),
                'created_at' => '',
                'updated_at' => '',
            ];
        });
        return response(['carts' => $transformedOrders]);
    }

    public function getCart($id)
    {
        $cart = $this->getRedisCart($id);

        if (!$cart) {
            return response(['error' => 'Cart not found.']);
        }

        return response($cart);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'food_id' => 'required',
            'count' => 'required|integer|min:1',
        ]);

        $food = Food::query()->find($request->food_id);

        if (!$food) {
            return response(['error' => 'Food not found.']);
        }

        $cartId = $request->user()->id;

        $cart = $this->getRedisCart($cartId);

        if ($cart) {
            $existingFood = collect($cart['foods'])->where('id', $food->id)->first();

            if ($existingFood) {
                $existingFood['count'] += $request->count;
            } else {
                $cart['foods'][] = [
                    'id' => $food->id,
                    'name' => $food->name,
                    'count' => $request->count,
                    'price' => $food->discounted_price,
                ];
            }

            $cart['total_amount'] += $food->discounted_price * $request->count;
        } else {
            $cart = [
                'id' => $cartId,
                'foods' => [
                    [
                        'id' => $food->id,
                        'name' => $food->name,
                        'count' => $request->count,
                        'price' => $food->discounted_price,
                    ],
                ],
                'total_amount' => $food->discounted_price * $request->count,
            ];
        }

        $this->storeRedisCart($cartId, $cart);

        return response(['Message' => 'Food added to cart successfully']);
    }

    public function payCart(Request $request)
    {
        $cartId = $request->user()->id;
        $cart = $this->getRedisCart($cartId);
        $food = Food::query()->find($request->food_id);

        if (!$cart) {
            return response(['error' => 'Cart not found.']);
        }

        $order = Order::query()->create([
            'user_id' => $request->user()->id,
            'restaurant_id' => $food->restaurant->id,
            'total_amount' => $cart['total_amount'],
        ]);

        foreach ($cart['foods'] as $cartFood) {
            $food = Food::find($cartFood['id']);
            $order->foods()->attach($food, ['count' => $cartFood['count']]);
        }

        $this->removeRedisCart($cartId);

        Mail::to($request->user()->email)->send(new OrderPlaced($order));

        return response(['Message' => 'Cart paid successfully', 'Order ID' => $order->id]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'food_id' => 'required',
            'count' => 'required|integer|min:1',
        ]);

        $cartId = $request->user()->id;
        $foodId = $request->food_id;

        $cart = $this->getRedisCart($cartId);

        if (!$cart) {
            return response(['error' => 'Cart not found.']);
        }

        $existingFood = collect($cart['foods'])->where('id', $foodId)->first();

        if (!$existingFood) {
            return response(['error' => 'Food not found in the cart.']);
        }

        $oldCount = $existingFood['count'];

        $existingFood['count'] = $request->count;

        $cart['total_amount'] += ($existingFood['price'] * $request->count) - ($existingFood['price'] * $oldCount);

        $this->storeRedisCart($cartId, $cart);

        return response(['Message' => 'Count of food is updated in the cart']);
    }

    // Other cart-related methods (update, remove, checkout) can be added here
    private function getRedisCarts()
    {
        return Redis::keys('cart:*');
    }

    private function getRedisCart($id)
    {
        return Redis::get("cart:$id");
    }

    private function storeRedisCart($id, $cart)
    {
        Redis::set("cart:$id", json_encode($cart));
    }

    private function removeRedisCart($id)
    {
        Redis::del("cart:$id");
    }
}
