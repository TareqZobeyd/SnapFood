@extends('layouts.main')
@section('content')
    <h1>Cart IDs</h1>

    <ul>
        @foreach($cartIds as $cartId)
            <li>{{ $cartId }}</li>
        @endforeach
    </ul>
@endsection
