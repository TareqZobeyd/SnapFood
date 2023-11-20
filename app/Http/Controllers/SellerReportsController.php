<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;

class SellerReportsController extends Controller
{
    public function index()
    {
        $restaurant = auth()->user()->restaurant;
        $orders = $restaurant->orders;
        $totalRevenue = $orders->sum('total_amount');
        $foods = Food::all();

        return view('seller.reports.index', compact('orders', 'totalRevenue', 'foods'));
    }

    public function filter(Request $request)
    {
        $status = $request->input('seller_status');
        $foodId = $request->input('food_id');
        $foods = Food::all();
        $orders =Order::all();
        $restaurant = auth()->user()->restaurant;

        $query = $restaurant->orders();

        if ($status) {
            $query->where('seller_status', $status);
        }

        if ($foodId) {
            $query->whereHas('foods', function ($foodQuery) use ($foodId) {
                $foodQuery->where('id', $foodId);
            });
        }

        $filteredOrders = $query->get();
        $totalRevenue = $filteredOrders->sum('total_amount');

        return view('seller.reports.index', compact('filteredOrders', 'totalRevenue', 'foods', 'orders'));
    }
}
