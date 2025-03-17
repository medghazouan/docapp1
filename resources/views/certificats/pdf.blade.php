<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de prêt N° {{ $certificat->idCertificat }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .section {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .row {
            display: flex;
            margin-bottom: 10px;
        }
        .col {
            flex: 1;
        }
        .label {
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .signature-box {
            margin-top: 40px;
            border: 1px solid #333;
            padding: 15px;
        }
        .signature-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 60px;
        }
        .signature-line {
            margin-top: 10px;
            border-top: 1px solid #333;
            width: 200px;
            display: inline-block;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>CERTIFICAT DE PRÊT DE DOCUMENT</h1>
            <h3>N° {{ $certificat->idCertificat }}</h3>
            <p>Date de génération: {{ (new DateTime($certificat->dateGeneration) )->format('d/m/Y H:i') }}</p>
        </div>

        <div class="section">
            <div class="section-title">INFORMATION SUR L'UTILISATEUR</div>
            <div class="row">
                <div class="col">
                    <div><span class="label">Nom:</span> {{ $certificat->utilisateur->nom }}</div>
                    <div><span class="label">Email:</span> {{ $certificat->utilisateur->email }}</div>
                </div>
                <div class="col">
                    <div><span class="label">Fonction:</span> {{ $certificat->utilisateur->fonction ?: 'Non spécifié' }}</div>
                    <div><span class="label">Service:</span> {{ $certificat->utilisateur->service ?: 'Non spécifié' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">INFORMATION SUR LE DOCUMENT</div>
            <div class="row">
                <div class="col">
                    <div><span class="label">Titre:</span> {{ $certificat->document->titre }}</div>
                    <div><span class="label">Type:</span> {{ $certificat->document->type }}</div>
                </div>
                <div class="col">
                    <div><span class="label">Direction:</span> {{ $certificat->document->direction ?: 'Non spécifié' }}</div>
                    <div><span class="label">Service:</span> {{ $certificat->document->service ?: 'Non spécifié' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">INFORMATION SUR LA DEMANDE</div>
            <div class="row">
                <div class="col">
                    <div><span class="label">Date de soumission:</span> {{ (new DateTime($certificat->demande->dateSoumission))->format('d/m/Y H:i') }}</div>
                    <div><span class="label">Date d'approbation (Responsable):</span> 
                        {{ (new DateTime($certificat->demande->dateValidationResponsable) ) ? (new DateTime($certificat->demande->dateValidationResponsable) )->format('d/m/Y H:i') : 'Non applicable' }}
                    </div>
                </div>
                <div class="col">
                    <div><span class="label">Date d'approbation (Archiviste):</span> 
                        {{ (new DateTime($certificat->demande->dateValidationArchiviste) ) ? (new DateTime($certificat->demande->dateValidationArchiviste) )->format('d/m/Y H:i') : 'Non applicable' }}
                    </div>
                    <div><span class="label">Date de récupération:</span> 
                        {{ (new DateTime($certificat->demande->dateRecuperation) ) ? (new DateTime($certificat->demande->dateRecuperation) )->format('d/m/Y H:i') : 'Non récupéré' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">DESCRIPTION DE LA DEMANDE</div>
            <p>{{ $certificat->demande->description }}</p>
        </div>

        <div class="section">
            <div class="section-title">APPROBATIONS</div>
            <div class="row">
                <div class="col">
                    <div><span class="label">Responsable:</span> {{ $certificat->demande->responsable->nom }}</div>
                </div>
                <div class="col">
                    <div><span class="label">Archiviste:</span> {{ $certificat->demande->archiviste->nom }}</div>
                </div>
            </div>
        </div>

        <div class="signature-box">
            <div class="signature-title">SIGNATURE DE L'UTILISATEUR</div>
            <div class="row">
                <div class="col">
                    <div class="signature-line">Signature de l'utilisateur</div>
                </div>
                <div class="col">
                    <div class="signature-line">Signature de l'archiviste</div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Ce certificat atteste que l'utilisateur mentionné ci-dessus a été autorisé à emprunter le document spécifié.</p>
            <p>Document généré le {{ (new DateTime($certificat->dateGeneration) )->format('d/m/Y') }} à {{ (new DateTime($certificat->dateGeneration) )->format('H:i') }}</p>
            @if($certificat->signatureUtilisateur)
                <p><strong>Document signé par l'utilisateur le {{ (new DateTime($certificat->demande->dateRecuperation) )->format('d/m/Y') }}</strong></p>
            @else
                <p><strong>En attente de signature par l'utilisateur</strong></p>
            @endif
        </div>
    </div>
</body>
</html>