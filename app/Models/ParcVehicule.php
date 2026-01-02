<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcVehicule extends Model
{
    protected $table = 'parc_vehicules';
    protected $fillable = [
        'immatriculation',
        'marque',
        'modele',
        'annee',
        'categorie',
        'energie',
        'consommation_moyenne',
        'capacite_reservoir',
        'kilometrage_actuel',
        'etat',
        'assurance_expire_le',
        'visite_technique_expire_le',
        'chauffeur_id',

    ];


    protected $casts = [
        'chauffeur_id' => 'integer',
        'assurance_expire_le' => 'date',
        'visite_technique_expire_le' => 'date',
    ];

    /* public function missions()
    {
        return $this->hasMany(ParcMission::class, 'vehicule_id');
    } */
    public function pleins()
    {
        return $this->hasMany(ParcPlein::class, 'vehicule_id');
    }
    public function maintenances()
    {
        return $this->hasMany(ParcMaintenance::class, 'vehicule_id');
    }
    public function affectations()
    {
        return $this->hasMany(ParcAffectation::class, 'vehicule_id');
    }

    public function chauffeur()
    {
        return $this->belongsTo(ParcChauffeur::class, 'chauffeur_id');
    }

    public function getAssuranceExpireLeFormattedAttribute()
    {
        return $this->assurance_expire_le ? $this->assurance_expire_le->format('Y-m-d') : '';
    }

    public function getVisiteTechniqueExpireLeFormattedAttribute()
    {
        return $this->visite_technique_expire_le ? $this->visite_technique_expire_le->format('Y-m-d') : '';
    }

    public function missions()
    {
        return $this->belongsToMany(
            ParcMission::class,
            'parc_mission_vehicules',
            'vehicule_id',
            'mission_id'
        )->withPivot('chauffeur_id');
    }
}
