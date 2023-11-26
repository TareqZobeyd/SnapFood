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
        $cartIds = $this->getRedisCarts();
        $transformedOrders = collect();

        foreach ($cartIds as $cartId) {
            $cart = $this->getRedisCart($cartId);

            if ($cart && is_array($cart['foods'])) {
                $transformedOrders->push($this->transformCart($cart));
            }
        }

        return response(['carts' => $transformedOrders]);
    }

    public function getCart($id)
    {
        $cart = $this->getRedisCart($id);

        if ($cart && is_array($cart['foods'])) {
            return response($this->transformCart($cart));
        }

        return response(['error' => 'Cart not found or invalid data.']);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'food_id' => 'required',
            'count' => 'required|integer|min:1',
        ]);

        $food = Food::find($request->food_id);

        if (!$food) {
            return response(['error' => 'Food not found.']);
        }

        $cartId = $request->user()->id;
        $cart = $this->getOrCreateRedisCart($cartId);

        $this->updateCartWithFood($cart, $food, $request->count);

        return response(['Message' => 'Food added to cart successfully']);
    }

    public function payCart(Request $request)
    {
        $cartId = $request->user()->id;
        $cart = $this->getRedisCart($cartId);

        if ($cart && is_array($cart['foods'])) {
            $order = $this->createOrderFromCart($request, $cart);
            $this->removeRedisCart($cartId);

            $order->customer_status = 'paid';
            $order->save();
            Mail::to($request->user()->email)->send(new OrderPlaced($order));

            return response(['Message' => 'Cart paid successfully', 'Order ID' => $order->id]);
        }

        return response(['error' => 'Cart not found or invalid data.']);
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

        if ($cart && is_array($cart['foods'])) {
            $this->updateCartWithFoodCount($cart, $foodId, $request->count);

            return response(['Message' => 'Count of food is updated in the cart']);
        }

        return response(['error' => 'Cart not found or invalid data.']);
    }

    // Helper methods

    private function transformCart($cart)
    {
        return [
            'id' => $cart['id'],
            'restaurant' => [
                'title' => $cart['restaurant']['name'],
                'image' => $cart['restaurant']['image'],
            ],
            'food' => $cart['foods']->map(function ($food) {
                return [
                    'id' => $food['id'],
                    'title' => $food['name'],
                    'count' => $food['count'],
                    'price' => $food['price'],
                ];
            }),
            'created_at' => $cart['created_at'],
            'updated_at' => $cart['updated_at'],
        ];
    }

    private function getOrCreateRedisCart($id)
    {
        $cart = $this->getRedisCart($id);

        if (!$cart) {
            $cart = [
                'id' => $id,
                'foods' => [],
                'total_amount' => 0,
            ];
            $this->storeRedisCart($id, $cart);
        }

        return $cart;
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

        $cart['total_amount'] += $food->discounted_price * $count;

        $this->storeRedisCart($cart['id'], $cart);
    }

    private function createOrderFromCart($request, $cart)
    {
        $food = Food::query()->find($request->food_id);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'restaurant_id' => $food->restaurant->id,
            'total_amount' => $cart['total_amount'],
        ]);

        foreach ($cart['foods'] as $cartFood) {
            $food = Food::find($cartFood['id']);
            $order->foods()->attach($food, ['count' => $cartFood['count']]);
        }

        return $order;
    }

    private function updateCartWithFoodCount($cart, $foodId, $count)
    {
        $existingFood = collect($cart['foods'])->where('id', $foodId)->first();

        if ($existingFood) {
            $oldCount = $existingFood['count'];
            $existingFood['count'] = $count;
            $cart['total_amount'] += ($existingFood['price'] * $count) - ($existingFood['price'] * $oldCount);
            $this->storeRedisCart($cart['id'], $cart);
        }
    }

    // Other cart-related methods (remove) can be added here

    private function getRedisCarts()
    {
        return Redis::keys('cart:*');
    }

    private function getRedisCart($id)
    {
        return json_decode(Redis::get("cart:$id"), true);
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
