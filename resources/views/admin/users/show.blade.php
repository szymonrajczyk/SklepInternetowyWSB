@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Szczegóły Użytkownika</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Powrót do Użytkowników</a>
    </div>
    
    <div class="card">
        <div class="card-header">
            Informacje o Użytkowniku
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">ID:</div>
                <div class="col-md-9">{{ $user->id }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Imię i Nazwisko:</div>
                <div class="col-md-9">{{ $user->name }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Email:</div>
                <div class="col-md-9">{{ $user->email }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Rola:</div>
                <div class="col-md-9">
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'seller' ? 'warning' : 'info') }}">
                        {{ $user->role == 'admin' ? 'Administrator' : ($user->role == 'seller' ? 'Sprzedawca' : 'Kupujący') }}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Data rejestracji:</div>
                <div class="col-md-9">{{ $user->created_at->format('j F Y, G:i') }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 fw-bold">Email zweryfikowany:</div>
                <div class="col-md-9">
                    @if($user->email_verified_at)
                        <span class="text-success">Tak - {{ $user->email_verified_at->format('j F Y, G:i') }}</span>
                    @else
                        <span class="text-danger">Nie</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edytuj Użytkownika</a>
                @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć tego użytkownika?')">Usuń Użytkownika</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    
    @if($user->isSeller())
        <div class="mt-4">
            <h2>Produkty tego Sprzedawcy</h2>
            @if($user->products->count() > 0)
                <div class="row">
                    @foreach($user->products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <span class="text-muted">Brak zdjęcia</span>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ number_format($product->price, 2) }} zł</p>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">Podgląd</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">Ten sprzedawca nie ma jeszcze żadnych produktów.</div>
            @endif
        </div>
    @endif
    
    <div class="mt-4">
        <h2>Zamówienia</h2>
        @if($user->orders->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Zamówienie #</th>
                            <th>Data</th>
                            <th>Suma</th>
                            <th>Status</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ number_format($order->total_amount, 2) }} zł</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->status == 'completed' ? 'success' : 
                                        ($order->status == 'processing' ? 'primary' : 
                                        ($order->status == 'cancelled' ? 'danger' : 'warning')) 
                                    }}">
                                        {{ $order->status == 'completed' ? 'Zrealizowane' : 
                                           ($order->status == 'processing' ? 'W realizacji' : 
                                           ($order->status == 'cancelled' ? 'Anulowane' : 'Oczekujące')) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Podgląd</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">Ten użytkownik nie ma jeszcze żadnych zamówień.</div>
        @endif
    </div>
</div>
@endsection
