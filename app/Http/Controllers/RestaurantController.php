<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Models\Category;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        return view('admin.restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('restaurants.create', compact('categories'));
    }

    public function store(StoreRestaurantRequest $request)
    {
        $user = Auth::user();

        Restaurant::query()->create([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'bank_account' => $request->input('bank_account'),
            'user_id' => $user->id,
        ]);

        if (!$user->hasRole('seller')) {
            $sellerRole = Role::query()->where('name', 'seller')->firstOrFail();
            $user->assignRole($sellerRole);
        }

        return redirect()->route('restaurants.create')->with('success', 'Restaurant details completed.');
    }

    public function show($id)
    {
        // Display a specific food
    }

    public function edit(Restaurant $restaurant)
    {
        $categories = Category::all();

        return view('admin.restaurants.edit', compact('restaurant', 'categories'));
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {

        $is_open = $request->input('is_open') === 'on';

        $restaurant->update([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'bank_account' => $request->input('bank_account'),
            'is_open' => $is_open,
            'delivery_cost' => $request->input('delivery_cost'),
            'working_hours' => $request->input('working_hours'),
        ]);

        return redirect()->route('home')->with('success', 'restaurant details updated successfully.');
    }

    public function destroy(Restaurant $restaurant)
    {

        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')->with('success', 'restaurant deleted successfully.');
    }
}
