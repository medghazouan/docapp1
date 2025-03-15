<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificat extends Model
{
    protected $primaryKey = 'idCertificat';

    protected $fillable = [
        'idDemande', 'idUtilisateur', 'idDocument', 'dateGeneration', 'signatureUtilisateur'
    ];

    protected $dates = [
        'dateGeneration'
    ];

    public function demande()
    {
        return $this->belongsTo(DemandeDocument::class, 'idDemande');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idUtilisateur');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'idDocument');
    }
}
