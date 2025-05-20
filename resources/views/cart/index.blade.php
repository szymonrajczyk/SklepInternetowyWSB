@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Twój Koszyk</h1>
    
    @if(count($products) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produkt</th>
                        <th>Cena</th>
                        <th>Ilość</th>
                        <th>Suma</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item['product']->image)
                                        <img src="{{ asset('storage/' . $item['product']->image) }}" alt="{{ $item['product']->name }}" class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                    <a href="{{ route('products.show', $item['product']) }}">{{ $item['product']->name }}</a>
                                </div>
                            </td>
                            <td>{{ number_format($item['product']->price, 2) }} zł</td>
                            <td>
                                <form action="{{ route('cart.update', $item['product']) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group input-group-sm">
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['product']->stock }}" class="form-control" style="width: 60px;">
                                        <button type="submit" class="btn btn-outline-secondary">Aktualizuj</button>
                                    </div>
                                </form>
                            </td>
                            <td>{{ number_format($item['product']->price * $item['quantity'], 2) }} zł</td>
                            <td>
                                <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Usuń</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Razem:</strong></td>
                        <td><strong>{{ number_format($total, 2) }} zł</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning">Wyczyść Koszyk</button>
            </form>
            
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Złóż Zamówienie</button>
            </form>
        </div>
    @else
        <div class="alert alert-info">
            Twój koszyk jest pusty. <a href="{{ route('products.index') }}">Kontynuuj zakupy</a>.
        </div>
    @endif
</div>
@endsection
