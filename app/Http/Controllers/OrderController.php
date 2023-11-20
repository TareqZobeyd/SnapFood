<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        // Retrieve and display a list of orders
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        // Display a form to create a new order
        return view('orders.create');
    }

    public function store(Request $request)
    {
        // Store a new order in the database
        // Validate and save the order data
        // Redirect to the index page with a success message
    }

    public function show(Order $order)
    {
        // Display a specific order
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // Display a form to edit a specific order
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        // Update the specific order in the database
        // Validate and save the updated order data
        // Redirect to the index page with a success message
    }

    public function destroy(Order $order)
    {
        // Delete a specific order from the database
        // Redirect to the index page with a success message
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
