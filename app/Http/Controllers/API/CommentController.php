<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Food;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'food_id' => ['nullable', Rule::requiredIf(is_null($request->restaurant_id)), Rule::exists('food', 'id')],
            'restaurant_id' => ['nullable', Rule::requiredIf(is_null($request->food_id)), Rule::exists('restaurants', 'id')],
        ]);

        if (!is_null($request->food_id)) {
            $comments = Comment::query()->where(['food_id' => $request->food_id, 'user_id' => auth()->user()->id])->orderByDesc('created_at')->get();
            return response(['Comments' => ($comments)]);
        }

        if (!is_null($request->restaurant_id)) {
            $comments = [];
            $orders = Order::query()->where(['restaurant_id' => $request->restaurant_id, 'user_id' => auth()->user()->id])->get();
            foreach ($orders as $order) {
                foreach ($order->comments as $comment) {
                    $comments[$comment->created_at->format('Y-m-d-H-i-s')] = $comment;
                }
            }
            ksort($comments);
            return response(['Comments' => ($comments)]);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'cart_id' => ['required', Rule::exists('orders', 'id')],
            'food_id' => ['required', Rule::exists('food', 'id')],
            'score' => 'required|integer|min:1|max:5',
            'message' => 'required|string'
        ]);

        Comment::query()->create([
            'order_id' => $request->cart_id,
            'food_id' => $request->food_id,
            'user_id' => auth()->user()->id,
            'message' => $request->message,
            'score' => $request->score,
        ]);


        return response(['Message' => 'comment created successfully']);


    }

}
