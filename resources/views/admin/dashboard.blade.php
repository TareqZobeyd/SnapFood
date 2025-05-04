@extends('layouts.app')

@section('title', 'داشبورد مدیریتی')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" 
                             class="rounded-circle me-3" width="50" height="50" alt="پروفایل">
                        <div>
                            <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">مدیر سیستم</small>
                        </div>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="/admin/dashboard">
                                <i class="fas fa-home me-2"></i> داشبورد
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/restaurants">
                                <i class="fas fa-utensils me-2"></i> رستوران‌ها
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/categories">
                                <i class="fas fa-list me-2"></i> دسته‌بندی‌ها
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/foods">
                                <i class="fas fa-hamburger me-2"></i> غذاها
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/orders">
                                <i class="fas fa-shopping-cart me-2"></i> سفارشات
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/users">
                                <i class="fas fa-users me-2"></i> کاربران
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/settings">
                                <i class="fas fa-cog me-2"></i> تنظیمات
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">سفارشات امروز</h6>
                                    <h3 class="mb-0">{{ $todayOrders }}</h3>
                                </div>
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">درآمد امروز</h6>
                                    <h3 class="mb-0">{{ number_format($todayRevenue) }} تومان</h3>
                                </div>
                                <i class="fas fa-wallet fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">رستوران‌ها</h6>
                                    <h3 class="mb-0">{{ $totalRestaurants }}</h3>
                                </div>
                                <i class="fas fa-utensils fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">کاربران</h6>
                                    <h3 class="mb-0">{{ $totalUsers }}</h3>
                                </div>
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">سفارشات اخیر</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>شماره سفارش</th>
                                    <th>مشتری</th>
                                    <th>رستوران</th>
                                    <th>مبلغ</th>
                                    <th>وضعیت</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->restaurant->name }}</td>
                                    <td>{{ number_format($order->total_price) }} تومان</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status_color }}">
                                            {{ $order->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/admin/orders/{{ $order->id }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">درآمد هفتگی</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="weeklyRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">توزیع سفارشات بر اساس رستوران</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="restaurantOrdersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Weekly Revenue Chart
    const weeklyRevenueCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
    new Chart(weeklyRevenueCtx, {
        type: 'line',
        data: {
            labels: @json($weeklyRevenue['labels']),
            datasets: [{
                label: 'درآمد (تومان)',
                data: @json($weeklyRevenue['data']),
                borderColor: '#ff6b6b',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Restaurant Orders Chart
    const restaurantOrdersCtx = document.getElementById('restaurantOrdersChart').getContext('2d');
    new Chart(restaurantOrdersCtx, {
        type: 'doughnut',
        data: {
            labels: @json($restaurantOrders['labels']),
            datasets: [{
                data: @json($restaurantOrders['data']),
                backgroundColor: [
                    '#ff6b6b',
                    '#4ecdc4',
                    '#45b7d1',
                    '#96ceb4',
                    '#ffeead'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                }
            }
        }
    });
</script>
@endpush
@endsection
