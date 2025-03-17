@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Notifications') }}</h5>
        @if($notifications->where('read', false)->count() > 0)
            <form action="{{ route('notifications.read.all') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm">
                    <i class="fas fa-check-double"></i> Marquer tout comme lu
                </button>
            </form>
        @endif
    </div>
    <div class="card-body">
        @if($notifications->count() > 0)
            <div class="list-group">
                @foreach($notifications as $notification)
                    <div class="list-group-item list-group-item-action {{ $notification->read ? '' : 'list-group-item-primary' }} d-flex justify-content-between align-items-center">
                        <div class="d-flex w-100 justify-content-between">
                            <div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-bell me-2 {{ $notification->read ? 'text-muted' : 'text-primary' }}"></i>
                                    <p class="mb-1">{{ $notification->message }}</p>
                                </div>
                                <small class="text-muted">{{ (new DateTime( $notification->dateEnvoi))->format('d/m/Y H:i') }}</small>
                            </div>
                            @if(!$notification->read)
                                <form action="{{ route('notifications.read', $notification->idNotification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-check"></i> Marquer comme lu
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-secondary">Lu</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Vous n'avez aucune notification.
            </div>
        @endif
    </div>
</div>
@endsection