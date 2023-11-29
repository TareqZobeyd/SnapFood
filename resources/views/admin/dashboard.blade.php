@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"
                               href="{{ route('admin.users') }}">
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.restaurants.index') ? 'active' : '' }}"
                               href="{{ route('admin.restaurants.index') }}">
                                Restaurants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}"
                               href="{{ route('categories.index') }}">
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('food_discounts.index') ? 'active' : '' }}"
                               href="{{ route('food_discounts.index') }}">
                                Discounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.comments') ? 'active' : '' }}"
                               href="{{ route('admin.comments') }}">
                                Comments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.banners.index') ? 'active' : '' }}"
                               href="{{ route('admin.banners.index') }}">
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
