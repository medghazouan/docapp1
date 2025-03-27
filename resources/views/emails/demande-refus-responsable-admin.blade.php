@component('mail::message')
# un demande de document a été refusé par le responsable

le demande de document "{{ $demande->document->titre }}" a été refusé par 
le responsable : <strong>{{ App\Models\User::find($demande->idResponsableService)->nom }}</strong>

Vous pouvez trouver ci-joint le certificat de refus.

**Détails de la demande:**

* **Document:** {{ $demande->document->titre }}
* **Description:** {{ $demande->description }}
* **Responsable:** {{ App\Models\User::find($demande->idResponsableService)->nom }}
* **Date de soumission:** {{ ( new DateTime($demande->dateSoumission))->format('d/m/Y H:i') }}
* **Motif:**  {{$demande->commentaire }} 
@component('mail::button', ['url' => route('certificats.show', $certificat->idCertificat)])
Voir le Certificat
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent