<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeDocument extends Model
{
    protected $primaryKey = 'idDemande';

    protected $fillable = [
        'idUtilisateur', 'idResponsableService', 'idArchiviste', 'idDocument',
        'description', 'statut', 'dateSoumission', 'dateValidationResponsable',
        'dateValidationArchiviste', 'dateRecuperation'
    ];

    protected $dates = [
        'dateSoumission', 'dateValidationResponsable', 'dateValidationArchiviste', 'dateRecuperation'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'idUtilisateur');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'idResponsableService');
    }

    public function archiviste()
    {
        return $this->belongsTo(User::class, 'idArchiviste');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'idDocument');
    }

    public function certificat()
    {
        return $this->hasOne(Certificat::class, 'idDemande');
    }
}
