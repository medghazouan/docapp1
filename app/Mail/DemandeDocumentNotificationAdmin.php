<?php
namespace App\Mail;

use App\Models\DemandeDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;



class DemandeDocumentNotificationAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;

    public function __construct(DemandeDocument $demande)
    {
        $this->demande = $demande;
    }

    public function build()
    {
        return $this->markdown('emails.demande-notification-admin')
                    ->subject('Nouvelle demande de document ');
    }
}