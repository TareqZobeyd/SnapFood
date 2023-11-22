@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">Comments</h1>
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('seller.comments.filter') }}" method="get">
                    @csrf
                    <label for="food_id">Filter by Food:</label>
                    <select name="food_id" id="food_id">
                        <option value="">All</option>
                        @foreach($foods as $food)
                            <option value="{{ $food->id }}">{{ $food->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </form>
                <table class="table">
                    <thead>
                    <tr>
                        <th>User</th>
                        <th>Comment</th>
                        <th>Score</th>
                        <th>Order ID</th>
                        <th>Created At</th>
                        <th>Response</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <td>{{ $comment->user->name }}</td>
                            <td>{{ $comment->message }}</td>
                            <td>{{ $comment->score }}</td>
                            <td>{{ $comment->order_id }}</td>
                            <td>{{ $comment->created_at }}</td>
                            <td>{{ $comment->seller_response }}</td>
                            <td>
                                <form method="post" action="{{ route('comments.respond', $comment->id) }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <textarea name="seller_response" placeholder="Write a response" class="form-control"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary">Submit Response</button>
                                        </div>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
