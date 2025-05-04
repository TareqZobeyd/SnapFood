<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Food;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $foods = Food::all();
        $banners = Banner::all();
        
        // Get popular restaurants (based on order count)
        $popularRestaurants = Restaurant::withCount('orders')
            ->with('category')
            ->orderBy('orders_count', 'desc')
            ->take(6)
            ->get();
            
        // Get all categories
        $categories = Category::where('type', 'food')->get();

        return view('home', compact('foods', 'banners', 'popularRestaurants', 'categories'));
    }
}
