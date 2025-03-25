<!-- resources/views/emails/overdue-document.blade.php -->
<!DOCTYPE html>
<html>
<body>
    @if($recipientType == 'user')
        <h2>Votre document est en retard</h2>
        <p>Cher(e) {{ $demande->utilisateur->nom }},</p>
        <p>Le document "{{ $demande->document->titre }}" que vous avez emprunté est maintenant en retard.</p>
        <p>Date de retour prévue : {{ $demande->dateRetour->format('d/m/Y') }}</p>
        <p>Veuillez retourner le document dès que possible.</p>
    @elseif($recipientType == 'archiviste')
        <h2>Document non retourné</h2>
        <p>Cher(e) Archiviste,</p>
        <p>Le document "{{ $demande->document->titre }}" emprunté par {{ $demande->utilisateur->nom }} est en retard.</p>
        <p>Date de retour prévue : {{ $demande->dateRetour->format('d/m/Y') }}</p>
    @else
        <h2>Document en retard</h2>
        <p>Cher(e) Administrateur,</p>
        <p>Un document est en retard :</p>
        <p>Titre : {{ $demande->document->titre }}</p>
        <p>Emprunté par : {{ $demande->utilisateur->nom }}</p>
        <p>Date de retour prévue : {{ $demande->dateRetour->format('d/m/Y') }}</p>
    @endif
</body>
</html>