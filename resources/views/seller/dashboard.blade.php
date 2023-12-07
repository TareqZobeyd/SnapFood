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
                            <a class="nav-link" href="{{ route('seller.orders') }}">
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('food.index') }}">
                                New Food
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.restaurant') }}">
                                Restaurant Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.comments') }}">
                                Comments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.archive') }}">
                                Archive
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.reports.index') }}">
                                Reports
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('dashboard-content')
                <br>
                <div class="row">
                    @if ($restaurant->food)
                        @foreach($restaurant->food as $food)
                            <div class="col-md-4">
                                <div class="card">
                                    <img src="{{ url('storage/' . $food->image_path) }}" class="card-img-top"
                                         alt="Food Image"
                                         style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $food->name }}</h5>
                                        <p class="card-text">Price: ${{ $food->price }}</p>
                                        <p class="card-text">Category: {{ $food->categories->name }}</p>

                                        @if ($food->custom_discount)
                                            @php
                                                $discountedPrice = \App\Models\Food::calculateDiscountedPrice($food->price, null, $food->custom_discount);
                                            @endphp
                                            <p class="card-text">Price With Custom
                                                Discount: {{ $food->custom_discount }}%</p>
                                            <i class="fas fa-arrow-right" style="color: cornflowerblue;"></i>
                                            <span style="color: green;">${{ $discountedPrice }}</span>
                                        @elseif ($food->food_discount_id)
                                            @php
                                                $discountedPrice = \App\Models\Food::calculateDiscountedPrice($food->price, $food->food_discount_id, null);
                                            @endphp
                                            <p class="card-text">Price With Food
                                                Discount: {{ $food->food_discount->discount_percentage }}%</p>
                                            <i class="fas fa-arrow-right" style="color: cornflowerblue;"></i>
                                            <span style="color: green;">${{ $discountedPrice }}</span>
                                        @endif

                                        <div class="mt-3">
                                            <a href="{{ route('food.edit', $food->id) }}"
                                               class="btn btn-primary">Edit</a>
                                            <form action="{{ route('food.destroy', $food->id) }}" method="post"
                                                  style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Are you sure?')">Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif
                </div>
            </main>
        </div>
    </div>
@endsection
