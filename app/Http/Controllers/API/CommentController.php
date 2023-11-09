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
            'food_id' => 'nullable|exists:foods,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ]);

        $user = auth()->user();

        if (!is_null($request->food_id)) {
            $comments = Comment::query()
                ->where(['food_id' => $request->food_id, 'user_id' => $user->id])
                ->with(['user', 'order'])
                ->orderByDesc('created_at')
                ->get();

            $transformedComments = $comments->map(function ($comment) {
                return $comment->additional_info;
            });

            return response(['comments' => $transformedComments]);
        }

        if (!is_null($request->restaurant_id)) {
            $orders = Order::query()
                ->where(['restaurant_id' => $request->restaurant_id, 'user_id' => $user->id])
                ->get();

            $comments = collect([]);
            foreach ($orders as $order) {
                $orderComments = $order->comments->map(function ($comment) {
                    return $comment->additional_info;
                });
                $comments = $comments->concat($orderComments);
            }

            $sortedComments = $comments->sortBy('created_at')->values();

            return response(['comments' => $sortedComments]);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'score' => 'required|integer|min:1|max:5',
            'message' => 'required|string',
        ]);
        $order = $request->order_id;

        Comment::query()->create([
            'user_id' => auth()->user()->id,
            'order_id' => $order,
            'message' => $request->message,
            'score' => $request->score,
        ]);

        return response(['Message' => 'Comment created successfully']);
    }
}
