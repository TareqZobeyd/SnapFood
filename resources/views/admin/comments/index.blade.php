@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">Comments</h1>
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.comments.filter') }}" method="get">
                    @csrf
                    <label for="food_id">Filter by Food:</label>
                    <select name="food_id" id="food_id">
                        <option value="">All</option>
                        @foreach($foods as $food)
                            <option value="{{ $food->id }}">{{ $food->name }}</option>
                        @endforeach
                    </select>

                    <label for="restaurant_id">Filter by Restaurant:</label>
                    <select name="restaurant_id" id="restaurant_id">
                        <option value="">All</option>
                        {{-- Loop through all restaurants --}}
                        @foreach($restaurants as $restaurant)
                            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </form>
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Comment</th>
                        <th>Score</th>
                        <th>Order ID</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>{{ $comment->user->name }}</td>
                            <td>{{ $comment->message }}</td>
                            <td>{{ $comment->score }}</td>
                            <td>{{ $comment->order_id }}</td>
                            <td>{{ $comment->created_at }}</td>
                            <td>
                                @if($comment->delete_request)
                                    <span class="badge badge-danger">Deletion Requested</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.comments.softDelete', $comment->id) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Soft Delete</button>
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
