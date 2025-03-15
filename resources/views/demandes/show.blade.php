@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Détails de la demande #') }}{{ $demande->idDemande }}</h5>
        <a href="{{ route('demandes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> {{ __('Retour') }}
        </a>
    </div>
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>{{ __('Informations de la demande') }}</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <td>{{ $demande->idDemande }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Statut') }}</th>
                        <td>
                            <span class="badge status-{{ $demande->statut }}">
                                @switch($demande->statut)
                                    @case('en_attente')
                                        {{ __('En attente') }}
                                        @break
                                    @case('approuvé_responsable')
                                        {{ __('Approuvé par responsable') }}
                                        @break
                                    @case('refusé_responsable')
                                        {{ __('Refusé par responsable') }}
                                        @break
                                    @case('approuvé_archiviste')
                                        {{ __('Approuvé par archiviste') }}
                                        @break
                                    @case('refusé_archiviste')
                                        {{ __('Refusé par archiviste') }}
                                        @break
                                    @case('récupéré')
                                        {{ __('Récupéré') }}
                                        @break
                                @endswitch
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('Date de soumission') }}</th>
                        <td>{{ (new DateTime($demande->dateSoumission))->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Description') }}</th>
                        <td>{{ $demande->description }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>{{ __('Informations du document') }}</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>{{ __('Titre') }}</th>
                        <td>{{ $demande->document->titre }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Type') }}</th>
                        <td>{{ $demande->document->type }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Société') }}</th>
                        <td>{{ $demande->document->societe ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Direction') }}</th>
                        <td>{{ $demande->document->direction ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Service') }}</th>
                        <td>{{ $demande->document->service ?: 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6>{{ __('Demandeur') }}</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>{{ __('Nom') }}</th>
                        <td>{{ $demande->utilisateur->nom }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Email') }}</th>
                        <td>{{ $demande->utilisateur->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Fonction') }}</th>
                        <td>{{ $demande->utilisateur->fonction ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Service') }}</th>
                        <td>{{ $demande->utilisateur->service ?: 'N/A' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>{{ __('Processus d\'approbation') }}</h6>
                <table class="table table-bordered">
                    <tr>
                        <th>{{ __('Responsable service') }}</th>
                        <td>{{ $demande->responsable ? $demande->responsable->nom : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Validation responsable') }}</th>
                        <td>{{ $demande->dateValidationResponsable ? $demande->dateValidationResponsable->format('d/m/Y H:i') : 'En attente' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Archiviste') }}</th>
                        <td>{{ $demande->archiviste ? $demande->archiviste->nom : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Validation archiviste') }}</th>
                        <td>{{ $demande->dateValidationArchiviste ? $demande->dateValidationArchiviste->format('d/m/Y H:i') : 'En attente' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('Récupération') }}</th>
                        <td>{{ $demande->dateRecuperation ? $demande->dateRecuperation->format('d/m/Y H:i') : 'Non récupéré' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($demande->certificat)
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">{{ __('Certificat disponible') }}</h6>
            </div>
            <div class="card-body">
                <p>{{ __('Date de génération:') }} {{ $demande->certificat->dateGeneration->format('d/m/Y H:i') }}</p>
                <p>{{ __('Signature:') }} 
                    @if($demande->certificat->signatureUtilisateur)
                        <span class="badge bg-success">{{ __('Signé') }}</span>
                    @else
                        <span class="badge bg-warning">{{ __('Non signé') }}</span>
                    @endif
                </p>
                <a href="{{ route('certificats.show', $demande->certificat->idCertificat) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i> {{ __('Voir le certificat') }}
                </a>
                <a href="{{ route('certificats.download', $demande->certificat->idCertificat) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-download"></i> {{ __('Télécharger le PDF') }}
                </a>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">{{ __('Actions') }}</h6>
            </div>
            <div class="card-body">
                <!-- Responsable actions -->
                @if(Auth::user()->role == 'responsable' && Auth::user()->idUtilisateur == $demande->idResponsableService && $demande->statut == 'en_attente')
                <div class="row">
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('demandes.approve.responsable', $demande->idDemande) }}">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check"></i> {{ __('Approuver') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectResponsableModal">
                            <i class="fas fa-times"></i> {{ __('Refuser') }}
                        </button>
                    </div>
                </div>

                <!-- Reject Modal for Responsable -->
                <div class="modal fade" id="rejectResponsableModal" tabindex="-1" aria-labelledby="rejectResponsableModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('demandes.reject.responsable', $demande->idDemande) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectResponsableModalLabel">{{ __('Refuser la demande') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="commentaire" class="form-label">{{ __('Motif du refus') }}</label>
                                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
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
                @endif

                <!-- Archiviste actions -->
                @if(Auth::user()->role == 'archiviste' && Auth::user()->idUtilisateur == $demande->idArchiviste && $demande->statut == 'approuvé_responsable')
                <div class="row">
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('demandes.approve.archiviste', $demande->idDemande) }}">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check"></i> {{ __('Approuver') }}
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectArchivisteModal">
                            <i class="fas fa-times"></i> {{ __('Refuser') }}
                        </button>
                    </div>
                </div>

                <!-- Reject Modal for Archiviste -->
                <div class="modal fade" id="rejectArchivisteModal" tabindex="-1" aria-labelledby="rejectArchivisteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('demandes.reject.archiviste', $demande->idDemande) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectArchivisteModalLabel">{{ __('Refuser la demande') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="commentaire" class="form-label">{{ __('Motif du refus') }}</label>
                                        <textarea class="form-control" id="commentaire" name="commentaire" rows="3" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                    <button type="submit" class="btnbtn-danger\">{{ __('Confirmer le refus') }}</button>
                        </div>
                        </form>
                        </div>
                    </div>
                </div>
                @endif

                                @if(Auth::user()->role == 'archiviste' && $demande->statut == 'approuvé_archiviste')
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#retrieveModal">
                            <i class="fas fa-file-export"></i> {{ __('Marquer comme récupéré') }}
                        </button>
                    </div>
                </div>

                                <div class="modal fade" id="retrieveModal" tabindex="-1" aria-labelledby="retrieveModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('demandes.retrieve', $demande->idDemande) }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="retrieveModalLabel">{{ __('Confirmer la récupération') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="signature" class="form-label">{{ __('Signature de l\'utilisateur') }}</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="signature" name="signature" value="accepted" required>
                                            <label class="form-check-label" for="signature">
                                                {{ __('Je confirme avoir récupéré le document') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Annuler') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('Confirmer la récupération') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection