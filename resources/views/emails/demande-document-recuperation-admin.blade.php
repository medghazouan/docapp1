@component('mail::message')
## Notification de récupération de document

Bonjour ,

Un document a été récupéré par <strong>{{ $demande->utilisateur->nom }}</strong>

### Détails de la récupération :

- **Utilisateur :** {{ $demande->utilisateur->nom }}
- **l'archiviste :** {{ App\Models\User::find($demande->idArchiviste)->nom }}
- **Responsable :** {{ App\Models\User::find($demande->idResponsableService)->nom }}
- **Document :** {{ $document->titre }}
- **Type de document :** {{ $document->type }}
- * **Date de Récuperation:** {{  $demande->dateRecuperation->format('d/m/Y H:i') }}
- * **Date Retour:** {{  $demande->dateRetour->format('d/m/Y H:i') }}


### Informations supplémentaires :
- **Service :** Archives
- **Statut du document :** Emprunté

@component('mail::button', ['url' => route('certificats.show', $certificat->idCertificat)])
Voir le Certificat
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent