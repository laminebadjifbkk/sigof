<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ParcChauffeur extends Model
{
    protected $table = 'parc_chauffeurs';
    protected $fillable = [
        'user_id',
        'employee_id',
        'matricule',
        'nom',
        'prenom',
        'telephone',
        'statut',
        'permis_numero',
        'permis_categories',
        'permis_expire_le'
    ];

    protected $casts = [
        'permis_expire_le' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    /* public function missions()
    {
        return $this->hasMany(ParcMission::class, 'chauffeur_id');
    } */
    public function affectations()
    {
        return $this->hasMany(ParcAffectation::class, 'chauffeur_id');
    }
    public function vehicules()
    {
        return $this->hasMany(ParcVehicule::class, 'chauffeur_id');
    }

    public function getPermisExpireLeFormattedAttribute()
    {
        return $this->permis_expire_le ? $this->permis_expire_le->format('Y-m-d') : '';
    }

    /* public function missions()
    {
        return $this->hasManyThrough(
            ParcMission::class,
            ParcMissionVehicule::class,
            'chauffeur_id',
            'id',
            'id',
            'mission_id'
        );
    } */

/*     public function missions()
    {
        return $this->belongsToMany(
            ParcMission::class,       // Modèle lié
            'parc_employee_mission',  // Table pivot réelle
            'employee_id',            // Clé locale dans la table pivot (employé/chauffeur)
            'mission_id'              // Clé étrangère vers la table ParcMission
        )->withTimestamps();
    } */



    /* public function getPermisRestantAttribute(): string
    {
        // Permis non renseigné
        if (is_null($this->permis_expire_le)) {
            return '-';
        }

        // Permis déjà expiré
        if ($this->permis_expire_le->isPast()) {
            return 'Permis expiré';
        }

        $diff = now()->diff($this->permis_expire_le);

        $parts = [];

        if ($diff->y > 0) {
            $parts[] = $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
        }

        if ($diff->m > 0) {
            $parts[] = $diff->m . ' mois';
        }

        // Toujours afficher les jours
        $parts[] = $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');

        return implode(' ', $parts);
    } */

    public function getPermisRestantAttribute(): string
    {
        if (is_null($this->permis_expire_le)) {
            return '-';
        }

        if ($this->permis_expire_le->isPast()) {
            return 'Permis expiré';
        }

        $diff = now()->diff($this->permis_expire_le);

        $parts = [];
        if ($diff->y > 0) {
            $parts[] = $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
        }
        if ($diff->m > 0) {
            $parts[] = $diff->m . ' mois';
        }
        $parts[] = $diff->d . ' jour' . ($diff->d > 1 ? 's' : '');

        return implode(' ', $parts);
    }

    public function getPermisClasseAttribute(): string
    {
        if (is_null($this->permis_expire_le)) {
            return '';
        }

        if ($this->permis_expire_le->isPast()) {
            return 'permis-expire';
        }

        $joursRestants = now()->diffInDays($this->permis_expire_le, false);

        if ($joursRestants < 30) {
            return 'permis-bientot';
        }

        return 'permis-ok';
    }

    public function chauffeurs()
    {
        return $this->belongsToMany(
            ParcChauffeur::class,       // modèle cible
            'parc_employee_mission',    // table pivot existante
            'mission_id',               // clé locale dans la table pivot (mission)
            'employee_id'               // clé étrangère vers l’employé/chauffeur
        )->whereHas('employee')         // s'assure que ce sont des chauffeurs
            ->withPivot('role')
            ->withTimestamps();
    }

    public function missions()
    {
        return $this->belongsToMany(
            ParcMission::class,
            'parc_employee_mission',
            'employee_id',   // clé locale (chauffeur/employee)
            'mission_id'     // clé étrangère vers ParcMission
        )->withPivot('role')->withTimestamps();
    }
}
