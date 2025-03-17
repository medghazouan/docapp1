@component('mail::message')
# Nouvelle demande de document

Une nouvelle demande de document a été soumise par {{ $demande->utilisateur->nom }}.

**Détails de la demande:**

* **Document:** {{ $demande->document->titre }}
* **Description:** {{ $demande->description }}
* **Date de soumission:** {{ (new DateTime( $demande->dateSoumission))->format('d/m/Y H:i') }}

Veuillez traiter cette demande dès que possible.

@component('mail::button', ['url' => route('demandes.show', $demande->idDemande)])
Voir la demande
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent