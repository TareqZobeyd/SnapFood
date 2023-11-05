@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container">
        <h1>Restaurant List</h1>

        <table class="table">
            <thead>
            <tr>
                <th>Restaurant Name</th>
                <th>Category</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Bank_account</th>
            </tr>
            </thead>
            <tbody>
            @foreach($restaurants as $restaurant)
                <tr>
                    <td>{{ $restaurant->name }}</td>
                    <td>{{ $restaurant->category_id }}</td>
                    <td>{{ $restaurant->phone }}</td>
                    <td>{{ $restaurant->address }}</td>
                    <td>{{ $restaurant->bank_account }}</td>
                    <td>
                        <a href="{{ route('admin.restaurants.edit', ['restaurant' => $restaurant->id]) }}"
                           class="btn btn-primary">Edit</a>
                        <form action="{{ route('admin.restaurants.destroy', ['restaurant' => $restaurant->id]) }}"
                              method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this restaurant?')">Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
