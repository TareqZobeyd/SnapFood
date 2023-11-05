@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
{{--                            <a class="nav-link" href="{{ route('admin.users') }}">--}}
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('foods.list') }}">
                                Foods
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.restaurants.index') }}">
                                Restaurants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('categories.index') }}">
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link" href="{{ route('admin.discounts') }}">--}}
                                Discounts
                            </a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link" href="{{ route('admin.comments') }}">--}}
                                Comments
                            </a>
                        </li>
                        <li class="nav-item">
{{--                            <a class="nav-link" href="{{ route('admin.banners') }}">--}}
                                Banners
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            </main>
        </div>
    </div>
@endsection
