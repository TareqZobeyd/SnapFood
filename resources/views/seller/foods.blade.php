@include('layouts.session')
@extends('layouts.main')
@section('content')
<form method="POST" action="{{ route('food.store') }}">
    @csrf
    <div class="form-group">
        <label for="name">Food Name</label>
        <input type="text" name="name" class="form-control" id="name">
    </div>

    <div class="form-group">
        <label for="price">Price</label>
        <input type="text" name="price" class="form-control" id="price">
    </div>

    <div class="form-group">
        <label for="category">Category</label>
        <select name="category_id" class="form-control" id="category">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="discount">Discount</label>
        <select name="food_discount_id" class="form-control" id="discount">
            @foreach($discounts as $discount)
                <option value="{{ $discount->id }}">{{ $discount->food_party }} - {{ $discount->discount_percentage }}%</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Food</button>
</form>
@endsection
