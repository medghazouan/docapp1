<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'idDocument';

    protected $fillable = [
        'titre', 'type', 'societe', 'direction', 'service', 'statut'
    ];

    public function demandes()
    {
        return $this->hasMany(DemandeDocument::class, 'idDocument');
    }

    public function certificats()
    {
        return $this->hasMany(Certificat::class, 'idDocument');
    }
}
