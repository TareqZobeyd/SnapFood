@include('layouts.session')
@extends('layouts.main')
@section('content')

        <h2>Seller Reports</h2>

        <p>Total Revenue: ${{ $totalRevenue }}</p>

        <!-- Filter Form -->
        <form action="{{ route('seller.reports.filter') }}" method="post">
            @csrf
            <label for="status">Status:</label>
            <select name="status" id="status">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="preparing">Preparing</option>
                <option value="send">Send</option>
                <option value="delivered">Delivered</option>
            </select>

            <label for="food_id">Food:</label>
            <select name="food_id" id="food_id">
                <option value="">All</option>
                <!-- Loop through your foods and populate the dropdown -->
                @foreach($foods as $food)
                    <option value="{{ $food->id }}">{{ $food->name }}</option>
                @endforeach
            </select>

            <button type="submit">Apply Filters</button>
        </form>

        <!-- Orders Table -->
        <table>
            <!-- Display filteredOrders in a table -->
        </table>
    @endsection
