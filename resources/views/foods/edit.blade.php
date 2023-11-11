@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <h2>Edit Food</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('foods.update', $food->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $food->name }}">
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" class="form-control" name="price" id="price" value="{{ $food->price }}">
            </div>
            <div class="form-group">
                <label for="discount">Discount</label>
                <select name="food_discount_id" class="form-control" id="discount">
                    <option value="">No Discount</option>
                    @foreach($discounts as $discount)
                        <option value="{{ $discount->id }}">{{ $discount->food_party }}
                            - {{ $discount->discount_percentage }}%
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="category_id">Category:</label>
                <select class="form-control" name="category_id" id="category_id">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $food->category_id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Food</button>
        </form>
    </div>
@endsection
