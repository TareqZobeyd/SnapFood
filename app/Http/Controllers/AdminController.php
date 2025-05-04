<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Food;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // آمار امروز
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $todayRevenue = Order::whereDate('created_at', Carbon::today())->sum('total_price');
        $totalRestaurants = Restaurant::count();
        $totalUsers = User::count();

        // سفارشات اخیر
        $recentOrders = Order::with(['user', 'restaurant'])
            ->latest()
            ->take(5)
            ->get();

        // درآمد هفتگی
        $weeklyRevenue = [
            'labels' => [],
            'data' => []
        ];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Order::whereDate('created_at', $date)->sum('total_price');
            
            $weeklyRevenue['labels'][] = $date->format('Y-m-d');
            $weeklyRevenue['data'][] = $revenue;
        }

        // توزیع سفارشات بر اساس رستوران
        $restaurantOrders = [
            'labels' => [],
            'data' => []
        ];

        $restaurants = Restaurant::withCount('orders')->get();
        foreach ($restaurants as $restaurant) {
            $restaurantOrders['labels'][] = $restaurant->name;
            $restaurantOrders['data'][] = $restaurant->orders_count;
        }

        return view('admin.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'totalRestaurants',
            'totalUsers',
            'recentOrders',
            'weeklyRevenue',
            'restaurantOrders'
        ));
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
