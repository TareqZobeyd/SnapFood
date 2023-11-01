<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return view('restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('restaurants.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:restaurants',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'required|string|min:11',
            'address' => 'required|string',
            'bank_account' => 'required|string',
        ]);
        Restaurant::query()->create([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'bank_account' => $request->input('bank_account'),
        ]);
        return redirect()->route('restaurants.create')->with('success', 'Restaurant details completed.');
    }

    public function show($id)
    {
        // Display a specific food
    }

    public function edit($id)
    {
        $restaurant = Restaurant::find($id);
        $categories = Category::all();

        if (!$restaurant) {
            return redirect()->route('restaurants.index')->with('error', 'Restaurant not found.');
        }

        return view('restaurants.edit', compact('restaurant', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'phone' => 'required|string',
            'address' => 'required|string',
            'bank_account' => 'required|string',
        ]);

        $restaurant = Restaurant::query()->find($id);

        if (!$restaurant) {
            return redirect()->route('restaurants.index')->with('error', 'Restaurant not found.');
        }

        $restaurant->update([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'bank_account' => $request->input('bank_account'),
        ]);

        return redirect()->route('restaurants.index')->with('success', 'Restaurant details updated successfully.');
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::query()->find($id);

        if (!$restaurant) {
            return redirect()->route('restaurants.index')->with('error', 'Restaurant not found.');
        }

        $restaurant->delete();

        return redirect()->route('restaurants.index')->with('success', 'Restaurant deleted successfully.');
    }
}
