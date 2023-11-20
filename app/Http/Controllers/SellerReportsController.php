<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerReportsController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->restaurant->orders;
        $totalRevenue = $orders->sum('total_price');

        return view('seller.reports.index', compact('orders', 'totalRevenue'));
    }

    public function filter(Request $request)
    {
        // Implement filtering logic based on status and foods
        // You can use $request->input('status') and $request->input('food_id') to get filter criteria

        // Return filtered results to the view
    }
}
