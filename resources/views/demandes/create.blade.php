@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Nouvelle demande de document') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('demandes.store') }}">
            @csrf
            <div class="mb-3">
                <label for="idDocument" class="form-label">{{ __('Document') }}</label>
                <select class="form-select" id="idDocument" name="idDocument" required>
                    <option value="">{{ __('Sélectionner un document') }}</option>
                    @foreach($documents as $document)
                        <option value="{{ $document->idDocument }}">{{ $document->titre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Soumettre la demande') }}</button>
            <a href="{{ route('demandes.index') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
        </form>
    </div>
</div>
@endsection