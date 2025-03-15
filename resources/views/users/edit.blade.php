@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Modifier l'Utilisateur</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user->idUtilisateur) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="{{ $user->nom }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
            </div>
            <button type="submit" class="btn btn-warning">Modifier</button>
        </form>
    </div>
</div>
@endsection