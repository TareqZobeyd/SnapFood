@extends('layouts.app')

@section('title', 'مدیریت سفارشات')

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
            <!-- Search and Filter -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('orders.index') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="جستجو در شماره سفارش..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-select" name="status">
                                    <option value="">همه وضعیت‌ها</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        در انتظار پرداخت
                                    </option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                                        پرداخت شده
                                    </option>
                                    <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>
                                        در حال آماده‌سازی
                                    </option>
                                    <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>
                                        آماده تحویل
                                    </option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>
                                        تحویل شده
                                    </option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        لغو شده
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-select" name="restaurant">
                                    <option value="">همه رستوران‌ها</option>
                                    @foreach($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}" 
                                                {{ request('restaurant') == $restaurant->id ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i>جستجو
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders List -->
            <div class="card shadow-sm">
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
                                    <th>تاریخ</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
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
                                    <td>{{ $order->created_at->format('Y/m/d H:i') }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('orders.show', $order->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#updateStatusModal{{ $order->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>

                                        <!-- Update Status Modal -->
                                        <div class="modal fade" id="updateStatusModal{{ $order->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">تغییر وضعیت سفارش</h5>
                                                        <button type="button" class="btn-close" 
                                                                data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('orders.update-status', $order->id) }}" 
                                                          method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="status" class="form-label">وضعیت جدید</label>
                                                                <select class="form-select" id="status" name="status" required>
                                                                    <option value="pending" 
                                                                            {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                                        در انتظار پرداخت
                                                                    </option>
                                                                    <option value="paid" 
                                                                            {{ $order->status == 'paid' ? 'selected' : '' }}>
                                                                        پرداخت شده
                                                                    </option>
                                                                    <option value="preparing" 
                                                                            {{ $order->status == 'preparing' ? 'selected' : '' }}>
                                                                        در حال آماده‌سازی
                                                                    </option>
                                                                    <option value="ready" 
                                                                            {{ $order->status == 'ready' ? 'selected' : '' }}>
                                                                        آماده تحویل
                                                                    </option>
                                                                    <option value="delivered" 
                                                                            {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                                                        تحویل شده
                                                                    </option>
                                                                    <option value="cancelled" 
                                                                            {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                                                        لغو شده
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" 
                                                                    data-bs-dismiss="modal">انصراف</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                ذخیره تغییرات
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 