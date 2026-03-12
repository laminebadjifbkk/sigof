<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiviteQuotidienne extends Model
{
    use HasFactory;

    protected $table = 'activitequotidiennes';

    protected $fillable = [
        'titre',
        'description',
        'user_id',
        'date_activite',
        'statut',
        'priorite',
        'alerte_envoyee',
        'heure_debut',
        'heure_fin',
        'validateur_id',
        'date_validation'
    ];

    protected $casts = [
        'date_activite' => 'date',
        'date_validation' => 'datetime',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'alerte_envoyee' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | CONSTANTES
    |--------------------------------------------------------------------------
    */

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_TERMINEE = 'terminee';
    const STATUT_VALIDEE = 'validee';
    const STATUT_REJETE = 'rejete';
    const STATUT_RETARD = 'retard';

    const PRIORITE_FAIBLE = 'faible';
    const PRIORITE_NORMALE = 'normale';
    const PRIORITE_URGENTE = 'urgente';

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    public function alertes()
    {
        return $this->hasMany(AlerteActivite::class, 'activitequotidienne_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES UTILES
    |--------------------------------------------------------------------------
    */

    public function scopeAujourdHui($query)
    {
        return $query->whereDate('date_activite', now());
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', self::STATUT_EN_COURS);
    }

    public function scopeNonValidees($query)
    {
        return $query->where('statut', '!=', self::STATUT_VALIDEE);
    }

}