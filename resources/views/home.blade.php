@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Tableau de bord') }}</div>

                <div class="card-body">
                    <h4>Bienvenue, {{ Auth::user()->nom }}</h4>
                    <p>Rôle: <span class="badge bg-primary">{{ ucfirst(Auth::user()->role) }}</span></p>

                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-header">Documents</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $stats['total_documents'] }}</h5>
                                    <p class="card-text">Total des documents</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-header">Demandes</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $stats['total_demandes'] }}</h5>
                                    <p class="card-text">Total des demandes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-info mb-3">
                                <div class="card-header">Mes demandes</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $stats['mes_demandes'] }}</h5>
                                    <p class="card-text">Demandes soumises</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-white bg-warning mb-3">
                                <div class="card-header">En attente</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $stats['demandes_en_attente'] }}</h5>
                                    <p class="card-text">Demandes à traiter</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                                <a href="{{ route('demandes.create') }}" class="btn btn-primary me-md-2">
                                    <i class="fas fa-plus"></i> Nouvelle demande
                                </a>
                                <a href="{{ route('demandes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list"></i> Voir mes demandes
                                </a>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'archiviste')
                                    <a href="{{ route('documents.create') }}" class="btn btn-success">
                                        <i class="fas fa-file-plus"></i> Ajouter un document
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($unreadNotifications > 0)
                        <div class="alert alert-info mt-4">
                            <i class="fas fa-bell"></i> Vous avez {{ $unreadNotifications }} notification(s) non lue(s).
                            <a href="{{ route('notifications.index') }}" class="alert-link">Voir les notifications</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection