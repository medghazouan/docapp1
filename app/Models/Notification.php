<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $primaryKey = 'idNotification';

    protected $fillable = [
        'idDestinataire', 'message', 'dateEnvoi', 'read'
    ];

    protected $dates = [
        'dateEnvoi'
    ];

    public function destinataire()
    {
        return $this->belongsTo(User::class, 'idDestinataire');
    }

    // Dans ton modèle Demande
    public function document()
    {
        return $this->belongsTo(Document::class, 'idDocument');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idUtilisateur');
    }
   
    public function demande()
    {
        return $this->belongsTo(DemandeDocument::class, 'idDemande');
    }
}
