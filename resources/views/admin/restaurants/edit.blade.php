@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <h1>Edit Restaurant</h1>

        <form action="{{ route('admin.restaurants.update', ['restaurant' => $restaurant->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Restaurant Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $restaurant->name }}">
            </div>

            <div class="form-group">
                <label for="category_id">Restaurant Category</label>
                <select name="category_id" id="category_id" class="form-control">
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->id }}" {{ $category->id == $restaurant->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ $restaurant->phone }}">
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" class="form-control" value="{{ $restaurant->address }}">
            </div>

            <div class="form-group">
                <label for="bank_account">Bank Account</label>
                <input type="text" name="bank_account" id="bank_account" class="form-control"
                       value="{{ $restaurant->bank_account }}">
            </div>

            <button type="submit" class="btn btn-primary">Update Restaurant</button>
        </form>
    </div>
@endsection
