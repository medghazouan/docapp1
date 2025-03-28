<?php
namespace App\Mail;

use App\Models\DemandeDocument;
use App\Models\Certificat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeDocumentRefusToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $certificat;
    public $commentaire;

    public function __construct(DemandeDocument $demande, Certificat $certificat,$commentaire = null)
    {
        $this->demande = $demande;
        $this->certificat = $certificat;
        $this->commentaire = $commentaire;
    }

    public function build()
    {
        return $this->markdown('demande-refus-admin')
                    ->subject('Demande Refus');
    }
}