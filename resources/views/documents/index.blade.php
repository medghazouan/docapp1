@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Liste des Documents</div>
    <div class="card-body">
        <a href="{{ route('documents.create') }}" class="btn btn-primary">Ajouter un Document</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->idDocument }}</td>
                        <td>{{ $document->titre }}</td>
                        <td>{{ $document->type }}</td>
                        <td>{{ $document->statut }}</td>
                        <td>
                            <a href="{{ route('documents.show', $document->idDocument) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('documents.edit', $document->idDocument) }}" class="btn btn-warning btn-sm">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection