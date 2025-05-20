@extends('layouts.app')

@section('content')
<div class="container">
    <h1>
        @if(Auth::user()->isAdmin())
            Wszystkie Zamówienia
        @elseif(Auth::user()->isSeller())
            Zamówienia z Twoimi Produktami
        @else
            Twoje Zamówienia
        @endif
    </h1>
    
    @if ($orders->isEmpty())
        <div class="alert alert-info">
            @if(Auth::user()->isBuyer())
                Nie masz jeszcze żadnych zamówień. <a href="{{ route('products.index') }}">Kontynuuj zakupy</a>.
            @else
                Nie znaleziono zamówień.
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Zamówienie #</th>
                                <th>Data</th>
                                @if(Auth::user()->isAdmin() || Auth::user()->isSeller())
                                    <th>Klient</th>
                                @endif
                                <th>Suma</th>
                                <th>Status</th>
                                <th>Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    @if(Auth::user()->isAdmin() || Auth::user()->isSeller())
                                        <td>{{ $order->user->name }}</td>
                                    @endif
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
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">Szczegóły</a>
                                        
                                        @if(Auth::user()->isAdmin() || Auth::user()->isSeller())
                                            <form action="{{ route('orders.update.status', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Oczekujące</option>
                                                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>W realizacji</option>
                                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Zrealizowane</option>
                                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Anulowane</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Aktualizuj</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
