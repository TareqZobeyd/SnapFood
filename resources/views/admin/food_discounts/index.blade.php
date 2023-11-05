@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <br>
        <h2>Food Discounts</h2>
        <a href="{{ route('food_discounts.create') }}" class="btn btn-success">Create Food Discount</a>
        <table class="table">
            <thead>
            <tr>
                <th>Restaurant ID</th>
                <th>Discount Percentage</th>
                <th>Food Party</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($foodDiscounts as $foodDiscount)
                <tr>
                    <td>{{ $foodDiscount->restaurant_id }}</td>
                    <td>{{ $foodDiscount->discount_percentage }}</td>
                    <td>{{ $foodDiscount->food_party }}</td>
                    <td>
                        <a href="{{ route('food_discounts.edit', $foodDiscount) }}" class="btn btn-warning">Edit</a>
                        <form method="POST" action="{{ route('food_discounts.destroy', $foodDiscount) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
