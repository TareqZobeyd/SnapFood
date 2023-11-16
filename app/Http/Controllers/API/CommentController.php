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
            'food_id' => 'nullable|exists:food,id',
            'restaurant_id' => 'nullable|exists:restaurants,id',
        ]);

        $user = auth()->user();

        if (!is_null($request->food_id)) {
            $comments = Comment::query()
                ->where(['food_id' => $request->food_id, 'user_id' => $user->id])
                ->with(['user', 'order.food'])
                ->orderByDesc('created_at')
                ->get();

            $transformedComments = $comments->map(function ($comment) {
                return $this->transformComment($comment);
            });

            return response(['comments' => $transformedComments]);
        }

        if (!is_null($request->restaurant_id)) {
            $orders = Order::query()
                ->where(['restaurant_id' => $request->restaurant_id, 'user_id' => $user->id])
                ->with('food')
                ->get();

            $comments = collect([]);
            foreach ($orders as $order) {
                $orderComments = $order->comments->map(function ($comment) use ($order) {
                    return [
                        'author' => [
                            'name' => $comment->user->name,
                        ],
                        'food' => $order->foods->pluck('name')->toArray(),
                        'created_at' => '',
                        'score' => $comment->score,
                        'content' => $comment->message,
                    ];
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
            'order_id' => 'required|integer',
            'score' => 'required|integer|min:1|max:5',
            'message' => 'required|string',
        ]);
        $order = Order::query()->find($request->order_id);
        if (!$order) {
            return response(['error' => 'Order not found.']);
        }
        if ($order->seller_status !== 'delivered') {
            return response(['error' => 'You can only comment on delivered orders.']);
        }
        $existingComment = Comment::where('user_id', auth()->user()->id)
            ->where('order_id', $order->id)
            ->first();
        if ($existingComment) {
            return response(['error' => 'You have already commented on this order.']);
        }
        Comment::query()->create([
            'user_id' => auth()->user()->id,
            'order_id' => $order->id,
            'message' => $request->message,
            'score' => $request->score,
        ]);

        return response(['Message' => 'Comment created successfully']);
    }
}
