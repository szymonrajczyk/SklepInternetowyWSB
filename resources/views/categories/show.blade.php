@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <h1>{{ $category->name }}</h1>
        <p class="lead">{{ $category->description }}</p>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Powrót do Kategorii</a>
    </div>
    
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card product-card h-100">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <span class="text-muted">Brak zdjęcia</span>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-truncate">{{ $product->description }}</p>
                        <p class="card-text"><strong>Cena:</strong> {{ number_format($product->price, 2) }} zł</p>
                        <p class="card-text"><strong>Stan magazynowy:</strong> {{ $product->stock }}</p>
                        <p class="card-text"><small class="text-muted">Sprzedawca: {{ $product->seller->name }}</small></p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info">Szczegóły</a>
                        
                        @if(Auth::check() && (Auth::user()->isAdmin() || (Auth::user()->isSeller() && Auth::id() == $product->seller_id)))
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edytuj</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć ten produkt?')">Usuń</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Brak produktów w tej kategorii.
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
