@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <br>
        <h1>Admin Users</h1>
        <br>
        <a href="{{ route('user.register') }}" class="btn btn-success">Create User</a>
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
