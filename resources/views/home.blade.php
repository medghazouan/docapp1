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
                        <div class="alert custom-alert mt-4">
                            <i class="fas fa-bell"></i> Vous avez {{ $unreadNotifications }} notification(s) non lue(s).
                            <a href="{{ route('notifications.index') }}" class="alert-link">Voir les notifications</a>
                        </div>
                    @endif

                    <h2>Bienvenue, {{ Auth::user()->nom }}!</h2>
                    <p id="role">Vous êtes connecté en tant que <strong >{{ ucfirst(Auth::user()->role) }}</strong>.</p>

                    <div class="row mt-4">
                        <!-- Stats cards - visible to all roles -->
                        
                    <div class="col-md-3 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h5>Mes demandes</h5>
                            <div class="stats-value">{{ $stats['mes_demandes'] }}</div>
                            <a href="{{ route('demandes.index') }}" class="stats-link">
                                Voir détails <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Notifications card -->
                    <div class="col-md-3 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <h5>Notifications</h5>
                            <div class="stats-value">{{ $unreadNotifications }}</div>
                            <a href="{{ route('notifications.index') }}" class="stats-link">
                                Voir détails <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Pending Requests card - visible to specific roles -->
                    @if(Auth::user()->role == 'responsable' || Auth::user()->role == 'archiviste' || Auth::user()->role == 'admin')
                    <div class="col-md-3 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5>
                                @if(Auth::user()->role == 'responsable')
                                    Demandes en attente
                                @elseif(Auth::user()->role == 'archiviste')
                                    À valider
                                @else
                                    Demandes en cours
                                @endif
                            </h5>
                            <div class="stats-value">{{ $stats['demandes_en_attente'] }}</div>
                            <a href="{{ route('demandes.index') }}" class="stats-link">
                                Traiter les demandes <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Documents stats card - visible to admin and archiviste -->
                    @if(Auth::user()->role == 'admin' || Auth::user()->role == 'archiviste')
                    <div class="col-md-3 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class="fas fa-folder"></i>
                            </div>
                            <h5>Documents totaux</h5>
                            <div class="stats-value">{{ $stats['total_documents'] }}</div>
                            <a href="{{ route('documents.index') }}" class="stats-link">
                                Gérer les documents <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                    </div>

                    <!-- Admin-specific stats -->

                    @if(Auth::user()->role == 'admin')
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                   <!-- Replace the existing filter form section -->
                                    <div class="filters-card mb-4">
                                        <div class="card-header">
                                            <h4><i class="fas fa-filter"></i> Filtrer les demandes</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('home') }}" method="GET" class="row g-3">
                                                <div class="col-md-3">
                                                    <div class="form-group custom-form-group">
                                                        <label for="utilisateur">
                                                            <i class="fas fa-user"></i> Nom d'utilisateur
                                                        </label>
                                                        <input type="text" 
                                                            name="utilisateur" 
                                                            id="utilisateur" 
                                                            class="form-control custom-input" 
                                                            value="{{ request('utilisateur') }}"
                                                            >
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group custom-form-group">
                                                        <label for="type">
                                                            <i class="fas fa-file-alt"></i> Type de document
                                                        </label>
                                                        <select name="type" id="type" class="form-control custom-select">
                                                            <option value="">Tous les types</option>
                                                            @foreach($documentTypes as $type)
                                                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                                                    {{ $type }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group custom-form-group">
                                                        <label for="service">
                                                            <i class="fas fa-building"></i> Service
                                                        </label>
                                                        <select name="service" id="service" class="form-control custom-select">
                                                            <option value="">Tous les services</option>
                                                            @foreach($services as $service)
                                                                <option value="{{ $service }}" {{ request('service') == $service ? 'selected' : '' }}>
                                                                    {{ $service }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group custom-form-group">
                                                        <label for="date_debut">
                                                            <i class="fas fa-calendar-alt"></i> Date début
                                                        </label>
                                                        <input type="date" 
                                                            name="date_debut" 
                                                            id="date_debut" 
                                                            class="form-control custom-input" 
                                                            value="{{ request('date_debut') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="form-group custom-form-group">
                                                        <label for="date_fin">
                                                            <i class="fas fa-calendar-alt"></i> Date fin
                                                        </label>
                                                        <input type="date" 
                                                            name="date_fin" 
                                                            id="date_fin" 
                                                            class="form-control custom-input" 
                                                            value="{{ request('date_fin') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group custom-form-group">
                                                        <label for="statut">
                                                            <i class="fas fa-tasks"></i> Statut
                                                        </label>
                                                        <select name="statut" id="statut" class="form-control custom-select">
                                                            <option value="">Tous les statuts</option>
                                                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>
                                                                En attente
                                                            </option>
                                                            <option value="approuvé_responsable" {{ request('statut') == 'approuvé_responsable' ? 'selected' : '' }}>
                                                                Approuvé par responsable
                                                            </option>
                                                            <option value="refusé_responsable" {{ request('statut') == 'refusé_responsable' ? 'selected' : '' }}>
                                                                Refusé par responsable
                                                            </option>
                                                            <option value="approuvé_archiviste" {{ request('statut') == 'approuvé_archiviste' ? 'selected' : '' }}>
                                                                Approuvé par archiviste
                                                            </option>
                                                            <option value="refusé_archiviste" {{ request('statut') == 'refusé_archiviste' ? 'selected' : '' }}>
                                                                Refusé par archiviste
                                                            </option>
                                                            <option value="récupéré" {{ request('statut') == 'récupéré' ? 'selected' : '' }}>
                                                                Récupéré
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <div class="filter-buttons">
                                                        <button type="submit" class="btn custom-btn-primary">
                                                            <i class="fas fa-search"></i> Filtrer
                                                        </button>
                                                        <a href="{{ route('home') }}" class="btn custom-btn-secondary">
                                                            <i class="fas fa-undo"></i> Réinitialiser
                                                        </a>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>Temps de retour moyen (heures)</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div style="height: 300px;">
                                                                <canvas id="returnTimeChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Document</th>
                                                        <th>Requester</th>
                                                        <th>Responsable</th>
                                                        <th>Archiviste</th>
                                                        <th>Statut</th>
                                                        <th>Submission Date</th>
                                                        <th>Responsible Validation</th>
                                                        <th>Responsible Delay h</th>
                                                        <th>Archivist Validation</th>
                                                        <th>Archivist Delay h</th>
                                                        <th>Retrieval</th>
                                                        <th>Total Time h</th>
                                                        <th>Date Retour spécifiée</th>

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
                                                            $dateRetour = $demande->dateRetour ? \Carbon\Carbon::parse($demande->dateRetour) : null;

                                                            $delaiResponsable = $dateValidationResponsable ? $dateSoumission->diffInHours($dateValidationResponsable) : null;
                                                            $delaiArchiviste = ($dateValidationArchiviste && $dateValidationResponsable) ?$dateValidationResponsable->diffInHours($dateValidationArchiviste) : null;
                                                            $tempsTotalTraitement = $dateRecuperation ? $dateSoumission->diffInHours($dateRecuperation) : null;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $demande->idDemande }}</td>
                                                            <td>{{ $demande->document->titre }}</td>
                                                            <td>{{ $demande->utilisateur->nom }}</td>
                                                            <td>{{ $demande->responsable->nom }}</td>
                                                            <td>{{ $demande->archiviste?->nom ?? '-' }}</td>
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
                                                            <td>{{ $demande->dateSoumission }}</td>
                                                            <td>
                                                                @if($dateValidationResponsable)
                                                                {{ (new DateTime($dateValidationResponsable))->format('d/m/Y H:i') }}
                                                                @else
                                                                    {{ __('En attente') }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $delaiResponsable !== null ? $delaiResponsable . ' H' : '-' }}</td>
                                                            <td>
                                                                @if($dateValidationArchiviste)
                                                                    {{ (new DateTime($dateValidationArchiviste))->format('d/m/Y H:i') }}
                                                                @else
                                                                    {{ __('En attente') }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $delaiArchiviste !== null ? $delaiArchiviste . ' H' : '-' }}</td>
                                                            <td>
                                                                @if($dateRecuperation)
                                                                {{ (new DateTime($dateRecuperation))->format('d/m/Y H:i') }}
                                                                @else
                                                                    {{ __('Non récupéré') }}
                                                                @endif
                                                            </td>
                                                            <td>{{ $tempsTotalTraitement !== null ? $tempsTotalTraitement . ' hours' : '-' }}</td>
                                                            <td>
                                                                @if($dateRetour)
                                                                    {{ (new DateTime($dateRetour))->format('d/m/Y ') }}
                                                                @else
                                                                    {{ __('-') }}
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
                                        <table class="table table-striped table-hover">
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
                                                        <form action="{{ route('demandes.approve.responsable', $demande->idDemande) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="fas fa-check"></i> Approuver
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $demande->idDemande }}">
                                                            <i class="fas fa-times"></i> Refuser
                                                        </button>
                                                    </div>
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
                                        <table class="table table-striped table-hover">
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
                                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $demande->idDemande }}">
                                                                    <i class="fas fa-check"></i> Approuver
                                                                </button>
                                                            </form>
                                                            
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $demande->idDemande }}">
                                                                <i class="fas fa-times"></i> Rejeter
                                                            </button>
                                                        </div>
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
                                                        
                                                        <!-- MODIFY: Add rejection modal for archivist -->
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
                                        <table class="table table-striped table-hover">
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
    if (document.getElementById('returnTimeChart')) {
        var returnTimeCtx = document.getElementById('returnTimeChart').getContext('2d');
        var returnTimeHours = {{ isset($stats['avg_return_time']) ? $stats['avg_return_time'] : 0 }};
        var returnTimeDays = returnTimeHours / 24;

        var returnTimeChart = new Chart(returnTimeCtx, {
            type: 'bar',
            data: {
                labels: ['Temps de retour moyen'],
                datasets: [{
                    label: 'Heures',
                    data: [returnTimeHours],
                    backgroundColor: 'rgba(164, 150, 114, 0.9)',
                    borderColor: 'rgba(164, 150, 114, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: '#171717',
                            callback: function(value) {
                                return value + ' h';
                            }
                        },
                        gridLines: {
                            color: 'rgba(164, 150, 114, 0.1)',
                            zeroLineColor: 'rgba(164, 150, 114, 0.2)'
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            fontColor: '#171717'
                        }
                    }]
                },
                tooltips: {
                    backgroundColor: '#171717',
                    titleFontColor: '#ffffff',
                    bodyFontColor: '#ffffff',
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var hours = tooltipItem.yLabel;
                            var days = (hours / 24).toFixed(1);
                            return hours.toFixed(1) + ' heures (' + days + ' jours)';
                        }
                    }
                }
            }
        });
    }
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
                        'rgba(164, 150, 114, 0.9)',    // En attente - Primary color
                        'rgba(138, 125, 94, 0.9)',     // Approuvé Responsable - Primary dark
                        'rgba(191, 64, 64, 0.9)',      // Refusé Responsable - Professional red
                        'rgba(73, 155, 94, 0.9)',      // Approuvé Archiviste - Professional green
                        'rgba(226, 135, 67, 0.9)',     // Refusé Archiviste - Professional orange
                        'rgba(108, 117, 125, 0.9)'     // Récupéré - Professional grey
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                legend: {
                    position: 'right',
                    labels: {
                        fontFamily: "'Nunito', sans-serif",
                        fontSize: 12,
                        fontColor: '#171717',
                        padding: 20,
                        usePointStyle: true,
                        generateLabels: function(chart) {
                            var data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map(function(label, i) {
                                    var meta = chart.getDatasetMeta(0);
                                    var ds = data.datasets[0];
                                    var arc = meta.data[i];
                                    var custom = arc && arc.custom || {};
                                    var value = ds.data[i];
                                    var percentage = ((value / ds.data.reduce((a, b) => a + b)) * 100).toFixed(1);
                                    
                                    return {
                                        text: label + ' (' + percentage + '%)',
                                        fillStyle: ds.backgroundColor[i],
                                        hidden: isNaN(ds.data[i]) || meta.data[i].hidden,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltips: {
                    backgroundColor: 'rgba(23, 23, 23, 0.9)',
                    titleFontFamily: "'Nunito', sans-serif",
                    bodyFontFamily: "'Nunito', sans-serif",
                    titleFontSize: 14,
                    bodyFontSize: 13,
                    titleFontColor: '#ffffff',
                    bodyFontColor: '#ffffff',
                    caretSize: 6,
                    cornerRadius: 6,
                    xPadding: 10,
                    yPadding: 10,
                    displayColors: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = ((currentValue/total) * 100).toFixed(1);
                            return " " + data.labels[tooltipItem.index] + ": " + currentValue + " (" + percentage + "%)";
                        }
                    }
                }
            }
        });
        
        // Processing time chart with your theme color
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
                    backgroundColor: ['#a49672'], // Primary color
                    borderColor: '#8a7d5e',      // Primary dark
                    borderWidth: 1
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
                            fontColor: '#171717',
                            callback: function(value) {
                                return value.toFixed(1) + ' j';
                            }
                        },
                        gridLines: {
                            color: 'rgba(164, 150, 114, 0.1)',
                            zeroLineColor: 'rgba(164, 150, 114, 0.2)'
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            fontColor: '#171717'
                        }
                    }]
                },
                tooltips: {
                    backgroundColor: '#171717',
                    titleFontColor: '#ffffff',
                    bodyFontColor: '#ffffff',
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var hours = tooltipItem.yLabel * 24;
                            return hours.toFixed(1) + ' heures (' + tooltipItem.yLabel.toFixed(1) + ' jours)';
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