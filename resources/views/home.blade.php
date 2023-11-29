@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <br>
        <h1>Welcome to Our Food Delivery</h1>
        <div class="call-to-action">
            <p>You can either sell your food here or order your favorite meals.</p>
        </div>
    </div>
    <div class="container mt-4">
        <div class="row">
            @foreach($banners as $banner)
                <div class="col-md-4 mb-3">
                        <div class="card" style="width: 120%;">
                            <img src="{{ url('storage/' . $banner->image_path) }}" class="card-img-top"
                                 alt="Banner Image">
                            <div class="card-body">
                                <p class="card-text">{{ $banner->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    <main class="col-md-9 ms-sm-auto col-lg-9 px-md-3 main-container">
        <br>
        <div class="row">
            @foreach($foods as $food)
                <div class="col-md-3">
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
                                <p class="card-text">Price With Discount: {{ $food->custom_discount }}%</p>
                                <i class="fas fa-arrow-right" style="color: cornflowerblue;"></i>
                                <span style="color: green;">${{ $discountedPrice }}</span>
                            @elseif ($food->food_discount_id)
                                @php
                                    $discountedPrice = \App\Models\Food::calculateDiscountedPrice($food->price, $food->food_discount_id, null);
                                @endphp
                                <p class="card-text">Price With Food Party
                                    Discount: {{ $food->food_discount->discount_percentage }}%</p>
                                <i class="fas fa-arrow-right" style="color: cornflowerblue;"></i>
                                <span style="color: green;">${{ $discountedPrice }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

@endsection
