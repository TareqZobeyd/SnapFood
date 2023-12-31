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
        <form action="{{ route('food.update', $food->id) }}" method="POST" enctype="multipart/form-data">
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
                <label for="custom_discount">Custom Discount:</label>
                <input type="text" class="form-control" name="custom_discount" id="custom_discount"
                       value="{{ $food->custom_discount }}">
            </div>
            <div class="form-group">
                <label for="food_discount_id">Food Discount:</label>
                <select name="food_discount_id" class="form-control" id="food_discount_id">
                    <option value="">No Discount</option>
                    @foreach($discounts as $discount)
                        <option
                            value="{{ $discount->id }}" {{ $discount->id == $food->food_discount_id ? 'selected' : '' }}>
                            {{ $discount->food_party }} - {{ $discount->discount_percentage }}%
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
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" name="image" id="image">
                @error('image')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Food</button>
        </form>
    </div>
@endsection
