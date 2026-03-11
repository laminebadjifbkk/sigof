<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlerteActivite extends Model
{
    use HasFactory;

    protected $table = 'alertes_activites';

    protected $fillable = [
        'activitequotidienne_id',
        'envoye',
        'date_alerte'
    ];

    protected $casts = [
        'date_alerte' => 'datetime',
        'envoye' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function activite()
    {
        return $this->belongsTo(ActiviteQuotidienne::class, 'activitequotidienne_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES UTILES
    |--------------------------------------------------------------------------
    */

    public function scopeNonEnvoyees($query)
    {
        return $query->where('envoye', false);
    }

    public function scopeAEnvoyer($query)
    {
        return $query->where('envoye', false)
                     ->where('date_alerte', '<=', now());
    }
}