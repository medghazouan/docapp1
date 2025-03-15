@component('mail::message')
# Votre demande de document a été approuvée

Votre demande de document "{{ $demande->document->titre }}" a été approuvée.

Vous pouvez récupérer le document. Veuillez trouver ci-joint le certificat d'approbation.

**Détails de la demande:**

* **Document:** {{ $demande->document->titre }}
* **Description:** {{ $demande->description }}
* **Date de soumission:** {{ $demande->dateSoumission->format('d/m/Y H:i') }}

@component('mail::button', ['url' => route('certificats.show', $certificat->idCertificat)])
Voir le Certificat
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent