@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">Usuń Konto</div>

                <div class="card-body">
                    <div class="alert alert-danger">
                        <h4 class="alert-heading">Uwaga!</h4>
                        <p>Usunięcie konta jest nieodwracalne. Wszystkie Twoje dane zostaną trwale usunięte.</p>
                        <hr>
                        <p class="mb-0">Jeśli jesteś pewien, że chcesz usunąć swoje konto, wprowadź swoje hasło i potwierdź.</p>
                    </div>

                    <form method="POST" action="{{ route('account.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            <div class="form-text">Wprowadź swoje aktualne hasło, aby potwierdzić usunięcie konta.</div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('account.index') }}" class="btn btn-secondary">Anuluj</a>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna.')">Usuń Moje Konto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
