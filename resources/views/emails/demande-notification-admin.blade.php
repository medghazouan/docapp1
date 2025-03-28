@component('mail::message')
# Nouvelle demande de document

Une nouvelle demande de document a été soumise par <strong>{{ $demande->utilisateur->nom }}</strong> , en attend de traitement
par le responsable : <strong>{{ App\Models\User::find($demande->idResponsableService)->nom }}</strong>

**Détails de la demande:**

* **Document:** {{ $demande->document->titre }}
* **Description:** {{ $demande->description }}
* **Demandeur:** {{ Auth::user()->nom }}
* **Responsable:**  {{ App\Models\User::find($demande->idResponsableService)->nom }}
* **Date de soumission:** {{ (new DateTime( $demande->dateSoumission))->format('d/m/Y H:i') }}


@component('mail::button', ['url' => route('demandes.show', $demande->idDemande)])
Voir la demande
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent

