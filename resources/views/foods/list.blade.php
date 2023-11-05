@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container mt-5">
        <h2>Food List</h2>
        <div class="mb-3">
            <a href="{{ route('foods.index') }}" class="btn btn-success">Create Food</a>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($foods as $food)
                <tr>
                    <td>{{ $food->name }}</td>
                    <td>{{ $food->price }}</td>
                    <td>{{ $food->category->name }}</td>
                    <td>
                        <a href="{{ route('foods.edit', $food->id) }}" class="btn btn-primary">Edit</a>
                    </td>
                    <td>
                        <form action="{{ route('foods.destroy', $food->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this food?')">Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
