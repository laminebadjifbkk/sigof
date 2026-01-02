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
        'permis_expire_le' => 'date',
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

    public function missions()
    {
        return $this->hasManyThrough(
            ParcMission::class,
            ParcMissionVehicule::class,
            'chauffeur_id',
            'id',
            'id',
            'mission_id'
        );
    }

    public function getPermisRestantAttribute(): string
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
    }
}
