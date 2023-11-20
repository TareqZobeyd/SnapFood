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
                    @foreach($foods as $food)
                        <option value="{{ $food->id }}">{{ $food->name }}</option>
                    @endforeach
                </select>

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
                            <td>
                                <form action="{{ route('orders.update-seller-status', ['id' => $order->id]) }}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group">
                                        <select name="seller_status" class="form-control"
                                                onchange="this.form.submit()">
                                            <option
                                                value="pending" {{ $order->seller_status === 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option
                                                value="preparing" {{ $order->seller_status === 'preparing' ? 'selected' : '' }}>
                                                Preparing
                                            </option>
                                            <option
                                                value="send" {{ $order->seller_status === 'send' ? 'selected' : '' }}>
                                                Send
                                            </option>
                                            <option
                                                value="delivered" {{ $order->seller_status === 'delivered' ? 'selected' : '' }}>
                                                Delivered
                                            </option>
                                        </select>
                                    </div>
                                </form>
                            </td>
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
            <table>
                <!-- Display filteredOrders in a table -->
            </table>
        </main>
    </div>
@endsection
