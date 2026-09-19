<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpTiers extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'onfp_tiers';

    protected $fillable = [
        'type',
        'nom',
        'organisation',
        'fonction',
        'telephone',
        'email',
        'adresse',
        'observation',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    /**
     * Types de tiers courants (à adapter selon vos besoins réels).
     */
    public const TYPES = [
        'partenaire'   => 'Partenaire',
        'prestataire'  => 'Prestataire',
        'institution'  => 'Institution',
        'expert'       => 'Expert / Consultant',
        'autre'        => 'Autre',
    ];

    /**
     * Lignes de la table pivot associant ce tiers à des activités.
     */
    public function activiteTiers()
    {
        return $this->hasMany(OnfpActiviteTiers::class, 'tier_id');
    }

    /**
     * Accès direct aux activités (à travers le pivot).
     */
    public function activites()
    {
        return $this->belongsToMany(
            OnfpActivite::class,
            'onfp_activite_tiers',
            'tier_id',
            'activite_id'
        )
            ->withPivot('role', 'observation')
            ->withTimestamps();
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}
