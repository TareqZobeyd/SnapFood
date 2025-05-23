<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'اسنپ فود')</title>
    
    <!-- Bootstrap 5 RTL CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="اسنپ فود" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/"><i class="fas fa-home"></i> خانه</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/restaurants"><i class="fas fa-utensils"></i> رستوران‌ها</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categories"><i class="fas fa-list"></i> دسته‌بندی‌ها</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="/cart" class="btn btn-outline-primary me-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge bg-danger cart-count">0</span>
                    </a>
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/profile"><i class="fas fa-user-circle"></i> پروفایل</a></li>
                                <li><a class="dropdown-item" href="/orders"><i class="fas fa-clipboard-list"></i> سفارشات</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('user.logout.show') }}">
                                        <i class="fas fa-sign-out-alt"></i> خروج
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('user.show-login') }}" class="btn btn-primary">ورود</a>
                        <a href="{{ route('user.register') }}" class="btn btn-primary">ثبت نام</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4 mt-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>درباره اسنپ فود</h5>
                    <p>سفارش آنلاین غذا از بهترین رستوران‌های شهر</p>
                </div>
                <div class="col-md-4">
                    <h5>لینک‌های مفید</h5>
                    <ul class="list-unstyled">
                        <li><a href="/about" class="text-white">درباره ما</a></li>
                        <li><a href="/contact" class="text-white">تماس با ما</a></li>
                        <li><a href="/faq" class="text-white">سوالات متداول</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>شبکه‌های اجتماعی</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-telegram fa-2x"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-whatsapp fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">© {{ date('Y') }} اسنپ فود. تمامی حقوق محفوظ است.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    @stack('scripts')
</body>
</html> 