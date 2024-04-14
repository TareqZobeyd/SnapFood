<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFoodRequest;
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

    public function store(StoreFoodRequest $request)
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;
        $originalPrice = $request->input('price');
        $foodDiscountId = $request->input('food_discount_id');
        $discountPercentage = $request->input('custom_discount');
        $discountedPrice = $this->calculateDiscountedPrice($originalPrice, $foodDiscountId, $discountPercentage);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('food_images', 'public');
        }
        Food::query()->create([
            'name' => $request->input('name'),
            'price' => $originalPrice,
            'discounted_price' => $discountedPrice,
            'category_id' => $request->input('category_id'),
            'food_discount_id' => $foodDiscountId,
            'custom_discount' => $discountPercentage,
            'restaurant_id' => $restaurant->id,
            'image_path' => $imagePath,

        ]);

        return redirect()->route('food.index')->with('success', 'food created successfully.');
    }

    public function show($id)
    {
        // Display a specific food
    }

    public function edit(Food $food)
    {
        $categories = Category::all();
        $discounts = FoodDiscount::all();
        return view('foods.edit', compact('food', 'categories', 'discounts'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'food_discount_id' => 'nullable|exists:food_discounts,id',
            'custom_discount' => 'nullable|numeric|between:5,95',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $discountPercentage = $request->input('custom_discount') ?? ($food->food_discount ? $food->food_discount->discount_percentage : null);

        $food->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'food_discount_id' => $request->input('food_discount_id'),
            'custom_discount' => $discountPercentage,
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('food_images', 'public');
            $food->update(['image_path' => $imagePath]);
        }

        return redirect()->route('food.index')->with('success', 'Food updated successfully.');
    }

    public function destroy(Food $food)
    {
            $food->delete();

            return redirect()->route('food.index')->with('success', 'food deleted successfully.');
    }
}
