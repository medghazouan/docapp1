@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">{{ __('Nouvelle demande de document') }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('demandes.store') }}">
            @csrf
            <div class="mb-3">
                <label for="service" class="form-label">{{ __('Service') }}</label>
                <select class="form-select" id="service" name="service" required>
                    <option value="">{{ __('Sélectionner un service') }}</option>
                    @foreach($services as $service)
                        <option value="{{ $service }}">{{ $service }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="site" class="form-label">{{ __('Site') }}</label>
                <select class="form-select" id="site" name="site" required>
                    <option value="">{{ __('Sélectionner un site') }}</option>
                    @foreach($sites as $site)
                        <option value="{{ $site }}">{{ $site }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="idDocument" class="form-label">{{ __('Document') }}</label>
                <select class="form-select" id="idDocument" name="idDocument" required disabled>
                    <option value="">{{ __('Sélectionner d\'abord un service et un site') }}</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">{{ __('Description') }}</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('Soumettre la demande') }}</button>
            <a href="{{ route('demandes.index') }}" class="btn btn-secondary">{{ __('Annuler') }}</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service');
    const siteSelect = document.getElementById('site');
    const documentSelect = document.getElementById('idDocument');
    let allDocuments = [];

    // Add select2 to document select
    $(documentSelect).select2({
        placeholder: "{{ __('Rechercher un document...') }}",
        allowClear: true,
        disabled: true,
        language: {
            noResults: function() {
                return "{{ __('Aucun document trouvé') }}";
            }
        }
    });

    function updateDocuments() {
        const service = serviceSelect.value;
        const site = siteSelect.value;

        if (service && site) {
            fetch(`/api/documents?service=${service}&site=${site}`)
                .then(response => response.json())
                .then(data => {
                    allDocuments = data;
                    // Enable and update select2
                    $(documentSelect).prop('disabled', false).empty();
                    $(documentSelect).append(new Option('', '', false, false));
                    
                    allDocuments.forEach(doc => {
                        $(documentSelect).append(new Option(doc.titre, doc.idDocument, false, false));
                    });
                    
                    $(documentSelect).trigger('change');
                })
                .catch(error => console.error('Error:', error));
        } else {
            // Disable and reset select2
            $(documentSelect).prop('disabled', true).empty();
            $(documentSelect).append(new Option("{{ __('Sélectionner d\'abord un service et un site') }}", '', false, false));
            $(documentSelect).trigger('change');
        }
    }

    serviceSelect.addEventListener('change', updateDocuments);
    siteSelect.addEventListener('change', updateDocuments);
});
</script>
@endpush
@endsection