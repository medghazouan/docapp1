<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificat de prêt N° {{ $certificat->idCertificat }}</title>
    
<style>
    @page { margin: 0; }
    body {
        font-family: 'Georgia', serif;
        color: #171717;
        font-size: 12px;
        margin: 0;
        padding: 40px 60px;
        background: #fff;
        min-height: 100vh;
    }
    .header {
        position: relative;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #a49672;
    }
    .logo {
        height: 80px;
        position: absolute;
        left: 0;
        top: 0;
    }
    .title-box {
        text-align: center;
        margin-left: 100px;
    }
    .title {
        font-size: 22px;
        color: #a49672;
        margin: 0 0 8px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .subtitle {
        font-size: 14px;
        color: #a39c99;
        margin: 0;
    }
    .certificat-number {
        font-size: 16px;
        color: #171717;
        margin-top: 5px;
    }
    .section {
        margin: 25px 0;
        padding-left: 20px;
        border-left: 2px solid #a39c99;
    }
    .section-title {
        font-family: 'Arial', sans-serif;
        text-transform: uppercase;
        color: #a49672;
        font-size: 13px;
        letter-spacing: 1px;
        margin-bottom: 15px;
        position: relative;
        left: -20px;
    }
    .section-title:before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #a49672;
        margin-right: 10px;
    }
    .info-block {
        margin: 18px 0;
        display: grid;
        grid-template-columns: 140px 1fr;
        align-items: start;
    }
    .label {
        color: #a39c99;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
    }
    .value {
        color: #171717;
        font-size: 12px;
        padding-left: 15px;
        border-left: 1px solid #a39c99;
        margin-left: 10px;
    }
    .signature-section {
        margin-top: 40px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    .signature-box {
        padding: 20px;
        background: #f8f8f8;
        border-radius: 4px;
        text-align: center;
    }
    .signature-line {
        width: 80%;
        height: 1px;
        background: #a49672;
        margin: 30px auto 10px;
    }
    .signature-label {
        color: #a39c99;
        font-size: 10px;
        text-transform: uppercase;
    }
    .footer {
        position: absolute;
        bottom: 30px;
        left: 0;
        right: 0;
        text-align: center;
        color: #a39c99;
        font-size: 9px;
        padding-top: 20px;
        border-top: 1px solid #a39c99;
    }
    .highlight {
        color: #a49672;
        font-weight: bold;
    }
</style>
</head>
<body>
    <div class="header">
        <img src="{{ asset('images/logo.png') }}" class="logo" >
        <div class="title-box">
            <h1 class="title">Certificat de Prêt</h1>
            <p class="subtitle">Document Officiel</p>
            <div class="certificat-number">N° {{ $certificat->idCertificat }}</div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Informations Principales</div>
        <div class="info-block">
            <div class="label">Date de Génération</div>
            <div class="value">{{ (new DateTime($certificat->dateGeneration))->format('d/m/Y H:i') }}</div>
        </div>
        <div class="info-block">
            <div class="label">Utilisateur</div>
            <div class="value">
                <span class="highlight">{{ $certificat->utilisateur->nom }}</span><br>
                {{ $certificat->utilisateur->fonction ?: 'Non spécifié' }}
            </div>
        </div>
        <div class="info-block">
            <div class="label">Document</div>
            <div class="value">
                <span class="highlight">{{ $certificat->document->titre }}</span><br>
                {{ $certificat->document->type }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Détails du Document</div>
        <div class="info-block">
            <div class="label">Direction/Service</div>
            <div class="value">
                {{ $certificat->document->direction ?: 'Non spécifié' }}<br>
                {{ $certificat->document->service ?: 'Non spécifié' }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Chronologie</div>
        <div class="info-block">
            <div class="label">Dates Clés</div>
            <div class="value">
                <div> Soumission : {{ (new DateTime($certificat->demande->dateSoumission))->format('d/m/Y H:i') }}</div>
                <div> Responsable : {{ $certificat->demande->dateValidationResponsable ? (new DateTime($certificat->demande->dateValidationResponsable))->format('d/m/Y H:i') : 'Non applicable' }}</div>
                <div> Archiviste : {{ $certificat->demande->dateValidationArchiviste ? (new DateTime($certificat->demande->dateValidationArchiviste))->format('d/m/Y H:i') : 'Non applicable' }}</div>
                <div> Récupération : {{ $certificat->demande->dateRecuperation ? (new DateTime($certificat->demande->dateRecuperation))->format('d/m/Y H:i') : 'Non récupéré' }}</div>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-label">Utilisateur</div>
            <div class="signature-line"></div>
            <div class="signature-label">Signature & Cachet</div>
        </div>
        <div class="signature-box">
            <div class="signature-label">Archiviste</div>
            <div class="signature-line"></div>
            <div class="signature-label">Signature & Cachet</div>
        </div>
    </div>

    <div class="footer">
        <p>Certificat généré électroniquement - Validité soumise aux conditions d'emprunt<br>
        {{ $certificat->signatureUtilisateur ? 'Signé numériquement le ' . (new DateTime($certificat->demande->dateRecuperation))->format('d/m/Y') : 'En attente de signature' }}</p>
    </div>
</body>
</html>