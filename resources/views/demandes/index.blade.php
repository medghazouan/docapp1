@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Demandes de documents') }}</h5>
        <a href="{{ route('demandes.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nouvelle demande
        </a>
    </div>

    <div class="card-body">
        @if($demandes->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Document</th>
                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'responsable' || Auth::user()->role == 'archiviste')
                                <th>Demandeur</th>
                            @endif
                            <th>Date de soumission</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes as $demande)
                            <tr>
                                <td>{{ $demande->idDemande }}</td>
                                <td>{{ $demande->document->titre }}</td>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'responsable' || Auth::user()->role == 'archiviste')
                                    <td>{{ $demande->utilisateur->nom }}</td>
                                @endif
                                <td>{{ (new DateTime($demande->dateSoumission))->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge status-{{ $demande->statut }}">
                                        @switch($demande->statut)
                                            @case('en_attente')
                                                En attente
                                                @break
                                            @case('approuvé_responsable')
                                                Approuvé par responsable
                                                @break
                                            @case('refusé_responsable')
                                                Refusé par responsable
                                                @break
                                            @case('approuvé_archiviste')
                                                Approuvé par archiviste
                                                @break
                                            @case('refusé_archiviste')
                                                Refusé par archiviste
                                                @break
                                            @case('récupéré')
                                                Récupéré
                                                @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('demandes.show', $demande->idDemande) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Voir
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $demandes->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucune demande de document trouvée.
            </div>
        @endif
    </div>
</div>
@endsection