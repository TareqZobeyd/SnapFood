<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
        $restaurant = auth()->user()->restaurant;

        $query = $restaurant->orders()->where('restaurant_id', $restaurant->id);

        if ($status) {
            $query->where('seller_status', $status);
        }
        if ($foodId) {
            $query->whereHas('foods', function ($foodQuery) use ($foodId) {
                $foodQuery->where('food.id', $foodId);
            });
        }
        if ($request->input('filter_last_week')) {
            $query->whereBetween('created_at', [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay(),
            ]);
        }
        if ($request->input('filter_last_month')) {
            $query->whereBetween('created_at', [
                now()->subDays(29)->startOfDay(),
                now()->endOfDay(),
            ]);
        }
        $filteredOrders = $query->get();
        $totalRevenue = $filteredOrders->sum('total_amount');
        $orders = $query->get();

        return view('seller.reports.index', compact('filteredOrders', 'totalRevenue', 'foods', 'orders'));
    }
}
