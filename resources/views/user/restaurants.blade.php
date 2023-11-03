@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <br>
        <h2>Restaurants</h2>
        <br>
        <div class="row">
            @foreach($restaurants as $restaurant)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="{{ $restaurant->image_url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $restaurant->name }}</h5>
                            <p class="card-text">{{ $restaurant->description }}</p>

                            <!-- Restaurant details -->
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Address: {{ $restaurant->address }}</li>
                                <li class="list-group-item">Phone: {{ $restaurant->phone }}</li>
                                <li class="list-group-item">Type: {{ $restaurant->category->name }}</li>
                            </ul>

                            <a href="{{ route('user.restaurants', ['restaurant' => $restaurant->id]) }}" class="btn btn-primary">View Details</a>
                            <a class="btn btn-success">Order Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
