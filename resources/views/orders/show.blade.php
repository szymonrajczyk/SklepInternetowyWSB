@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Zamówienie #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Powrót do Zamówień</a>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Informacje o Zamówieniu</div>
                <div class="card-body">
                    <p><strong>Data zamówienia:</strong> {{ $order->created_at->format('j F Y, G:i') }}</p>
                    <p>
                        <strong>Status:</strong> 
                        <span class="badge bg-{{ 
                            $order->status == 'completed' ? 'success' : 
                            ($order->status == 'processing' ? 'primary' : 
                            ($order->status == 'cancelled' ? 'danger' : 'warning')) 
                        }}">
                            {{ $order->status == 'completed' ? 'Zrealizowane' : 
                               ($order->status == 'processing' ? 'W realizacji' : 
                               ($order->status == 'cancelled' ? 'Anulowane' : 'Oczekujące')) }}
                        </span>
                    </p>
                    <p><strong>Kwota całkowita:</strong> {{ number_format($order->total_amount, 2) }} zł</p>
                    
                    @if(Auth::user()->isAdmin() || Auth::user()->isSeller())
                        <form action="{{ route('orders.update.status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Oczekujące</option>
                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>W realizacji</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Zrealizowane</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Anulowane</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Aktualizuj Status</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Informacje o Kliencie</div>
                <div class="card-body">
                    <p><strong>Imię i nazwisko:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">Produkty w Zamówieniu</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Sprzedawca</th>
                            <th>Cena</th>
                            <th>Ilość</th>
                            <th>Suma</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="{{ Auth::user()->isSeller() && $item->product && $item->product->seller_id == Auth::id() ? 'table-primary' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name ?? 'Produkt' }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            @if($item->product)
                                                <a href="{{ route('products.show', $item->product) }}">{{ $item->product->name }}</a>
                                                @if($item->product->category)
                                                    <div><small class="text-muted">{{ $item->product->category->name }}</small></div>
                                                @endif
                                            @else
                                                <span class="text-muted">Produkt nie jest już dostępny</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($item->product && $item->product->seller)
                                        {{ $item->product->seller->name }}
                                        @if(Auth::user()->isSeller() && $item->product->seller_id == Auth::id())
                                            <span class="badge bg-info">Ty</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Nieznany</span>
                                    @endif
                                </td>
                                <td>{{ number_format($item->price, 2) }} zł</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price * $item->quantity, 2) }} zł</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>Razem:</strong></td>
                            <td><strong>{{ number_format($order->total_amount, 2) }} zł</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
