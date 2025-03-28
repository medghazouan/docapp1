<?php
namespace App\Mail;

use App\Models\DemandeDocument;
use App\Models\Certificat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeDocumentRefus extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $certificat;
    public $commentaire;
    public $viewType;
    public $refusePar;

    public function __construct(
        DemandeDocument $demande, 
        Certificat $certificat, 
        $commentaire = null, 
        $refusePar = 'responsable', 
        $viewType = 'user'
    ) {
        $this->demande = $demande;
        $this->certificat = $certificat;
        $this->commentaire = $commentaire;
        $this->refusePar = $refusePar;
        $this->viewType = $viewType;
    }

    public function build()
    {
        // Construire dynamiquement le nom de la vue et le sujet
        $viewName = "emails.demande-refus-{$this->refusePar}-{$this->viewType}";
        $subject = $this->refusePar === 'responsable' 
            ? 'Refus de  demande par le responsable' 
            : 'Refus de demande par l\'archiviste';

        return $this->markdown($viewName)
                    ->subject($subject);
    }
}