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

        auth()->user();

        if (!is_null($request->food_id)) {
            $comments = Comment::query()
                ->join('food_order', 'comments.order_id', '=', 'food_order.order_id')
                ->where('food_order.food_id', $request->food_id)
                ->where('comments.confirmed', true)
                ->orderBy('comments.created_at', 'desc')
                ->get();

            $transformedComments = $comments->map(function ($comment) {
                return $this->transformCommentByFood($comment);
            });

            return response(['comments' => $transformedComments]);
        }

        if (!is_null($request->restaurant_id)) {
            $orders = Order::query()
                ->where(['restaurant_id' => $request->restaurant_id])
                ->with(['comments', 'foods'])
                ->get();

            $comments = $orders->flatMap(function ($order) {
                $confirmedComments = $order->comments->where('confirmed', true);

                return $confirmedComments->map(function ($comment) use ($order) {
                    return $this->transformComment($comment, $order);
                });
            });
        }
        $sortedComments = $comments->sortByDesc('created_at')->values();
        return response(['comments' => $sortedComments]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'score' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:5',
        ]);
        $order = Order::query()->find($request->order_id);
        if (!$order) {
            return response(['error' => 'order not found.']);
        }
        if ($order->seller_status !== 'delivered') {
            return response(['error' => 'you can only comment on delivered orders.']);
        }
        $existingComment = Comment::where('user_id', auth()->user()->id)
            ->where('order_id', $order->id)
            ->first();
        if ($existingComment) {
            return response(['error' => 'you have already commented on this order.']);
        }
        $restaurantId = $order->restaurant_id;

        Comment::query()->create([
            'user_id' => auth()->user()->id,
            'order_id' => $order->id,
            'restaurant_id' => $restaurantId,
            'message' => $request->message,
            'score' => $request->score,
        ]);

        return response(['message' => 'comment created successfully']);
    }

    protected function transformComment($comment, $order)
    {
        $foods = $order->foods->pluck('name')->toArray();

        return [
            'author' => [
                'name' => $comment->user->name,
            ],
            'food' => $foods,
            'created_at' => "",
            'score' => $comment->score,
            'content' => $comment->message,
        ];
    }

    protected function transformCommentByFood($comment)
    {
        return [
            'author' => [
                'name' => $comment->user->name,
            ],
            'created_at' => "",
            'score' => $comment->score,
            'content' => $comment->message,
        ];
    }

}
