@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Certificat de prêt de document') }}</h5>
                    <a href="{{ route('certificats.download', $certificat->idCertificat) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-download"></i> Télécharger PDF
                    </a>
                </div>
                <div class="card-body">
                    <div class="border p-4 mb-4">
                        <div class="text-center mb-4">
                            <h4>CERTIFICAT DE PRÊT DE DOCUMENT</h4>
                            <p>N° {{ $certificat->idCertificat }}</p>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Date de génération:</strong> {{ (new DateTime($certificat->dateGeneration) )->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p><strong>État:</strong> 
                                    @if($certificat->signatureUtilisateur)
                                        <span class="badge bg-success">Signé</span>
                                    @else
                                        <span class="badge bg-warning">En attente de signature</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Information sur l'utilisateur</h5>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Nom:</strong> {{ $certificat->utilisateur->nom }}</p>
                                <p><strong>Email:</strong> {{ $certificat->utilisateur->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Fonction:</strong> {{ $certificat->utilisateur->fonction ?: 'Non spécifié' }}</p>
                                <p><strong>Service:</strong> {{ $certificat->utilisateur->service ?: 'Non spécifié' }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Information sur le document</h5>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Titre:</strong> {{ $certificat->document->titre }}</p>
                                <p><strong>Type:</strong> {{ $certificat->document->type }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Direction:</strong> {{ $certificat->document->direction ?: 'Non spécifié' }}</p>
                                <p><strong>Service:</strong> {{ $certificat->document->service ?: 'Non spécifié' }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Information sur la demande</h5>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date de soumission:</strong> {{ (new DateTime($certificat->demande->dateSoumission) )->format('d/m/Y H:i') }}</p>
                                <p><strong>Date d'approbation (Responsable):</strong> 
                                    {{ (new DateTime( $certificat->demande->dateValidationResponsable)) ? (new DateTime($certificat->demande->dateValidationResponsable) )->format('d/m/Y H:i') : 'Non applicable' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date d'approbation (Archiviste):</strong> 
                                    {{ (new DateTime($certificat->demande->dateValidationArchiviste) ) ? (new DateTime($certificat->demande->dateValidationArchiviste) )->format('d/m/Y H:i') : 'Non applicable' }}
                                </p>
                                <p><strong>Date de récupération:</strong> 
                                    {{ (new DateTime($certificat->demande->dateRecuperation) ) ? (new DateTime($certificat->demande->dateRecuperation)) ->format('d/m/Y H:i') : 'Non récupéré' }}
                                </p>
                                <p><strong>Date de retour prévue:</strong> 
                                    {{ (new DateTime($certificat->demande->dateRetour) ) ? (new DateTime($certificat->demande->dateRetour)) ->format('d/m/Y H:i') : 'Non récupéré' }}
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5>Description de la demande</h5>
                                <p>{{ $certificat->demande->description }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>Approbations</h5>
                                <p><strong>Responsable:</strong> {{ $certificat->demande->responsable->nom }}</p>
                                <p><strong>Archiviste:</strong> {{ $certificat->demande->archiviste->nom }}</p>
                            </div>
                            <div class="col-md-6">
                                <h5>Signature</h5>
                                @if($certificat->signatureUtilisateur)
                                    <p class="text-success"><i class="fas fa-check-circle"></i> Document signé par l'utilisateur</p>
                                    <p>Date de signature: {{ (new DateTime($certificat->demande->dateRecuperation) )->format('d/m/Y H:i') }}</p>
                                @else
                                    <p class="text-warning"><i class="fas fa-exclamation-circle"></i> En attente de signature par l'utilisateur</p>
                                    
                                    @if(Auth::user()->idUtilisateur == $certificat->demande->idArchiviste)
                                        <form method="POST" action="{{ route('demandes.retrieve', $certificat->demande->idDemande) }}">
                                            @csrf
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="signature" id="signature" value="1" required>
                                                <label class="form-check-label" for="signature">
                                                    Je confirme que l'utilisateur a signé et récupéré le document
                                                </label>
                                            </div>
                                            
                                            <div class="form-group mb-3">
                                                <label for="duree_max_retour">Durée maximale de retour (jours)</label>
                                                <input type="number" class="form-control" id="duree_max_retour" name="duree_max_retour" 
                                                       min="1" max="{{ $certificat->demande->document->duree_max_retour }}" 
                                                       value="{{ $certificat->demande->document->duree_max_retour }}" required>
                                                <small class="form-text text-muted">
                                                    Durée maximale autorisée : {{ $certificat->demande->document->duree_max_retour }} jours
                                                </small>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check"></i> Marquer comme récupéré
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('demandes.show', $certificat->demande->idDemande) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour à la demande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection