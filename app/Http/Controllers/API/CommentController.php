<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Order;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'food_id' => ['nullable', ($request->restaurant_id)],
            'restaurant_id' => ['nullable', ($request->food_id)],
        ]);

        if (!is_null($request->food_id)) {
            $comments = Comment::query()->where(['food_id' => $request->food_id,
                'user_id' => auth()->user()->id])->orderByDesc('created_at')->get();
            return response(['Comments' => ($comments)]);
        }

        if (!is_null($request->restaurant_id)) {
            $comments = [];
            $orders = Order::query()->where(['restaurant_id' => $request->restaurant_id,
                'user_id' => auth()->user()->id])->get();
            foreach ($orders as $order) {
                foreach ($order->comments as $comment) {
                    $comments[$comment->created_at->format('Y-m-d-H-i-s')] = $comment;
                }
            }
            ksort($comments);
            return response(['Comments' => ($comments)]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart_id' => 'required',
            'score' => 'required|integer|min:1|max:5',
            'message' => 'required|string',
        ]);

        Comment::query()->create([
            'order_id' => $request->order_id,
            'user_id' => auth()->user()->id,
            'message' => $request->message,
            'score' => $request->score,
        ]);

        return response(['Message' => 'Comment created successfully']);
    }

}
