@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Kategorie Produktów</h1>
        @if(Auth::check() && Auth::user()->isAdmin())
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Dodaj Nową Kategorię</a>
        @endif
    </div>
    
    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">{{ $category->description }}</p>
                        <p class="card-text"><span class="badge bg-info">{{ $category->products_count }} Produktów</span></p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info">Zobacz Produkty</a>
                        
                        @if(Auth::check() && Auth::user()->isAdmin())
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edytuj</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć tę kategorię?')">Usuń</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
