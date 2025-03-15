@ extends('layouts.app')
@ section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Notifications') }}</h5>
        <div>
            <form action="{{ route('notifications.read.all') }}" method="POST">
                @ csrf
                <button type="submit" class="btn btn-sm btn-secondary">
                    <i class="fas fa-check-double"></i> {{ __('Marquer tout comme lu') }}
                </button>
            </form>
        </div>
    </div>
    <div class="card-body">
        @ if($notifications->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @ foreach($notifications as $notification)
                            <tr>
                                <td>
                                    @ if($notification->read)
                                        {{ $notification->message }}
                                    @ else
                                        <strong>{{ $notification->message }}</strong>
                                    @ endif
                                </td>
                                <td>{{ $notification->dateEnvoi->format('d/m/Y H:i') }}</td>
                                <td>
                                    @ if(!$notification->read)
                                        <form action="{{ route('notifications.read', $notification->idNotification) }}" method="POST">
                                            @ csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-check"></i> {{ __('Marquer comme lu') }}
                                            </button>
                                        </form>
                                    @ endif
                                </td>
                            </tr>
                        @ endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $notifications->links() }}
            </div>
        @ else
            <div class="alert alert-info">
                {{ __('Aucune notification trouvée.') }}
            </div>
        @ endif
    </div>
</div>
@ endsection