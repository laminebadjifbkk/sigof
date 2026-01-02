<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ParcMissionVehicule extends Pivot
{
    protected $table = 'parc_mission_vehicules';

    protected $fillable = [
        'mission_id',
        'vehicule_id',
        'chauffeur_id',
    ];

    /**
     * Le véhicule associé à cette mission
     */
    public function vehicule()
    {
        return $this->belongsTo(ParcVehicule::class, 'vehicule_id');
    }

    /**
     * Le chauffeur assigné à ce véhicule pour la mission
     */
    public function chauffeur()
    {
        return $this->belongsTo(ParcChauffeur::class, 'chauffeur_id');
    }

    /**
     * La mission associée
     */
    public function mission()
    {
        return $this->belongsTo(ParcMission::class, 'mission_id');
    }
}
