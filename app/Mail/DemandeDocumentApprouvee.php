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
    public $viewType;

    public function __construct(DemandeDocument $demande, Certificat $certificat, $viewType = 'user')
    {
        $this->demande = $demande;
        $this->certificat = $certificat;
        $this->viewType = $viewType;
    }

    public function build()
    {
        switch ($this->viewType) {
            case 'admin':
                return $this->markdown('emails.demande-approuvee-admin')
                            ->subject('Nouvelle demande de document approuvée');
            
            case 'user':
            default:
                return $this->markdown('emails.demande-approuvee')
                            ->subject('Votre demande de document a été approuvée');
        }
    }
}