<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Food;
use App\Models\Order;
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

    public function getOrders(Request $request)
    {
        $user = auth()->user();
        $restaurant = $user->restaurant;

        $query = Order::query()->where('restaurant_id', $restaurant->id);

        if ($request->has('food_id')) {
            $query->whereHas('food', function ($foodQuery) use ($request) {
                $foodQuery->where('id', $request->input('food_id'));
            });
        }
        $orders = $query->get();
        return view('seller.orders', compact('orders'));
    }

    public function showComments(Request $request)
    {
        $user = auth()->user();
        $restaurant = $user->restaurant;
        $foods = Food::all();

        $commentsQuery = Comment::query()->whereHas('orders', function ($query) use ($restaurant) {
            $query->where('restaurant_id', $restaurant->id);
        });

        if ($request->filled('food_id')) {
            $foodId = $request->input('food_id');
            $commentsQuery->whereHas('orders.foods', function ($query) use ($foodId) {
                $query->where('food.id', $foodId);
            });
        }

        $comments = $commentsQuery->get();

        return view('seller.comments', compact('comments', 'foods', 'request'));
    }

    public function requestDelete($commentId)
    {
        $user = auth()->user();
        $foods = Food::all();
        $comments = Comment::all();
        $restaurant = $user->restaurant;
        $comment = Comment::query()->findOrFail($commentId);

        auth()->user();
        $comment->update(['delete_request' => true]);

        return view('seller.comments', compact('restaurant', 'foods', 'comments'))
            ->with('success', 'Delete request sent successfully.');
    }

    public function confirmComment($commentId)
    {
        $comments = Comment::all();
        $foods = Food::all();
        $comment = Comment::query()->findOrFail($commentId);
        $comment->update(['confirmed' => true]);

        return view('seller.comments', compact('foods', 'comments'))->with('success', 'Comment confirmed successfully.');
    }

    public function respond(Request $request, $commentId)
    {
        $user = auth()->user();
        $restaurant = $user->restaurant;
        $request->validate([
            'seller_response' => 'required|string',
        ]);

        $comment = Comment::query()->find($commentId);
        auth()->user();
        $comment->update(['seller_response' => $request->seller_response]);
        $comment->update(['confirmed' => true]);

        return view('seller.comments', compact('restaurant'))->with('success', 'respond added successfully');
    }
}
