@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>Create Food Item</h2>
                <form action="{{ route('foods.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                        @error('name')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price">Price:</label>
                        <input type="text" class="form-control" name="price" id="price" value="{{ old('price') }}">
                        @error('price')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
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
                            @foreach($foodCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Food</button>
                </form>
            </div>
        </div>
    </div>
@endsection
