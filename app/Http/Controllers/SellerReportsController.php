<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class SellerReportsController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->restaurant->orders;
        $totalRevenue = $orders->sum('total_amount');
        $foods = Food::all();

        return view('seller.reports.index', compact('orders', 'totalRevenue', 'foods'));
    }

    public function filter(Request $request)
    {
        $status = $request->input('status');
        $foodId = $request->input('food_id');

        $restaurant = auth()->user()->restaurant;

        $query = $restaurant->orders();

        if ($status) {
            $query->where('status', $status);
        }

        if ($foodId) {
            $query->whereHas('food', function ($foodQuery) use ($foodId) {
                $foodQuery->where('id', $foodId);
            });
        }
        $filteredOrders = $query->get();
        $totalRevenue = $filteredOrders->sum('total_price');

        return view('seller.reports.index', compact('filteredOrders', 'totalRevenue'));
    }
}
