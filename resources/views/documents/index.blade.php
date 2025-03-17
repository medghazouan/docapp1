@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Documents') }}</h5>
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'archiviste')
        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nouveau document
        </a>
        @endif
    </div>
    <div class="card-body">
        @if($documents->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Service</th>
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
                        <td>{{ $document->service ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $document->statut == 'disponible' ? 'success' : ($document->statut == 'emprunté' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($document->statut) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Document actions">
                                <a href="{{ route('documents.show', $document->idDocument) }}" class="btn btn-sm btn-info me-1" title="Voir le document">
                                  <i class="fas fa-eye"></i> Voir
                                </a>
                              
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'archiviste')
                                  <a href="{{ route('documents.edit', $document->idDocument) }}" class="btn btn-sm btn-primary me-1" title="Modifier le document">
                                    <i class="fas fa-edit"></i> Modifier
                                  </a>
                                  <form action="{{ route('documents.destroy', $document->idDocument) }}" method="POST" class="d-inline me-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer le document" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                      <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                  </form>
                                @endif
                              
                                @if($document->statut == 'disponible')
                                  <a href="{{ route('demandes.create', ['document' => $document->idDocument]) }}" class="btn btn-sm btn-success" title="Demander le document">
                                    <i class="fas fa-file"></i> Demander
                                  </a>
                                @endif
                              </div>    
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $documents->links() }}
        </div>
        @else
        <div class="alert alert-info">
            Aucun document trouvé.
        </div>
        @endif
    </div>
</div>
@endsection