<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\FoodDiscount;
use Illuminate\Http\Request;

class FoodDiscountController extends Controller
{
    public function index()
    {
        $foodDiscounts = FoodDiscount::all();
        return view('admin.food_discounts.index', compact('foodDiscounts'));
    }

    public function create()
    {
        return view('admin.food_discounts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'discount_percentage' => 'required|numeric',
            'food_party' => 'nullable|string',
        ]);
        $foodDiscount = FoodDiscount::query()->create([
            'restaurant_id' => $data['restaurant_id'],
            'discount_percentage' => $data['discount_percentage'],
            'food_party' => $data['food_party'],
        ]);

        if ($foodDiscount->restaurant_id) {
            $foodItems = Food::query()->where('restaurant_id', $foodDiscount->restaurant_id)->get();
            foreach ($foodItems as $foodItem) {
                $newPrice = $foodItem->price * (1 - ($foodDiscount->discount_percentage / 100));
                $foodItem->update(['price' => $newPrice]);
            }
        }

        return redirect()->route('food_discounts.index')->with('success', 'Discount Created Successfully.');
    }


    public function show(FoodDiscount $foodDiscount)
    {
        return view('food_discounts.show', compact('foodDiscount'));
    }

    public function edit(FoodDiscount $foodDiscount)
    {
        return view('admin.food_discounts.edit', compact('foodDiscount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'restaurant_id' => 'nullable|exists:restaurants,id',
            'discount_percentage' => 'required|numeric',
            'food_party' => 'nullable|string',
        ]);

        $foodDiscount = FoodDiscount::query()->find($id);

        if (!$foodDiscount) {
            return redirect()->route('food_discounts.index')->with('error', 'Food Discount not found.');
        }

        $foodDiscount->update([
            'restaurant_id' => $request->input('restaurant_id'),
            'discount_percentage' => $request->input('discount_percentage'),
            'food_party' => $request->input('food_party'),
        ]);

        return redirect()->route('food_discounts.index')->with('success', 'Food Discount updated successfully.');
    }

    public function destroy($id)
    {
        $foodDiscount = FoodDiscount::query()->find($id);

        if ($foodDiscount) {
            $foodDiscount->delete();
            return redirect()->route('food_discounts.index')->with('success', 'Food Discount deleted successfully.');
        }

        return redirect()->route('food_discounts.index')->with('error', 'Food Discount not found.');
    }


}
