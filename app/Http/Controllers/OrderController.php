<?php

namespace App\Http\Controllers;

use App\Mail\OrderSent;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {

    }

    public function show(Order $order)
    {
        // Display a specific order
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {

    }

    public function destroy(Order $order)
    {

    }

    public function updateSellerStatus(Request $request, $id)
    {
        $request->validate([
            'seller_status' => 'required|in:pending,preparing,send,delivered',
        ]);

        $user = auth()->user();
        $order = Order::query()->where('id', $id)
            ->where('restaurant_id', $user->restaurant->id)
            ->firstOrFail();

        $order->seller_status = $request->seller_status;
        $order->save();

        $success = 'Seller status updated successfully.';
        $orders = Order::query()->where('restaurant_id', $user->restaurant->id)->get();
        if ($request->seller_status === 'send') {
            Mail::to($order->user->email)->send(new OrderSent($order));
        }
        return view('seller.orders', compact('orders', 'success'));
    }

    public function archive()
    {
        $user = auth()->user();
        $restaurantId = $user->restaurant->id;

        $deliveredOrders = Order::query()->where('seller_status', 'delivered')
            ->where('restaurant_id', $restaurantId)
            ->get();

        return view('seller.archive', compact('deliveredOrders'));
    }
}
