@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Produkty</h1>
        @if(Auth::check() && (Auth::user()->isSeller() || Auth::user()->isAdmin()))
            <a href="{{ route('products.create') }}" class="btn btn-primary">Dodaj Nowy Produkt</a>
        @endif
    </div>
    
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Szukaj produktów..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-primary">Szukaj</button>
            </form>
        </div>
        <div class="col-md-4">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ request('category') ? $categories->firstWhere('id', request('category'))->name : 'Filtruj według kategorii' }}
                </button>
                <ul class="dropdown-menu w-100" aria-labelledby="categoryDropdown">
                    <li><a class="dropdown-item" href="{{ route('products.index') }}">Wszystkie Kategorie</a></li>
                    @foreach($categories as $category)
                        <li><a class="dropdown-item" href="{{ route('products.index', ['category' => $category->id]) }}">{{ $category->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
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
                        @if($product->category)
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </div>
                        @endif
                        <p class="card-text text-truncate">{{ $product->description }}</p>
                        <p class="card-text"><strong>Cena:</strong> {{ number_format($product->price, 2) }} zł</p>
                        <p class="card-text">
                            <strong>Stan magazynowy:</strong> 
                            <span class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $product->stock > 0 ? $product->stock . ' dostępnych' : 'Brak w magazynie' }}
                            </span>
                        </p>
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
                    Nie znaleziono produktów. 
                    @if(request('search') || request('category'))
                        <a href="{{ route('products.index') }}" class="alert-link">Wyczyść filtry</a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>
    <div class="d-flex flex-column align-items-center mt-4">
    <div class="small text-muted mb-2 text-center">
        Wyświetlanie od <span class="fw-semibold">{{ $products->firstItem() }}</span>
        do <span class="fw-semibold">{{ $products->lastItem() }}</span>
        z <span class="fw-semibold">{{ $products->total() }}</span> wyników
    </div>

    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
</div>
</div>
</div>
@endsection
