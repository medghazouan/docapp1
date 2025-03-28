<?php
namespace App\Mail;

use App\Models\DemandeDocument;
use App\Models\Certificat;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class DemandeDocumentApprouveRecuperation extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $certificat;
    public $document;
    public $dateRetour;
    public $viewType;

    public function __construct(
        DemandeDocument $demande, 
        Certificat $certificat, 
        Document $document,
        Carbon $dateRetour,
        $viewType = 'user'
    ) {
        $this->demande = $demande;
        $this->certificat = $certificat;
        $this->document = $document;
        $this->dateRetour = $dateRetour;
        $this->viewType = $viewType;
    }

    public function build()
    {
        switch ($this->viewType) {
            case 'admin':
                return $this->markdown('emails.demande-document-recuperation-admin')
                            ->subject('Document récupéré par un utilisateur');
            
            case 'user':
            default:
                return $this->markdown('emails.demande-document-recuperation-utilisateur')
                            ->subject('Votre document est prêt à être récupéré');
        }
    }

    
}