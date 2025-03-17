@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Détails du document') }}</h5>
        <div>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'archiviste')
            <a href="{{ route('documents.edit', $document->idDocument) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Modifier
            </a>
            @endif
            @if($document->statut == 'disponible')
            <a href="{{ route('demandes.create', ['document' => $document->idDocument]) }}" class="btn btn-success btn-sm">
                <i class="fas fa-file"></i> Demander
            </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $document->idDocument }}</td>
                    </tr>
                    <tr>
                        <th>Titre</th>
                        <td>{{ $document->titre }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $document->type }}</td>
                    </tr>
                    <tr>
                        <th>Société</th>
                        <td>{{ $document->societe ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Direction</th>
                        <td>{{ $document->direction ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Service</th>
                        <td>{{ $document->service ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td>
                            <span class="badge bg-{{ $document->statut == 'disponible' ? 'success' : ($document->statut == 'emprunté' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($document->statut) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Date de création</th>
                        <td>{{ $document->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Dernière mise à jour</th>
                        <td>{{ $document->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Historique des demandes</h6>
                    </div>
                    <div class="card-body">
                        @if($document->demandes->count() > 0)
                        <ul class="list-group">
                            @foreach($document->demandes as $demande)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">Demande #{{ $demande->idDemande }}</span> -
                                     {{ $demande->dateSoumission->format('d/m/Y') }}
                                </div>
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
                            </li>
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