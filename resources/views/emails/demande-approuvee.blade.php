@component('mail::message')
# Votre demande de document a été approuvée

Votre demande de document "{{ $demande->document->titre }}" a été approuvée. par l'archiviste 
<strong>{{ App\Models\User::find($demande->idArchiviste)->nom }}</strong>

Vous pouvez récupérer le document. Veuillez trouver ci-joint le certificat d'approbation.

**Détails de la demande:**

* **Document:** {{ $demande->document->titre }}
* **Description:** {{ $demande->description }}
* **Date de soumission:** {{ ( new DateTime($demande->dateSoumission))->format('d/m/Y H:i') }}
* **Date de Récuperation:** {{ ( new DateTime($demande->dateRecuperation))->format('d/m/Y H:i') }}

### Instructions :
1. Veuillez vous présenter au bureau des archives pour récupérer votre document.
2. N'oubliez pas de signer le certificat lors de la récupération.
3. Respectez la date limite de retour qui sera  mentionnée aprés.

@component('mail::button', ['url' => route('certificats.show', $certificat->idCertificat)])
Voir le Certificat
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent