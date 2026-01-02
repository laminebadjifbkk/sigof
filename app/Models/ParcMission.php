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
        'statut',
        'departement',
        'region',
        'itineraire',
        'taux_journalier',
        'indemnite_mission',
        'frais_deplacement',
        'avance',
        'reliquat',
        'commentaires',
        'autres',
        'type_mission_id',

    ];

    protected $casts = [
        'date_depart' => 'date',
        'date_retour' => 'date',
        'distance_km' => 'integer',
        'indemnites_total' => 'decimal:2',
        'taux_journalier' => 'decimal:2',
        'indemnite_mission' => 'decimal:2',
        'frais_deplacement' => 'decimal:2',
        'avance' => 'decimal:2',
        'reliquat' => 'decimal:2',
    ];

    /* public function vehicule()
    {
        return $this->belongsTo(ParcVehicule::class, 'vehicule_id');
    }
    public function chauffeur()
    {
        return $this->belongsTo(ParcChauffeur::class, 'chauffeur_id');
    } */

    public function depenses()
    {
        return $this->hasMany(ParcDepense::class, 'mission_id');
    }

    /* public function employees()
    {
        return $this->belongsToMany(Employee::class, 'parc_employee_mission', 'mission_id', 'employee_id')
            ->withPivot('role')
            ->withTimestamps();
    } */

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'parc_employee_mission', 'mission_id', 'employee_id')
            ->withPivot('role', 'vehicule_id') // ajouter vehicule_id ici
            ->withTimestamps();
    }


    // Accessor pour calculer le nombre de jours
    public function getNombreJoursAttribute()
    {
        $fin = $this->date_retour ?? now();
        return $this->date_depart->diffInDays($fin) + 1;
    }

    public function typeMission()
    {
        return $this->belongsTo(ParcTypeMission::class, 'type_mission_id');
    }

    public function getTypeMissionLibelleAttribute()
    {
        return $this->typeMission?->libelle ?? 'Non défini';
    }

    public function vehicules()
    {
        return $this->belongsToMany(
            ParcVehicule::class,       // Modèle lié
            'parc_mission_vehicules',  // Table pivot
            'mission_id',               // Clé étrangère sur la table pivot pour ce modèle
            'vehicule_id'               // Clé étrangère sur la table pivot pour le modèle lié
        )->withPivot([
            'chauffeur_id',
            'kilometrage_depart',
            'kilometrage_retour'
        ])->withTimestamps();
    }
}
