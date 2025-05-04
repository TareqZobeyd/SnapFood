@include('layouts.session')
@extends('layouts.app')

@section('title', 'اسنپ فود - سفارش آنلاین غذا')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h1 class="display-4 fw-bold mb-4">سفارش آنلاین غذا از بهترین رستوران‌های شهر</h1>
                <p class="lead mb-4">با اسنپ فود، غذای مورد علاقه‌تان را در کمترین زمان و با بهترین کیفیت دریافت کنید.</p>
                <div class="d-flex gap-3">
                    <a href="/restaurants" class="btn btn-primary btn-lg">مشاهده رستوران‌ها</a>
                    <a href="/categories" class="btn btn-outline-primary btn-lg">دسته‌بندی‌ها</a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="{{ asset('images/hero-image.jpg') }}" alt="سفارش غذا" class="img-fluid rounded-3 shadow">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">چرا اسنپ فود؟</h2>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-bolt fa-3x text-primary mb-3"></i>
                        <h3 class="h4">تحویل سریع</h3>
                        <p class="text-muted">تحویل غذا در کمترین زمان ممکن</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-star fa-3x text-primary mb-3"></i>
                        <h3 class="h4">کیفیت عالی</h3>
                        <p class="text-muted">بهترین رستوران‌های شهر در یکجا</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h3 class="h4">پرداخت امن</h3>
                        <p class="text-muted">سیستم پرداخت امن و مطمئن</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Restaurants -->
<section class="restaurants-section py-5">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">رستوران‌های محبوب</h2>
        <div class="row g-4">
            @foreach($popularRestaurants as $restaurant)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100">
                    <img src="{{ $restaurant->image }}" class="card-img-top" alt="{{ $restaurant->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text text-muted">{{ $restaurant->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">{{ $restaurant->category->name }}</span>
                            <a href="/restaurants/{{ $restaurant->id }}" class="btn btn-outline-primary">مشاهده منو</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="categories-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5" data-aos="fade-up">دسته‌بندی‌ها</h2>
        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100">
                    <img src="{{ $category->image }}" class="card-img-top" alt="{{ $category->name }}">
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <a href="/categories/{{ $category->id }}" class="btn btn-outline-primary">مشاهده</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Download App Section -->
<section class="download-app-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <h2 class="mb-4">دانلود اپلیکیشن اسنپ فود</h2>
                <p class="lead mb-4">با اپلیکیشن اسنپ فود، سفارش غذا را سریع‌تر و راحت‌تر تجربه کنید.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-dark">
                        <i class="fab fa-google-play"></i> Google Play
                    </a>
                    <a href="#" class="btn btn-dark">
                        <i class="fab fa-apple"></i> App Store
                    </a>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <img src="{{ asset('images/app-preview.png') }}" alt="اپلیکیشن اسنپ فود" class="img-fluid">
            </div>
        </div>
    </div>
</section>
@endsection
