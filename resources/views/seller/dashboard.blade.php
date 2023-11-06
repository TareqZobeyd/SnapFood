@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('seller.dashboard.index') }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link" href="{{ route('seller.orders') }}">--}}
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('foods.index') }}">
                                New Food
                            </a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link" href="{{ route('seller.restaurantprofile') }}">--}}
                                Restaurant Profile
                            </a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link" href="{{ route('seller.comments') }}">--}}
                                Comments
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('dashboard-content')
                <div class="row">
                    @foreach($restaurant->food as $food)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ $food->image_url }}" class="card-img-top" alt="Food Image">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $food->name }}</h5>
                                    <p class="card-text">Price: ${{ $food->price }}</p>
                                    <p class="card-text">Category: {{ $food->category->name }}</p>
                                    @if ($food->food_discount)
                                        <p class="card-text">Discount: {{ $food->food_discount->discount_percentage }}%</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </main>
        </div>
    </div>
@endsection
