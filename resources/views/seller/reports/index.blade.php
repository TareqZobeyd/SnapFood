@include('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <br>
            <h2>Seller Reports</h2>
            <br>
            <p>Total Revenue: ${{ $totalRevenue }}</p>
            <form action="{{ route('seller.reports.filter') }}" method="post">
                @csrf
                <label for="seller_status">Status:</label>
                <select name="seller_status" id="seller_status">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="preparing">Preparing</option>
                    <option value="send">Send</option>
                    <option value="delivered">Delivered</option>
                </select>

                <label for="food_id">Food:</label>
                <select name="food_id" id="food_id">
                    <option value="">All</option>
                    @foreach($foods as $food)
                        <option value="{{ $food->id }}">{{ $food->name }}</option>
                    @endforeach
                </select>
                <label for="filter_last_week">Last Week:</label>
                <input type="checkbox" name="filter_last_week" id="filter_last_week">

                <label for="filter_last_month">Last Month:</label>
                <input type="checkbox" name="filter_last_month" id="filter_last_month">
                <button type="submit">Apply Filters</button>
            </form>
            <br>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Customer Status</th>
                        <th>Seller Status</th>
                        <th>Total Amount</th>
                        <th>Created At</th>
                        <th>Food Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->customer_status }}</td>
                            <td>{{ $order->seller_status }}</td>
                            <td>{{ $order->total_amount }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                @foreach ($order->foods as $food)
                                    {{ $food->name }} ({{ $food->pivot->count }}),
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection
