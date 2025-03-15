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
}
