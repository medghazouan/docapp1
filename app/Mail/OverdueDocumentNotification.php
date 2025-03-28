<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OverdueDocumentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $recipientType;

    public function __construct($demande, $recipientType)
    {
        $this->demande = $demande;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $subject = 'Document en retard';
        
        if ($this->recipientType == 'user') {
            $subject = 'Votre document est en retard';
        } elseif ($this->recipientType == 'archiviste') {
            $subject = 'Document non retourné';
        }

        return $this->subject($subject)
            ->view('emails.overdue-document')
            ->with([
                'demande' => $this->demande,
                'recipientType' => $this->recipientType
            ]);
    }
}