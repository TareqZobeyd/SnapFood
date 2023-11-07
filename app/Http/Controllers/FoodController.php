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
        return view('foods.list', compact('foods'));
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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'food_discount_id' => 'nullable|exists:food_discounts,id',
        ]);
        $user = Auth::user();
        $restaurant = $user->restaurant;
        $originalPrice = $request->input('price');
        $foodDiscountId = $request->input('food_discount_id');
        $discountedPrice = $this->calculateDiscountedPrice($originalPrice, $foodDiscountId);
        Food::query()->create([
            'name' => $request->input('name'),
            'price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'category_id' => $request->input('category_id'),
            'food_discount_id' => $foodDiscountId,
            'restaurant_id' => $restaurant->id,
        ]);

        return redirect()->route('foods.index')->with('success', 'Food created successfully.');
    }
    private function calculateDiscountedPrice($originalPrice, $foodDiscountId)
    {
        $discount = FoodDiscount::query()->find($foodDiscountId)->discount_percentage;

        $discountedPrice = $originalPrice - ($originalPrice * ($discount / 100));

        return $discountedPrice;
    }

    public function show($id)
    {
        // Display a specific food
    }

    public function edit($id)
    {
        $food = Food::find($id);
        $categories = Category::all();
        return view('foods.edit', compact('food', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $food = Food::find($id);
        if (!$food) {
            return redirect()->route('foods.index')->with('error', 'Food not found.');
        }

        $food->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
        ]);

        return redirect()->route('foods.list')->with('success', 'Food updated successfully.');
    }

    public function destroy($id)
    {
        $food = Food::find($id);
        if ($food) {
            $food->delete();
            return redirect()->route('foods.index')->with('success', 'Food deleted successfully.');
        }

        return redirect()->route('foods.index')->with('error', 'Food not found.');
    }
}
