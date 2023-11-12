@extends('layouts.session')
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
                            <a class="nav-link" href="{{ route('foods.index') }}">
                                New Food
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.restaurant') }}">
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
                    @if ($restaurant)
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $restaurant->name }}</h5>
                                    <p class="card-text">Address: {{ $restaurant->address }}</p>
                                    <p class="card-text">Phone: {{ $restaurant->phone }}</p>
                                    <p class="card-text">Bank Account: {{ $restaurant->bank_account }}</p>

                                    <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}"
                                       class="btn btn-primary">Edit</a>

                                    <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
@endsection
