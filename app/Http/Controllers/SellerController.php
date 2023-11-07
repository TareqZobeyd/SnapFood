<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $restaurant = $user->restaurant;

        return view('seller.dashboard', compact('restaurant'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'food_discount_id' => 'required|exists:food_discounts,id',
        ]);

        $restaurant = auth()->user()->restaurant;

        $food = $restaurant->food()->create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'food_discount_id' => $request->input('food_discount_id'),
        ]);

        return redirect()->route('seller.food.index')->with('success', 'Food item created successfully.');
    }

    public function showRestaurant()
    {
        $user = auth()->user();
        $restaurant = $user->restaurant;

        return view('seller.restaurant', compact('restaurant'));
    }
}
