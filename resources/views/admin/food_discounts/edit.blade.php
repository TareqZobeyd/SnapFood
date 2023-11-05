@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <h2>Edit Food Discount</h2>
        <form method="POST" action="{{ route('food_discounts.update', $foodDiscount) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="restaurant_id">Restaurant ID (optional):</label>
                <input type="text" class="form-control" id="restaurant_id" name="restaurant_id"
                       value="{{ $foodDiscount->restaurant_id }}">
            </div>
            <div class="form-group">
                <label for="discount_percentage">Discount Percentage:</label>
                <input type="text" class="form-control" id="discount_percentage" name="discount_percentage"
                       value="{{ $foodDiscount->discount_percentage }}">
            </div>

            <div class="form-group">
                <label for="food_party">Food Party (optional):</label>
                <input type="text" class="form-control" id="food_party" name="food_party"
                       value="{{ $foodDiscount->food_party }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Food Discount</button>
        </form>
    </div>
@endsection
