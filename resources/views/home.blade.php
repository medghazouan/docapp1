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
                                                    <option value="approuvé_archiviste" {{ request('statut') == 'approuvé_archiviste' ? 'selected' : '' }}>Approuvé par archiviste</option>
                                                    <option value="rejeté" {{ request('statut') == 'rejeté' ? 'selected' : '' }}>Rejeté</option>
                                                    <option value="terminé" {{ request('statut') == 'terminé' ? 'selected' : '' }}>Terminé</option>
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
                                                <canvas id="statusChart" width="400" height="200"></canvas>
                                            </div>

                                            <div class="col-md-6">
                                                <h5>Average Processing Time (Days)</h5>
                                                <canvas id="processingTimeChart" width="400" height="200"></canvas>
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
                                                        <th>Submission Date</th>
                                                        <th>Responsible Validation</th>
                                                        <th>Responsible Delay</th>
                                                        <th>Archivist Validation</th>
                                                        <th>Archivist Delay</th>
                                                        <th>Retrieval</th>
                                                        <th>Total Time</th>
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

                                                            $delaiResponsable = $dateValidationResponsable ? $dateSoumission->diffInDays($dateValidationResponsable) : null;
                                                            $delaiArchiviste = $dateValidationArchiviste ? ($dateValidationResponsable ? $dateValidationResponsable->diffInDays($dateValidationArchiviste) : null) : null;
                                                            $tempsTotalTraitement = $dateRecuperation ? $dateSoumission->diffInDays($dateRecuperation) : null;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $demande->idDemande }}</td>
                                                            <td>{{ $demande->document->titre }}</td>
                                                            <td>{{ $demande->utilisateur->nom }}</td>
                                                            <td>{{ $demande->dateSoumission }}</td>
                                                            <td>{{ (new DateTime($demande->dateValidationResponsable) ) ? (new DateTime($demande->dateValidationResponsable) )->format('Y-m-d H:i:s') : 'Pending' }}</td>
                                                            <td>{{ $delaiResponsable !== null ? $delaiResponsable . ' days' : '-' }}</td>
                                                            <td>{{ (new DateTime($demande->dateValidationArchiviste) ) ? (new DateTime($demande->dateValidationArchiviste) )->format('Y-m-d H:i:s') : 'Pending' }}</td>
                                                            <td>{{ $delaiArchiviste !== null ? $delaiArchiviste . ' days' : '-' }}</td>
                                                            <td>{{ (new DateTime($demande->dateRecuperation) ) ? (new DateTime($demande->dateRecuperation) )->format('Y-m-d H:i:s') : 'Pending' }}</td>
                                                            <td>{{ $tempsTotalTraitement !== null ? $tempsTotalTraitement . ' days' : '-' }}</td>
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
                                                <tr>
                                                    <td>{{ $demande->idDemande }}</td>
                                                    <td>{{ $demande->document->titre }}</td>
                                                    <td>{{ $demande->utilisateur->nom }}</td>
                                                    <td>{{ $demande->dateSoumission }}</td>
                                                    <td>{{ $demande->delaiTraitement ?? '0' }} jours</td>
                                                    <td>
                                                        @if ($demande->statut === 'en_attente')
                                                        <div class="btn-group" role="group">
                                                            <form action="{{ route('demandes.approve.responsable', $demande->idDemande) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success">Approuver</button>
                                                            </form>
                                                            
                                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#rejectModal{{ $demande->idDemande }}">
                                                                Rejeter
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- Rejection Modal -->
                                                        <div class="modal fade" id="rejectModal{{ $demande->idDemande }}" tabindex="-1" role="dialog">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title">Rejeter la demande</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <form action="{{ route('demandes.reject.responsable', $demande->idDemande) }}" method="POST">
                                                                        @csrf
                                                                        <div class="modal-body">
                                                                            <div class="form-group">
                                                                                <label for="commentaire">Raison du rejet:</label>
                                                                                <textarea class="form-control" id="commentaire" name="commentaire" required></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
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
                                                <tr>
                                                    <td>{{ $demande->idDemande }}</td>
                                                    <td>{{ $demande->document->titre }}</td>
                                                    <td>{{ $demande->utilisateur->nom }}</td>
                                                    <td>{{ $demande->dateValidationResponsable }}</td>
                                                    <td>{{ $demande->delaiTraitement ?? '0' }} jours</td>
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
                                                            <span class="badge badge status-{{ $demande->statut }}">Soumise</span>
                                                        @elseif($demande->statut == 'approuvé_responsable')
                                                            <span class="badge badge status-{{ $demande->statut }}">Validée par responsable</span>
                                                        @elseif($demande->statut == 'approuvé_archiviste')
                                                            <span class="badge badge status-{{ $demande->statut }}">Prête pour récupération</span>
                                                        @elseif($demande->statut == 'refusé_responsable||refusé_archiviste')
                                                            <span class="badge badge status-{{ $demande->statut }}">Rejetée</span>
                                                        @elseif($demande->statut == 'récupéré')
                                                            <span class="badge badge status-{{ $demande->statut }}">récupéré</span>
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
                labels: ['Soumise', 'Validée Responsable', 'Validée Archiviste', 'Rejetée', 'Terminée'],
                datasets: [{
                    data: [
                        {{ $stats['statusDistribution']['soumise'] ?? 0 }},
                        {{ $stats['statusDistribution']['validee_responsable'] ?? 0 }},
                        {{ $stats['statusDistribution']['validee_archiviste'] ?? 0 }},
                        {{ $stats['statusDistribution']['rejetee'] ?? 0 }},
                        {{ $stats['statusDistribution']['terminee'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#17a2b8', // info
                        '#007bff', // primary
                        '#28a745', // success
                        '#dc3545', // danger
                        '#6c757d'  // secondary
                    ]
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'bottom'
                }
            }
        });
        
        // Processing time chart
        var timeCtx = document.getElementById('processingTimeChart').getContext('2d');
        var timeChart = new Chart(timeCtx, {
            type: 'bar',
            data: {
                labels: ['Responsable', 'Archiviste', 'Total'],
                datasets: [{
                    label: 'Temps moyen (jours)',
                    data: [
                        {{ $stats['avgProcessingTimes']['responsable'] ?? 0 }},
                        {{ $stats['avgProcessingTimes']['archiviste'] ?? 0 }},
                        {{ $stats['avgProcessingTimes']['total'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#007bff',
                        '#28a745',
                        '#17a2b8'
                    ]
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                responsive: true,
                legend: {
                    display: false
                }
            }
        });
    }
});
</script>
@endpush
@endsection