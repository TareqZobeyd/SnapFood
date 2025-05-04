@extends('layouts.app')

@section('title', 'افزودن دسته‌بندی جدید')

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
                            <a class="nav-link active" href="/admin/categories">
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
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0">افزودن دسته‌بندی جدید</h2>
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right me-2"></i>بازگشت
                        </a>
                    </div>

                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">نام دسته‌بندی</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-label">نوع دسته‌بندی</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">انتخاب کنید</option>
                                        <option value="restaurant" {{ old('type') == 'restaurant' ? 'selected' : '' }}>
                                            رستوران
                                        </option>
                                        <option value="food" {{ old('type') == 'food' ? 'selected' : '' }}>
                                            غذا
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">توضیحات</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="image" class="form-label">تصویر</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>ذخیره
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 