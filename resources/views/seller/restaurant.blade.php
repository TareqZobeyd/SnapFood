@extends('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <div class="row">
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
                                    <p class="card-text">Is Open: {{ $restaurant->is_open ? 'Yes' : 'No' }}</p>
                                    <p class="card-text">Delivery Cost: {{ $restaurant->delivery_cost }}</p>
                                    <p class="card-text">Working Hours: {{ $restaurant->working_hours }}</p>

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
