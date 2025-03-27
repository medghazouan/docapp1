@component('mail::message')
## votre document a été  récupéré

Bonjour {{ $demande->utilisateur->nom }},

Vous avez récupéré votre document <strong>{{ $document->titre }}</strong>

### Détails du document :
- **Titre :** {{ $document->titre }}
- **Type :** {{ $document->type }}
- **Archiviste :** {{ App\Models\User::find($demande->idArchiviste)->nom }}
- **Responsable :** {{ App\Models\User::find($demande->idResponsableService)->nom }}
- **Date de Récuperation:** {{  $demande->dateRecuperation->format('d/m/Y H:i') }}
- **Date de Retour:** {{  $demande->dateRetour->format('d/m/Y H:i') }}

Attention : Respectez la date limite de retour .

Si vous avez des questions, n'hésitez pas à contacter le service des archives.

@component('mail::button', ['url' => route('certificats.show', $certificat->idCertificat)])
Voir le Certificat
@endcomponent

Merci,
{{ config('app.name') }}
@endcomponent