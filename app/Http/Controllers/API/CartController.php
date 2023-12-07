<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Redis;

class CartController extends Controller
{
    public function getAllCarts()
    {
        $cartIds = $this->getRedisCarts();

        if (empty($cartIds)) {
            return response(['message' => 'No carts found.']);
        }
        $transformedOrders = [];

        foreach ($cartIds as $cartId) {
            $cart = $this->getRedisCart($cartId);

            if ($cart && is_array($cart['foods'])) {
                $transformedOrders[] = $this->transformCart($cart);
            }
        }
        $transformedOrders = collect($transformedOrders);

        if ($transformedOrders->isEmpty()) {
            return response(['message' => 'No valid carts found.']);
        }
        return response(['carts' => $transformedOrders]);
    }

    public function getCart($id, Request $request)
    {
        $cart = $this->getRedisCart($id);

        if ($cart && is_array($cart['foods']) && $cart['id'] == $request->user()->id) {
            return response($this->transformCart($cart));
        }

        return response(['error' => 'cart not found or invalid data.']);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:food,id',
            'count' => 'required|integer|min:1',
        ]);

        $food = Food::find($request->food_id);

        if (!$food) {
            return response(['error' => 'food not found.']);
        }

        $cartId = $request->user()->id;
        $cart = $this->getRedisCart($cartId);

        if ($this->foodExistsInCart($cart, $food->id)) {
            return response(['error' => 'food already exists in the cart.']);
        }

        $this->updateCartWithFood($cart, $food, $request->count);

        return response(['message' => 'food added to cart successfully']);
    }

    private function foodExistsInCart($cart, $foodId)
    {
        return collect($cart['foods'])->contains('id', $foodId);
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

            return response(['message' => 'cart paid successfully', 'order id' => $order->id]);
        }

        return response(['error' => 'cart not found or invalid data.']);
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

            return response(['message' => 'count of food is updated in the cart']);
        }

        return response(['error' => 'cart not found or invalid data.']);
    }

    private function transformCart($cart)
    {

        return [
            'id' => $cart['id'],
            'total_amount' => $cart['total_amount'],
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

    private function getOrCreateRedisCart($id)
    {
        $cart = $this->getRedisCart($id);

        if (!$cart) {
            $cart = [
                'id' => $id,
                'foods' => [],
                'total_amount' => 0,
                'restaurant_id' => null,
                'restaurant' => null,
            ];
            $food = Food::with('restaurant')->first();

            if ($food && $food->restaurant) {
                $cart['restaurant_id'] = $food->restaurant->id;
                $cart['restaurant'] = [
                    'name' => $food->restaurant->name,
                    'image' => $food->restaurant->image,
                ];
            }

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

        $order = Order::query()->create([
            'user_id' => $request->user()->id,
            'restaurant_id' => $cart['restaurant_id'],
            'total_amount' => $cart['total_amount'],
        ]);

        foreach ($cart['foods'] as $cartFood) {
            $food = Food::query()->find($cartFood['id']);
            $order->foods()->attach($food, ['count' => $cartFood['count']]);
        }

        return $order;
    }

    private function updateCartWithFoodCount($cart, $foodId, $count)
    {
        $existingFoodIndex = collect($cart['foods'])->search(function ($item) use ($foodId) {
            return $item['id'] == $foodId;
        });

        if ($existingFoodIndex !== false) {
            $existingFood = $cart['foods'][$existingFoodIndex];

            $existingFood['count'] = $count;

            $cart['total_amount'] = $count * $existingFood['price'];

            $cart['foods'][$existingFoodIndex] = $existingFood;

            $this->storeRedisCart($cart['id'], $cart);
        }
    }

    private function getRestaurantDetails($restaurantId)
    {
        $restaurant = Restaurant::query()->find($restaurantId);

        if ($restaurant) {
            return [
                'title' => $restaurant->name,
                'image' => $restaurant->image,
            ];
        }

        return null;
    }

    private function getRedisCarts()
    {
        $cartKeys = Redis::keys('cart:*');
        return $cartKeys;
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
