<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function getAllCards()
    {
        $orders = Order::with('restaurant', 'foods')->get();

        $transformedOrders = $orders->map(function ($order) {
            return array_merge($order->toArray(), [
                'additional_info' => $order->additional_info,
            ]);
        });

        return response(['orders' => $transformedOrders]);
    }


    public function getCard($id)
    {
        $order = Order::query()->find($id);

        return response($order);
    }

    public function add(Request $request)
    {
        $request->validate([
            'food_id' => 'required',
            'count' => 'required|integer|min:1',
        ]);

        $food = Food::query()->find($request->food_id);

        $order = Order::query()->where([
            'user_id' => auth()->user()->id,
            'restaurant_id' => $food->restaurant->id,
            'customer_status' => 'unpaid',
        ])->first();

        $foodPrice = $food->discounted_price * $request->count;

        if ($order) {
            $existingFood = $order->foods->find($request->food_id);

            if ($existingFood) {
                $existingFood->pivot->count += $request->count;
                $existingFood->pivot->save();
            } else {
                $order->foods()->attach($food, ['count' => $request->count]);
            }

            $order->total_amount += $foodPrice;
            $order->save();
        } else {
            $order = Order::query()->create([
                'user_id' => auth()->user()->id,
                'restaurant_id' => $food->restaurant->id,
                'total_amount' => $foodPrice,
            ]);

            $order->foods()->attach($food, ['count' => $request->count]);
        }

        return response(['Message' => 'Food added to cart successfully', 'Cart ID' => $order->id]);
    }


    public function update(Request $request)
    {
        $request->validate([
            'food_id' => ['required',],
            'count' => 'required|integer|min:1'
        ]);

        $order = Order::query()->where(['restaurant_id' => Food::query()->find($request->food_id)->restaurant->id,
            'user_id' => auth()->user()->id, 'customer_status' => 'unpaid'])->first();
        if ($order == null) return \response(['Message' => "You don't have unpaid card"]);

            $foods = $order->foods->first()->pivot->pluck('food_id')->toArray();
            if (!in_array($request->food_id, $foods)) return response("this food isn't added yet");

            $pivot = $order->foods->first()->pivot->where('food_id', $request->food_id)->first();
            $food_id = $pivot->food_id;
            $oldCount = $pivot->count;
            $pivot->count = $request->count;
            $pivot->save();

            $order->total_amount += ((Food::query()->find($food_id)->discounted_price * $request->count)
                - (Food::query()->find($food_id)->discounted_price * $oldCount));
            $order->save();

            return response(['Message' => 'count of food is updated']);
    }


    public function payCard($id)
    {
        $order = Order::query()->find($id);
        if ($order == null) return \response(['Message' => "this isn't your cart"]);

        $order->customer_status = 'paid';
        $order->save();
        return response(['Message' => "cart number $id paid successfully"]);
    }
}
