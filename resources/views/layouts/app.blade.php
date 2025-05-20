<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Przełącz nawigację">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index') }}">Produkty</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('categories.index') }}">Kategorie</a>
                        </li>
                        @auth
                            @if(Auth::user()->isSeller() || Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('products.create') }}">Dodaj Produkt</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cart.index') }}">
                                    Koszyk
                                    @if(Session::has('cart') && count(Session::get('cart')) > 0)
                                        <span class="badge bg-primary">{{ count(Session::get('cart')) }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('orders.index') }}">Zamówienia</a>
                            </li>
                            @if(Auth::user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.users.index') }}">Zarządzaj Użytkownikami</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Logowanie</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Rejestracja</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                    <span class="badge bg-{{ Auth::user()->role == 'admin' ? 'danger' : (Auth::user()->role == 'seller' ? 'warning' : 'info') }}">
                                        {{ Auth::user()->role == 'admin' ? 'Administrator' : (Auth::user()->role == 'seller' ? 'Sprzedawca' : 'Kupujący') }}
                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('account.index') }}">
                                        <i class="fas fa-user-cog fa-fw"></i> Moje Konto
                                    </a>
                                    <a class="dropdown-item" href="{{ route('account.change-password') }}">
                                        <i class="fas fa-key fa-fw"></i> Zmień Hasło
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt fa-fw"></i> Wyloguj
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        
        <footer class="footer mt-auto py-3 bg-dark text-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h5>{{ config('app.name', 'Laravel') }}</h5>
                        <p class="text-light">Twój sklep internetowy z wszystkim, czego potrzebujesz.</p>
                    </div>
                    <div class="col-md-4">
                        <h5>Szybkie Linki</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('products.index') }}">Produkty</a></li>
                            <li><a href="{{ route('categories.index') }}">Kategorie</a></li>
                            @auth
                                <li><a href="{{ route('cart.index') }}">Koszyk</a></li>
                                <li><a href="{{ route('orders.index') }}">Zamówienia</a></li>
                                <li><a href="{{ route('account.index') }}">Moje Konto</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Logowanie</a></li>
                                <li><a href="{{ route('register') }}">Rejestracja</a></li>
                            @endauth
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Kontakt</h5>
                        <address>
                            <strong>{{ config('app.name', 'Laravel') }}</strong><br>
                            Szymon Rajczyk<br>
                            Hubert Bałuszyński<br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <p class="text-light">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}.</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
