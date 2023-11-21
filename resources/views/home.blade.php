@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <br>
        <h1>Welcome to Food Delivery</h1>
        <div class="call-to-action">
            <p>You can either sell your food here or order your favorite meals.</p>
            <p>You have to log in or register first</p>
        </div>
    </div>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <br>
        <div class="row">
            @foreach($foods as $food)
                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ $food->image_url }}" class="card-img-top" alt="Food Image">
                        <div class="card-body">
                            <h5 class="card-title">{{ $food->name }}</h5>
                            <p class="card-text">Price: ${{ $food->price }}</p>
                            <p class="card-text">Category: {{ $food->categories->name }}</p>
                            <p class="card-text">Restaurant: {{ $food->restaurant->name }}</p>

                        @if ($food->custom_discount)
                                @php
                                    $discountedPrice = \App\Models\Food::calculateDiscountedPrice($food->price, null, $food->custom_discount);
                                @endphp
                                <p class="card-text">Price With Custom Discount: {{ $food->custom_discount }}%</p>
                                <i class="fas fa-arrow-right" style="color: blue;"></i>
                                <span style="color: green;">${{ $discountedPrice }}</span>
                            @elseif ($food->food_discount_id)
                                @php
                                    $discountedPrice = \App\Models\Food::calculateDiscountedPrice($food->price, $food->food_discount_id, null);
                                @endphp
                                <p class="card-text">Price With Food Discount: {{ $food->food_discount->discount_percentage }}%</p>
                                <i class="fas fa-arrow-right" style="color: green;"></i>
                                <span style="color: green;">${{ $discountedPrice }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

@endsection
