<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentIndexRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{
    public function index(CommentIndexRequest $request)
    {
        $validated = $request->validated();

        if (!is_null($validated['food_id'])) {
            $comments = Comment::query()
                ->join('food_order', 'comments.order_id', '=', 'food_order.order_id')
                ->where('food_order.food_id', $validated['food_id'])
                ->where('comments.confirmed', true)
                ->orderBy('comments.created_at', 'desc')
                ->get();

            $transformedComments = $comments->map(function ($comment) {
                return $this->transformCommentByFood($comment);
            });

            return response()->json(['comments' => $transformedComments]);
        }

        if (!is_null($validated['restaurant_id'])) {
            $orders = Order::query()
                ->where('restaurant_id', $validated['restaurant_id'])
                ->with(['comments', 'foods'])
                ->get();

            $comments = $orders->flatMap(function ($order) {
                return $order->comments->where('confirmed', true)->map(function ($comment) use ($order) {
                    return $this->transformComment($comment, $order);
                });
            });
        }

        $sortedComments = $comments->sortByDesc('created_at')->values();
        return response()->json(['comments' => $sortedComments]);
    }

    public function store(StoreCommentRequest $request)
    {
        $validated = $request->validated();

        $order = Order::query()->find($validated['order_id']);
        if (!$order) {
            return Response::json(['error' => 'order not found.'], 404);
        }

        if ($order->seller_status !== 'delivered') {
            return Response::json(['error' => 'you can only comment on delivered orders.'], 403);
        }

        $existingComment = Comment::where('user_id', auth()->user()->id)
            ->where('order_id', $order->id)
            ->first();

        if ($existingComment) {
            return Response::json(['error' => 'you have already commented on this order.'], 409);
        }

        $restaurantId = $order->restaurant_id;

        Comment::query()->create([
            'user_id' => auth()->user()->id,
            'order_id' => $order->id,
            'restaurant_id' => $restaurantId,
            'message' => $validated['message'],
            'score' => $validated['score'],
        ]);

        return Response::json(['message' => 'comment created successfully'], 201);
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
