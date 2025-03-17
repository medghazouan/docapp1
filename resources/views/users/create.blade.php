@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Créer un Utilisateur</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
                        <div class="mb-3">
                            <label for="nom" class="form-label">Name</label>
                            <input type="text" name="nom" id="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                        </div>
                         <div class="mb-3">
                            <label for="Fonction" class="form-label">Fonction</label>
                            <input type="text" name="fonction" id="fonction" class="form-control" >
                        </div>
                         <div class="mb-3">
                            <label for="Societe" class="form-label">Societe</label>
                            <input type="text" name="societe" id="societe" class="form-control" >
                        </div>
                         <div class="mb-3">
                            <label for="Direction" class="form-label">Direction</label>
                            <input type="text" name="direction" id="direction" class="form-control" >
                        </div>
                         <div class="mb-3">
                            <label for="Service" class="form-label">Service</label>
                            <input type="text" name="service" id="service" class="form-control" >
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="Utilisateur">Utilisateur</option>
                                <option value="Responsable">Responsable</option>
                                <option value="Archiviste">Archiviste</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success">Créer</button>
        </form>
    </div>
</div>
@endsection