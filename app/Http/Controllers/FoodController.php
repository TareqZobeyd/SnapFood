<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Category;
use App\Models\FoodDiscount;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class FoodController extends Controller
{
    public function list()
    {
        $foods = Food::all();
        return view('food.list', compact('foods'));
    }

    public function index()
    {
        $user = Auth::user();
        $foodCategories = Category::query()->where('type', 'food')->get();
        $discounts = FoodDiscount::all();
        $restaurants = Restaurant::all();
        return view('foods.index', compact('foodCategories', 'discounts', 'restaurants'));
    }

    public function create()
    {

    }

    private function calculateDiscountedPrice($originalPrice, $foodDiscountId, $customDiscount)
    {
        if ($customDiscount !== null) {
            return $originalPrice - ($originalPrice * ($customDiscount / 100));
        }
        if ($foodDiscountId !== null) {
            $foodDiscount = FoodDiscount::query()->find($foodDiscountId);
            if ($foodDiscount) {
                $discount = $foodDiscount->discount_percentage;
                return $originalPrice - ($originalPrice * ($discount / 100));
            }
        }
        return $originalPrice;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'food_discount_id' => 'nullable|exists:food_discounts,id',
            'custom_discount' => 'nullable|numeric|between:5,95',
        ]);
        $user = Auth::user();
        $restaurant = $user->restaurant;
        $originalPrice = $request->input('price');
        $foodDiscountId = $request->input('food_discount_id');
        $discountPercentage = $request->input('custom_discount');
        $discountedPrice = $this->calculateDiscountedPrice($originalPrice, $foodDiscountId, $discountPercentage);

        Food::query()->create([
            'name' => $request->input('name'),
            'price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'category_id' => $request->input('category_id'),
            'food_discount_id' => $foodDiscountId,
            'custom_discount' => $discountPercentage,
            'restaurant_id' => $restaurant->id,
        ]);

        return redirect()->route('food.index')->with('success', 'Food created successfully.');
    }


    public function show($id)
    {
        // Display a specific food
    }

    public function edit($id)
    {
        $food = Food::query()->find($id);
        $categories = Category::all();
        $discounts = FoodDiscount::all();
        return view('foods.edit', compact('food', 'categories', 'discounts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'food_discount_id' => 'nullable|exists:food_discounts,id',
            'custom_discount' => 'nullable|numeric|between:5,95',
        ]);
        $food = Food::query()->find($id);
        if (!$food) {
            return redirect()->route('food.index')->with('error', 'Food not found.');
        }
        $discountPercentage = $request->input('custom_discount') ?? ($food->food_discount ? $food->food_discount->discount_percentage : null);
        $food->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'food_discount_id' => $request->input('food_discount_id'),
            'custom_discount' => $discountPercentage,
        ]);

        return redirect()->route('foods.index')->with('success', 'Food updated successfully.');
    }

    public function destroy($id)
    {
        $food = Food::query()->find($id);
        if ($food) {
            $food->delete();
            return redirect()->route('food.index')->with('success', 'Food deleted successfully.');
        }

        return redirect()->route('foods.index')->with('error', 'Food not found.');
    }
}
