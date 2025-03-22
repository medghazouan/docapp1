@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Tableau de bord') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if($unreadNotifications > 0)
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-bell"></i> Vous avez {{ $unreadNotifications }} notification(s) non lue(s).
                            <a href="{{ route('notifications.index') }}" class="alert-link">Voir les notifications</a>
                        </div>
                    @endif

                    <h2>Bienvenue, {{ Auth::user()->nom }}!</h2>
                    <p>Vous êtes connecté en tant que <strong>{{ ucfirst(Auth::user()->role) }}</strong>.</p>

                    <div class="row mt-4">
                        <!-- Stats cards - visible to all roles -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Mes demandes</h5>
                                    <p class="card-text display-4">{{ $stats['mes_demandes'] }}</p>
                                    <a href="{{ route('demandes.index') }}" class="btn btn-sm btn-light">Voir mes demandes</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notifications card - visible to all roles -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Notifications</h5>
                                    <p class="card-text display-4">{{ $unreadNotifications }}</p>
                                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-light">Voir notifications</a>
                                </div>
                            </div>
                        </div>

                        <!-- Pending requests card - visible to responsables and archivistes -->
                        @if(Auth::user()->role == 'responsable' || Auth::user()->role == 'archiviste' || Auth::user()->role == 'admin')
                        <div class="col-md-3 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        @if(Auth::user()->role == 'responsable')
                                            Demandes en attente
                                        @elseif(Auth::user()->role == 'archiviste')
                                            À valider
                                        @else
                                            Demandes en cours
                                        @endif
                                    </h5>
                                    <p class="card-text display-4">{{ $stats['demandes_en_attente'] }}</p>
                                    <a href="{{ route('demandes.index') }}" class="btn btn-sm btn-light">Traiter les demandes</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Documents stats card - visible to admin and archiviste -->
                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'archiviste')
                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Documents totaux</h5>
                                    <p class="card-text display-4">{{ $stats['total_documents'] }}</p>
                                    <a href="{{ route('documents.index') }}" class="btn btn-sm btn-light">Gérer les documents</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Admin-specific stats -->

                    @if(Auth::user()->role == 'admin')
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Filtrer les demandes</h4>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('home') }}" method="GET" class="row">
                                            <div class="col-md-3 form-group">
                                                <label for="utilisateur">Nom d'utilisateur</label>
                                                <input type="text" name="utilisateur" id="utilisateur" class="form-control" value="{{ request('utilisateur') }}">
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="type">Type de document</label>
                                                <select name="type" id="type" class="form-control">
                                                    <option value="">Tous</option>
                                                    @foreach($documentTypes as $type)
                                                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="service">Service</label>
                                                <select name="service" id="service" class="form-control">
                                                    <option value="">Tous</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>{{ $service }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="date_debut">Date début</label>
                                                <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                                            </div>
                                            <div class="col-md-2 form-group">
                                                <label for="date_fin">Date fin</label>
                                                <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                                            </div>
                                            
                                            <div class="col-md-2 form-group">
                                                <label for="statut">Statut</label>
                                                <select name="statut" id="statut" class="form-control">
                                                    <option value="">Tous</option>
                                                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                                    <option value="approuvé_responsable" {{ request('statut') == 'approuvé_responsable' ? 'selected' : '' }}>Approuvé par responsable</option>
                                                    <option value="refusé_responsable" {{ request('statut') == 'refusé_responsable' ? 'selected' : '' }}>Refusé par responsable</option>
                                                    <option value="approuvé_archiviste" {{ request('statut') == 'approuvé_archiviste' ? 'selected' : '' }}>Approuvé par archiviste</option>
                                                    <option value="refusé_archiviste" {{ request('statut') == 'refusé_archiviste' ? 'selected' : '' }}>Refusé par archiviste</option>
                                                    <option value="récupéré" {{ request('statut') == 'récupéré' ? 'selected' : '' }}>Récupéré</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary mr-2">Filtrer</button>
                                                <a href="{{ route('home') }}" class="btn btn-secondary">Réinitialiser</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Detailed Demands Statistics</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Total Demands:</strong> {{ $stats['total_demandes'] }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Pending Demands:</strong> {{ $stats['demandes_en_attente'] }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>My Demands:</strong> {{ $stats['mes_demandes'] }}</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Demand Distribution by Status</h5>
                                                <div style="height: 300px;">
                                                    <canvas id="statusChart"></canvas>
                                                </div>
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <h5>Average Processing Time (heures)</h5>
                                                <div style="height: 300px;">
                                                    <canvas id="processingTimeChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Recent Demands Processing Time</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Document</th>
                                                        <th>Requester</th>
                                                        <th>Responsable</th>
                                                        <th>Archiviste</th>
                                                        
                                                        <th>Submission Date</th>
                                                        <th>Responsible Validation</th>
                                                        <th>Responsible Delay h</th>
                                                        <th>Archivist Validation</th>
                                                        <th>Archivist Delay h</th>
                                                        <th>Retrieval</th>
                                                        <th>Total Time h</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($demandes ?? [] as $demande)
                                                        @php
                                                            // Calculate processing times
                                                            $dateSoumission = \Carbon\Carbon::parse($demande->dateSoumission);
                                                            $dateValidationResponsable = $demande->dateValidationResponsable ? \Carbon\Carbon::parse($demande->dateValidationResponsable) : null;
                                                            $dateValidationArchiviste = $demande->dateValidationArchiviste ? \Carbon\Carbon::parse($demande->dateValidationArchiviste) : null;
                                                            $dateRecuperation = $demande->dateRecuperation ? \Carbon\Carbon::parse($demande->dateRecuperation) : null;

                                                            $delaiResponsable = $dateValidationResponsable ? $dateSoumission->diffInHours($dateValidationResponsable) : null;
                                                            $delaiArchiviste = $dateValidationArchiviste ? ($dateValidationResponsable ? $dateValidationResponsable->diffInHours($dateValidationArchiviste) : null) : null;
                                                            $tempsTotalTraitement = $dateRecuperation ? $dateSoumission->diffInHours($dateRecuperation) : null;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $demande->idDemande }}</td>
                                                            <td>{{ $demande->document->titre }}</td>
                                                            <td>{{ $demande->utilisateur->nom }}</td>
                                                            <td>{{ $demande->responsable->nom }}</td>
                                                            <td>{{ $demande->archiviste?->nom ?? '-' }}</td>
                                                            <td>{{ $demande->dateSoumission }}</td>
                                                            <td>{{ (new DateTime($demande->dateValidationResponsable) ) ? (new DateTime($demande->dateValidationResponsable) )->format('Y-m-d H:i:s') : 'Pending' }}</td>
                                                            <td>{{ $delaiResponsable !== null ? $delaiResponsable . ' H' : '-' }}</td>
                                                            <td>{{ (new DateTime($demande->dateValidationArchiviste) ) ? (new DateTime($demande->dateValidationArchiviste) )->format('Y-m-d H:i:s') : 'Pending' }}</td>
                                                            <td>{{ $delaiArchiviste !== null ? $delaiArchiviste . ' H' : '-' }}</td>
                                                            <td>{{ (new DateTime($demande->dateRecuperation) ) ? (new DateTime($demande->dateRecuperation) )->format('Y-m-d H:i:s') : 'Pending' }}</td>
                                                            <td>{{ $tempsTotalTraitement !== null ? $tempsTotalTraitement . ' hours' : '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Responsable-specific section -->
                    @if(Auth::user()->role == 'responsable')
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Demandes nécessitant votre approbation</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Document</th>
                                                    <th>Demandeur</th>
                                                    <th>Date soumission</th>
                                                    <th>Délai</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($demandes ?? [] as $demande)
                                                @php
                                                    // Calculate processing times
                                                    $dateSoumission = \Carbon\Carbon::parse($demande->dateSoumission);
                                                    $dateValidationResponsable = $demande->dateValidationResponsable ? \Carbon\Carbon::parse($demande->dateValidationResponsable) : null;
                                                    $dateValidationArchiviste = $demande->dateValidationArchiviste ? \Carbon\Carbon::parse($demande->dateValidationArchiviste) : null;
                                                    $dateRecuperation = $demande->dateRecuperation ? \Carbon\Carbon::parse($demande->dateRecuperation) : null;

                                                    $delaiResponsable = $dateValidationResponsable ? $dateSoumission->diffInHours($dateValidationResponsable) : null;
                                                    $delaiArchiviste = $dateValidationArchiviste ? ($dateValidationResponsable ? $dateValidationResponsable->diffInHours($dateValidationArchiviste) : null) : null;
                                                    $tempsTotalTraitement = $dateRecuperation ? $dateSoumission->diffInHours($dateRecuperation) : null;
                                                @endphp
                                                <tr>
                                                    <td>{{ $demande->idDemande }}</td>
                                                    <td>{{ $demande->document->titre }}</td>
                                                    <td>{{ $demande->utilisateur->nom }}</td>
                                                    <td>{{ $demande->dateSoumission }}</td>
                                                    <td>{{  $delaiResponsable !== null ? $delaiResponsable . ' H' : '-' }} </td>
                                                    <td>
                                                        <!-- Replace the existing reject button and modal in the responsable section -->
                                                    @if ($demande->statut === 'en_attente')
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('demandes.approve.responsable', $demande->idDemande) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                                                        </form>
                                                        
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $demande->idDemande }}">
                                                            Rejeter
                                                        </button>
                                                    </div>

                                                    <!-- Rejection Modal -->
                                                    <div class="modal fade" id="rejectModal{{ $demande->idDemande }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $demande->idDemande }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="rejectModalLabel{{ $demande->idDemande }}">Rejeter la demande</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ route('demandes.reject.responsable', $demande->idDemande) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            <label for="commentaire{{ $demande->idDemande }}">Raison du rejet:</label>
                                                                            <textarea class="form-control" id="commentaire{{ $demande->idDemande }}" name="commentaire" required></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                        <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @else
                                                        @switch($demande->statut)
                                                            @case('approuvé_responsable')
                                                                <span class="badge status-{{ $demande->statut }}">Approuvé par responsable</span>
                                                                @break
                                                            @case('refusé_responsable')
                                                                <span class="badge status-{{ $demande->statut }}">Refusé par responsable</span>
                                                                @break
                                                            @case('récupéré')
                                                                <span class="badge status-{{ $demande->statut }}">terminé</span>
                                                                @break
                                                            @case('approuvé_archiviste')
                                                                <span class="badge status-{{ $demande->statut }}">approuvé par archiviste</span>
                                                                @break
                                                            @case('refusé_archiviste')
                                                                <span class="badge status-{{ $demande->statut }}">Refusé par archiviste</span>
                                                                @break
                                                        @endswitch
                                                    @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Archiviste-specific section -->
                    @if(Auth::user()->role == 'archiviste')
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Demandes nécessitant votre validation</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Document</th>
                                                    <th>Demandeur</th>
                                                    <th>Approbation responsable</th>
                                                    <th>Délai</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($demandes ?? [] as $demande)
                                                @php
                                                    // Calculate processing times
                                                    $dateSoumission = \Carbon\Carbon::parse($demande->dateSoumission);
                                                    $dateValidationResponsable = $demande->dateValidationResponsable ? \Carbon\Carbon::parse($demande->dateValidationResponsable) : null;
                                                    $dateValidationArchiviste = $demande->dateValidationArchiviste ? \Carbon\Carbon::parse($demande->dateValidationArchiviste) : null;
                                                    $dateRecuperation = $demande->dateRecuperation ? \Carbon\Carbon::parse($demande->dateRecuperation) : null;

                                                    $delaiResponsable = $dateValidationResponsable ? $dateSoumission->diffInHours($dateValidationResponsable) : null;
                                                    $delaiArchiviste = $dateValidationArchiviste ? ($dateValidationResponsable ? $dateValidationResponsable->diffInHours($dateValidationArchiviste) : null) : null;
                                                    $tempsTotalTraitement = $dateRecuperation ? $dateSoumission->diffInHours($dateRecuperation) : null;
                                                @endphp
                                                <tr>
                                                    <td>{{ $demande->idDemande }}</td>
                                                    <td>{{ $demande->document->titre }}</td>
                                                    <td>{{ $demande->utilisateur->nom }}</td>
                                                    <td>{{ $demande->dateValidationResponsable }}</td>
                                                    <td>{{ $delaiArchiviste !== null ? $delaiArchiviste . ' H' : '-'  }} </td>
                                                    <td>
                                                        @if ($demande->statut === 'approuvé_responsable')
                                                        <div class="btn-group" role="group">
                                                            <form action="{{ route('demandes.approve.archiviste', $demande->idDemande) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                                                            </form>
                                                            
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $demande->idDemande }}">
                                                                Rejeter
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Rejection Modal -->
                                                        <div class="modal fade" id="rejectModal{{ $demande->idDemande }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $demande->idDemande }}" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="rejectModalLabel{{ $demande->idDemande }}">Rejeter la demande</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <form action="{{ route('demandes.reject.archiviste', $demande->idDemande) }}" method="POST">
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <div class="form-group">
                                                                                <label for="commentaire">Raison du rejet:</label>
                                                                                <textarea class="form-control" id="commentaire" name="commentaire" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                            <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                       
                                                        @else
                                                        @switch($demande->statut)
                                                            @case('approuvé_archiviste')
                                                                <span class="badge status-{{ $demande->statut }}">approuvépar archiviste</span>
                                                                @break
                                                            @case('refusé_archiviste')
                                                                <span class="badge status-{{ $demande->statut }}">Refusé par archiviste</span>
                                                                @break
                                                            @case('récupéré')
                                                                <span class="badge status-{{ $demande->statut }}">Terminé</span>
                                                                @break
                                                            
                                                        @endswitch
                                                        @endif

                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- User-specific section -->
                    @if(Auth::user()->role == 'utilisateur')
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Mes demandes récentes</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Document</th>
                                                    <th>Date soumission</th>
                                                    <th>Statut</th>
                                                    <th>Dernière mise à jour</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($demandes  as $demande)
                                                <tr>
                                                    <td>{{ $demande->idDemande }}</td>
                                                    <td>{{ $demande->document->titre }}</td>
                                                    <td>{{ $demande->dateSoumission }}</td>
                                                    <td>
                                                        @if($demande->statut == 'en_attente')
                                                            <span class="badge status-en_attente">Soumise</span>
                                                        @elseif($demande->statut == 'approuvé_responsable')
                                                            <span class="badge status-approuvé_responsable">Validée par responsable</span>
                                                        @elseif($demande->statut == 'approuvé_archiviste')
                                                            <span class="badge status-approuvé_archiviste">Prête pour récupération</span>
                                                        @elseif($demande->statut == 'refusé_responsable||refusé_archiviste')
                                                            <span class="badge badge status-{{ $demande->statut }}">Rejetée</span>
                                                        @elseif($demande->statut == 'récupéré')
                                                            <span class="badge badge status-{{ $demande->statut }}">Terminé</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $demande->updated_at }}</td>
                                                    <td>
                                                        <a href="{{ route('demandes.show', $demande->idDemande) }}" class="btn btn-sm btn-info">Détails</a>
                                                        
                                                        @if($demande->statut == 'validee_archiviste')
                                                        <form action="{{ route('demandes.marquer-recupere', $demande->idDemande) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">Marquer comme récupéré</button>
                                                        </form>
                                                        @endif
                                                        
                                                        @if($demande->statut == 'soumise')
                                                        <form action="{{ route('demandes.annuler', $demande->idDemande) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-warning">Annuler</button>
                                                        </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('demandes.create') }}" class="btn btn-primary">Nouvelle demande</a>
                                        <a href="{{ route('demandes.index') }}" class="btn btn-secondary">Voir toutes mes demandes</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Only create charts if elements exist (admin view)
    if (document.getElementById('statusChart')) {
        // Status distribution chart
        var statusCtx = document.getElementById('statusChart').getContext('2d');
        var statusChart = new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['En attente', 'Approuvé Responsable', 'Refusé Responsable', 'Approuvé Archiviste', 'Refusé Archiviste', 'Récupéré'],
                    datasets: [{
                        data: [
                            {{ isset($stats['statusDistribution']['en_attente']) ? $stats['statusDistribution']['en_attente'] : 0 }},
                            {{ isset($stats['statusDistribution']['approuvé_responsable']) ? $stats['statusDistribution']['approuvé_responsable'] : 0 }},
                            {{ isset($stats['statusDistribution']['refusé_responsable']) ? $stats['statusDistribution']['refusé_responsable'] : 0 }},
                            {{ isset($stats['statusDistribution']['approuvé_archiviste']) ? $stats['statusDistribution']['approuvé_archiviste'] : 0 }},
                            {{ isset($stats['statusDistribution']['refusé_archiviste']) ? $stats['statusDistribution']['refusé_archiviste'] : 0 }},
                            {{ isset($stats['statusDistribution']['récupéré']) ? $stats['statusDistribution']['récupéré'] : 0 }}
                        ],
                        backgroundColor: [
                            '#17a2b8', // En attente
                            '#007bff', // Approuvé Responsable
                            '#dc3545', // Refusé Responsable
                            '#28a745', // Approuvé Archiviste
                            '#fd7e14', // Refusé Archiviste
                            '#6c757d'  // Récupéré
                        ]
                    }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                legend: {
                    position: 'bottom'
                }
            }
        });
        
        // Processing time chart
        var timeCtx = document.getElementById('processingTimeChart').getContext('2d');
        var processingHours = {{ isset($stats['avg_processing_time']) ? $stats['avg_processing_time'] : 0 }};
        var processingDays = processingHours / 24;

        var timeChart = new Chart(timeCtx, {
            type: 'bar',
            data: {
                labels: ['Temps de traitement moyen'],
                datasets: [{
                    label: 'Jours',
                    data: [processingDays],
                    backgroundColor: ['#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        ticks: { 
                            beginAtZero: true,
                            callback: function(value) {
                                return value.toFixed(1) + ' j';
                            }
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var hours = tooltipItem.yLabel * 24;
                            return hours.toFixed(1) + ' heures (' + tooltipItem.yLabel.toFixed(1) + ' jours)';
                        }
                    }
                },
                animation: {
                    onComplete: function() {
                        if (processingHours == 0) {
                            // Display a message when there's no data
                            var ctx = this.chart.ctx;
                            ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, 'normal', Chart.defaults.global.defaultFontFamily);
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.fillStyle = '#666';
                            ctx.fillText('Aucune donnée disponible', this.chart.width / 2, this.chart.height / 2);
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
@endsection