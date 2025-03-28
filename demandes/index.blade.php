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
                <table class="table table-striped table-hover">
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
                                <td class="actions-column">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('demandes.show', $demande->idDemande) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        @if (Auth::user()->role == 'responsable' && $demande->statut == 'en_attente')
                                        <form action="{{ route('demandes.approve.responsable', $demande->idDemande) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approuver
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $demande->idDemande }}">
                                            <i class="fas fa-times"></i> Refuser
                                        </button>
                                        
                                        <!-- Rejection Modal -->
                                        <div class="modal fade" id="rejectModal{{ $demande->idDemande }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $demande->idDemande }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('demandes.reject.responsable', $demande->idDemande) }}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rejectModalLabel{{ $demande->idDemande }}">{{ __('Refuser la demande') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="commentaire{{ $demande->idDemande }}" class="form-label">{{ __('Motif du refus') }}</label>
                                                                <textarea class="form-control" id="commentaire{{ $demande->idDemande }}" name="commentaire" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                                            <button type="submit" class="btn btn-danger">{{ __('Confirmer le refus') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @elseif (Auth::user()->role == 'archiviste' && $demande->statut == 'approuvé_responsable')
                                            <!-- Bouton pour ouvrir le modal d'approbation -->
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $demande->idDemande }}">
                                                <i class="fas fa-check"></i> Approuver
                                            </button>

                                            <!-- Modal d'approbation -->
                                            <div class="modal fade" id="approveModal{{ $demande->idDemande }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $demande->idDemande }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('demandes.approve.archiviste', $demande->idDemande) }}">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="approveModalLabel{{ $demande->idDemande }}">Fixer la date de récupération</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="dateRecuperation" class="form-label">Date de récupération *</label>
                                                                    <input type="datetime-local" 
                                                                        class="form-control" 
                                                                        id="dateRecuperation" 
                                                                        name="dateRecuperation" 
                                                                        min="{{ now()->format('Y-m-d\TH:i') }}"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="submit" class="btn btn-success">Valider l'approbation</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $demande->idDemande }}">
                                                <i class="fas fa-times"></i> Rejeter
                                            </button>
                                            <!-- Rejection Modal -->
                                            <div class="modal fade" id="rejectModal{{ $demande->idDemande }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $demande->idDemande }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form method="POST" action="{{ route('demandes.reject.archiviste', $demande->idDemande) }}">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="rejectModalLabel{{ $demande->idDemande }}">{{ __('Refuser la demande') }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="commentaire{{ $demande->idDemande }}" class="form-label">{{ __('Motif du refus') }}</label>
                                                                    <textarea class="form-control " 
                                                                            id="commentaire{{ $demande->idDemande }}" 
                                                                            name="commentaire" 
                                                                            rows="3" 
                                                                            required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="fas fa-times"></i> {{ __('Annuler') }}
                                                                </button>
                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fas fa-check"></i> {{ __('Confirmer le refus') }}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                          @endif
                                    </div>  
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</div>
@endsection