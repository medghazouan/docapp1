@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Créer un Document</div>
    <div class="card-body">
        <form method="POST" action="{{ route('documents.store') }}">
            @csrf
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" name="titre" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control" name="type" required>
            </div>
            <button type="submit" class="btn btn-success">Créer</button>
        </form>
    </div>
</div>
@endsection