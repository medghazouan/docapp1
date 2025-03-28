<?php
namespace App\Mail;

use App\Models\DemandeDocument;
use App\Models\Certificat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon; // Ajoutez cette importation pour formater les dates

class DemandeDocumentApprouvee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $certificat;
    public $viewType;
    public $dateRecuperation; // Ajoutez cette propriété

    public function __construct(DemandeDocument $demande, Certificat $certificat, $viewType = 'user')
    {
        $this->demande = $demande;
        $this->certificat = $certificat;
        $this->viewType = $viewType;
        
        // Formatez la date de récupération
        $this->dateRecuperation = $demande->dateRecuperation 
            ? Carbon::parse($demande->dateRecuperation)->format('d/m/Y') 
            : null;
    }

    public function build()
    {
        switch ($this->viewType) {
            case 'admin':
                return $this->markdown('emails.demande-approuvee-admin')
                            ->with([
                                'dateRecuperation' => $this->dateRecuperation
                            ])
                            ->subject('Nouvelle demande de document approuvée');
            
            case 'user':
            default:
                return $this->markdown('emails.demande-approuvee')
                            ->with([
                                'dateRecuperation' => $this->dateRecuperation
                            ])
                            ->subject('Votre demande de document a été approuvée');
        }
    }
}