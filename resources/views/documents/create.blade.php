@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Ajouter un document') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('documents.store') }}">
            @csrf
            
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" required>
                @error('titre')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type') }}" required>
                @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="societe" class="form-label">Société</label>
                <input type="text" class="form-control @error('societe') is-invalid @enderror" id="societe" name="societe" value="{{ old('societe') }}">
                @error('societe')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="direction" class="form-label">Direction</label>
                <input type="text" class="form-control @error('direction') is-invalid @enderror" id="direction" name="direction" value="{{ old('direction') }}">
                @error('direction')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="service" class="form-label">Service</label>
                <input type="text" class="form-control @error('service') is-invalid @enderror" id="service" name="service" value="{{ old('service') }}">
                @error('service')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="mb-3">
                <label for="duree_max_retour" class="form-label">Durée maximale de retour (jours)</label>
                <input type="number" class="form-control @error('duree_max_retour') is-invalid @enderror" id="duree_max_retour" name="duree_max_retour" value="{{ old('duree_max_retour') }}" required min="1">
                @error('duree_max_retour')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection