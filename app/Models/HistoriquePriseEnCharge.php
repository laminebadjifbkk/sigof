<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriquePriseEnCharge extends Model
{
    protected $table = 'historiqueprisencharges';

    protected $fillable = [
        'formulaire_id',
        'statut',
        'motif',
        'user_id'
    ];

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

