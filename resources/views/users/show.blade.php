@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Détails de l'Utilisateur</div>
    <div class="card-body">
        <p><strong>ID:</strong> {{ $user->idUtilisateur }}</p>
        <p><strong>Nom:</strong> {{ $user->nom }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Rôle:</strong> {{ ucfirst($user->role) }}</p>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Retour</a>
    </div>
</div>
@endsection