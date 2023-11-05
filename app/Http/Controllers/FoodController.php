<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Category;
use Illuminate\Http\Request;
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
        $foodCategories = Category::query()->where('type', 'food')->get();
        return view('foods.index', compact('foodCategories'));
    }

    public function create()
    {
        // Show the form to create a new food
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'food_discount_id' => 'nullable|exists:food_discounts,id',
        ]);

        Food::query()->create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'food_discount_id' => $request->input('food_discount_id')
        ]);

        return redirect()->route('foods.index')->with('success', 'Food created successfully.');
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
