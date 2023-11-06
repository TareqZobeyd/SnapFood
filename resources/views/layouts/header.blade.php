<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Online Food Delivery</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
                </li>
                @auth
                    @if(auth()->user()->hasRole('seller'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('seller.dashboard.index') }}">Dashboard</a>
                        </li>
                    @endif
                    @if(auth()->user()->hasRole('super-admin'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                    @endif
                    @if(!auth()->user()->hasRole('super-admin'))
                        <li class="nav-item">
                            <a class="nav-link" href="restaurants/create">Create Your Restaurant</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <form action="{{ route('user.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Log Out</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/user/login">Log In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/register">Register</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
</header>
