@extends('layouts.session')
@extends('layouts.main')
@section('content')
    <div class="container-fluid">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <br>
            <h2>Delivered Orders Archive</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Customer Status</th>
                        <th>Seller Status</th>
                        <th>Total Amount</th>
                        <th>Created At</th>
                        <th>Food Details</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deliveredOrders as $order)
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
