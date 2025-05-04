@extends('layouts.app')

@section('title', 'جزئیات سفارش')

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
                            <a class="nav-link" href="/admin/dashboard">
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
                            <a class="nav-link active" href="/admin/orders">
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
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">جزئیات سفارش #{{ $order->id }}</h2>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right me-2"></i>بازگشت
                        </a>
                    </div>

                    <div class="row">
                        <!-- Order Details -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">اطلاعات سفارش</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">تاریخ سفارش</small>
                                            <p class="mb-0">{{ $order->created_at->format('Y/m/d H:i') }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">وضعیت</small>
                                            <p class="mb-0">
                                                <span class="badge bg-{{ $order->status_color }}">
                                                    {{ $order->status_text }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">مشتری</small>
                                            <p class="mb-0">{{ $order->user->name }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">شماره تماس</small>
                                            <p class="mb-0">{{ $order->user->phone }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <small class="text-muted">آدرس تحویل</small>
                                            <p class="mb-0">{{ $order->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Restaurant Details -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">اطلاعات رستوران</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <small class="text-muted">نام رستوران</small>
                                            <p class="mb-0">{{ $order->restaurant->name }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">شماره تماس</small>
                                            <p class="mb-0">{{ $order->restaurant->phone }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <small class="text-muted">آدرس رستوران</small>
                                            <p class="mb-0">{{ $order->restaurant->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">محصولات سفارش</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>نام محصول</th>
                                                    <th>تعداد</th>
                                                    <th>قیمت واحد</th>
                                                    <th>قیمت کل</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->items as $item)
                                                <tr>
                                                    <td>{{ $item->food->name }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ number_format($item->price) }} تومان</td>
                                                    <td>{{ number_format($item->price * $item->quantity) }} تومان</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-end">جمع کل:</td>
                                                    <td>{{ number_format($order->total_price) }} تومان</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 