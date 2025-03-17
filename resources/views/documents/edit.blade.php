@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ __('Modifier un document') }}</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('documents.update', $document->idDocument) }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $document->titre) }}" required>
                @error('titre')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type" value="{{ old('type', $document->type) }}" required>
                @error('type')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="societe" class="form-label">Société</label>
                <input type="text" class="form-control @error('societe') is-invalid @enderror" id="societe" name="societe" value="{{ old('societe', $document->societe) }}">
                @error('societe')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="direction" class="form-label">Direction</label>
                <input type="text" class="form-control @error('direction') is-invalid @enderror" id="direction" name="direction" value="{{ old('direction', $document->direction) }}">
                @error('direction')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="service" class="form-label">Service</label>
                <input type="text" class="form-control @error('service') is-invalid @enderror" id="service" name="service" value="{{ old('service', $document->service) }}">
                @error('service')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="statut" class="form-label">Statut</label>
                <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                    <option value="disponible" {{ old('statut', $document->statut) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                    <option value="emprunté" {{ old('statut', $document->statut) == 'emprunté' ? 'selected' : '' }}>Emprunté</option>
                    <option value="archivé" {{ old('statut', $document->statut) == 'archivé' ? 'selected' : '' }}>Archivé</option>
                </select>
                @error('statut')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('documents.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>
@endsection