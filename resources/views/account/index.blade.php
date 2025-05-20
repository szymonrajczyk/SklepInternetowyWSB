@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Panel Zarządzania Kontem</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h4>Informacje o koncie</h4>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Imię i Nazwisko:</div>
                            <div class="col-md-8">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Adres Email:</div>
                            <div class="col-md-8">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Rola:</div>
                            <div class="col-md-8">
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'seller' ? 'warning' : 'info') }}">
                                    {{ $user->role == 'admin' ? 'Administrator' : ($user->role == 'seller' ? 'Sprzedawca' : 'Kupujący') }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Data rejestracji:</div>
                            <div class="col-md-8">{{ $user->created_at->format('j F Y, G:i') }}</div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h4>Zarządzanie kontem</h4>
                        <div class="list-group">
                            <a href="{{ route('account.change-password') }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Zmień hasło</h5>
                                    <small><i class="fas fa-key"></i></small>
                                </div>
                                <p class="mb-1">Aktualizuj swoje hasło, aby zwiększyć bezpieczeństwo konta.</p>
                            </a>
                            
                            @if($user->role == 'buyer')
                                <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Moje zamówienia</h5>
                                        <small><i class="fas fa-shopping-bag"></i></small>
                                    </div>
                                    <p class="mb-1">Przeglądaj historię swoich zamówień.</p>
                                </a>
                            @endif
                            
                            @if($user->role == 'seller')
                                <a href="{{ route('products.index', ['seller' => $user->id]) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Moje produkty</h5>
                                        <small><i class="fas fa-box"></i></small>
                                    </div>
                                    <p class="mb-1">Zarządzaj swoimi produktami.</p>
                                </a>
                            @endif
                            
                            <a href="{{ route('account.confirm-delete') }}" class="list-group-item list-group-item-action list-group-item-danger">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">Usuń konto</h5>
                                    <small><i class="fas fa-trash"></i></small>
                                </div>
                                <p class="mb-1">Trwale usuń swoje konto i wszystkie powiązane dane.</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
