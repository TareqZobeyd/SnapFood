@extends('layouts.app')

@section('title', 'خروج از حساب کاربری')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-sign-out-alt fa-4x text-primary"></i>
                    </div>
                    
                    <h2 class="mb-4">آیا از خروج از حساب کاربری خود اطمینان دارید؟</h2>
                    
                    <p class="text-muted mb-4">
                        با خروج از حساب کاربری، دسترسی شما به امکانات شخصی‌سازی شده محدود خواهد شد.
                    </p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <form action="{{ route('user.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                بله، خروج
                            </button>
                        </form>
                        
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            انصراف
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 