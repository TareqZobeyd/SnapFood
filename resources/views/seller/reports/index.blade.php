
@extends('layouts.app')

@section('content')
    <h2>Seller Reports</h2>

    <p>Total Revenue: ${{ $totalRevenue }}</p>

    <!-- Filter Form -->
    <form action="{{ route('seller.reports.filter') }}" method="post">
        @csrf
        <!-- Add filter inputs here (e.g., status dropdown, food dropdown) -->
        <button type="submit">Apply Filters</button>
    </form>

    <!-- Orders Table -->
    <table>
        <!-- Display orders in a table -->
    </table>
@endsection
