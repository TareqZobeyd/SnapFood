<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Food;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function showUsers()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }
    public function showComments(Request $request)
    {
        $commentsQuery = Comment::query();

        if ($request->filled('food_id')) {
            $foodId = $request->input('food_id');
            $commentsQuery->whereHas('orders.foods', function ($query) use ($foodId) {
                $query->where('food_order.food_id', $foodId);
            });
        }

        if ($request->filled('restaurant_id')) {
            $restaurantId = $request->input('restaurant_id');
            $commentsQuery->whereHas('orders', function ($query) use ($restaurantId) {
                $query->where('orders.restaurant_id', $restaurantId);
            });
        }

        $comments = $commentsQuery->get();
        $foods = Food::all();
        $restaurants = Restaurant::all();

        return view('admin.comments.index', compact('comments', 'foods', 'restaurants'));
    }
    public function softDeleteComment($id)
    {
        $comment = Comment::query()->find($id);

        if ($comment) {
            $comment->delete();
            return redirect()->route('admin.comments')->with('success', 'comment soft deleted successfully.');
        }

        return redirect()->route('admin.comments')->with('error', 'comment not found.');
    }
}
