<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcMission extends Model
{
    protected $table = 'parc_missions';
    protected $fillable = [
        'reference',
        'vehicule_id',
        'chauffeur_id',
        'objet',
        'lieu_depart',
        'lieu_arrivee',
        'date_depart',
        'date_retour',
        'distance_km',
        'indemnites_total',
        'statut'
    ];

    protected $casts = [
        'date_depart' => 'date',
        'date_retour' => 'date',
    ];

    public function vehicule()
    {
        return $this->belongsTo(ParcVehicule::class, 'vehicule_id');
    }
    public function chauffeur()
    {
        return $this->belongsTo(ParcChauffeur::class, 'chauffeur_id');
    }
    public function depenses()
    {
        return $this->hasMany(ParcDepense::class, 'mission_id');
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'parc_employee_mission', 'mission_id', 'employee_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    // Accessor pour calculer le nombre de jours
    public function getNombreJoursAttribute()
    {
        $fin = $this->date_retour ?? now();
        return $this->date_depart->diffInDays($fin) + 1;
    }
}
