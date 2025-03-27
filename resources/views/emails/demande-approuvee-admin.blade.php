@component('mail::message')
# le demande de document a été approuvée par l'archiviste 
la demande de document " <strong> {{ $demande->document->titre }}" </strong> a été approuvée par l'archiviste 
<strong>{{ App\Models\User::find($demande->idArchiviste)->nom }}</strong>

 Veuillez trouver ci-joint le certificat d'approbation.

**Détails de la demande:**

* **Document:** {{ $demande->document->titre }}
* **Description:** {{ $demande->description }}
* **Date de soumission:** {{ ( new DateTime($demande->dateSoumission))->format('d/m/Y H:i') }}
* **Date de Récuperation:** {{ ( new DateTime($demande->dateRecuperation))->format('d/m/Y H:i') }}
* **L'archiviste:** {{ App\Models\User::find($demande->idArchiviste)->nom }}

@component('mail::button', ['url' => route('certificats.show', $certificat->idCertificat)])
Voir le Certificat
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent