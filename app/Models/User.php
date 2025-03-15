<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'idUtilisateur';

    protected $fillable = [
        'nom', 'email', 'password', 'fonction', 'societe', 'direction', 'service', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function demandes()
    {
        return $this->hasMany(DemandeDocument::class, 'idUtilisateur');
    }

    public function demandesResponsable()
    {
        return $this->hasMany(DemandeDocument::class, 'idResponsableService');
    }

    public function demandesArchiviste()
    {
        return $this->hasMany(DemandeDocument::class, 'idArchiviste');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'idDestinataire');
    }
}