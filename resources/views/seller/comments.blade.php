@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <h1 class="mt-4">Comments</h1>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
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
                            <td>{{ $comment->user->name }}</td>
                            <td>{{ $comment->message }}</td>
                            <td>{{ $comment->score }}</td>
                            <td>{{ $comment->order_id }}</td>
                            <td>{{ $comment->created_at }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
