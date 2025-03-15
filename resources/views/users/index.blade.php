@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Liste des Utilisateurs</div>
    <div class="card-body">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Ajouter un Utilisateur</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->idUtilisateur }}</td>
                        <td>{{ $user->nom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('users.show', $user->idUtilisateur) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('users.edit', $user->idUtilisateur) }}" class="btn btn-warning btn-sm">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection