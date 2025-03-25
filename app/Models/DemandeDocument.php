<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class DemandeDocument extends Model
{
    protected $primaryKey = 'idDemande';

    protected $fillable = [
        'idUtilisateur', 'idResponsableService', 'idArchiviste', 'idDocument',
        'description', 'statut', 'dateSoumission', 'dateValidationResponsable',
        'dateValidationArchiviste', 'dateRecuperation', 'dateRetour'
    ];

    protected $dates = [
        'dateSoumission', 'dateValidationResponsable', 'dateValidationArchiviste', 
        'dateRecuperation', 'dateRetour'
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

    // New accessor to check if the document is overdue
    public function getIsOverdueAttribute()
    {
        return $this->statut === 'récupéré' && 
               $this->dateRetour !== null && 
               Carbon::now()->greaterThan($this->dateRetour);
    }

    // Scope to find overdue documents
    public function scopeOverdue($query)
    {
        return $query->where('statut', 'récupéré')
                     ->whereNotNull('dateRetour')
                     ->where('dateRetour', '<', now());
    }
}
