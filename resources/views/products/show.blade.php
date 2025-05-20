@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produkty</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <div class="product-image-container">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                        <span class="text-muted">Brak zdjęcia</span>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <h1 class="product-title">{{ $product->name }}</h1>
            
            @if($product->category)
                <div class="mb-3">
                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                </div>
            @endif
            
            <p class="text-muted">Sprzedawca: {{ $product->seller->name }}</p>
            <p class="product-price">{{ number_format($product->price, 2) }} zł</p>
            
            <div class="product-stock mb-3">
                <span class="stock-label">Stan magazynowy:</span>
                <span class="stock-value {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $product->stock > 0 ? $product->stock . ' dostępnych' : 'Brak w magazynie' }}
                </span>
            </div>
            
            <div class="product-description mb-4">
                <h5>Opis</h5>
                <p>{{ $product->description }}</p>
            </div>
            
            @if(Auth::check() && Auth::user()->isBuyer())
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                            <button type="submit" class="btn btn-primary">Dodaj do koszyka</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning">Ten produkt jest obecnie niedostępny.</div>
                @endif
            @elseif(!Auth::check())
                <div class="alert alert-info">
                    Proszę <a href="{{ route('login') }}">zalogować się</a>, aby dodać ten produkt do koszyka.
                </div>
            @endif
            
            @if(Auth::check() && (Auth::user()->isAdmin() || (Auth::user()->isSeller() && Auth::id() == $product->seller_id)))
                <div class="mt-4">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edytuj Produkt</a>
                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć ten produkt?')">Usuń Produkt</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    
    @if($relatedProducts->count() > 0)
        <div class="related-products mt-5">
            <h3>Powiązane Produkty</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-md-3 mb-4">
                        <div class="card product-card h-100">
                            @if($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}" style="height: 150px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <span class="text-muted">Brak zdjęcia</span>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                <p class="card-text">{{ number_format($relatedProduct->price, 2) }} zł</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-sm btn-outline-primary">Zobacz Szczegóły</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    <div class="mt-5">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Powrót do Produktów</a>
    </div>
</div>
@endsection
