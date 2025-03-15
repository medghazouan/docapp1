<?php
namespace App\Mail;

use App\Models\DemandeDocument;
use App\Models\Certificat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DemandeDocumentApprouvee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $certificat;

    public function __construct(DemandeDocument $demande, Certificat $certificat)
    {
        $this->demande = $demande;
        $this->certificat = $certificat;
    }

    public function build()
    {
        return $this->markdown('emails.demande-approuvee')
                    ->subject('Votre demande de document a été approuvée');
    }
}