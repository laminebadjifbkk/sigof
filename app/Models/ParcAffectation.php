<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcAffectation extends Model
{
    protected $table = 'parc_affectations';

    protected $fillable = ['vehicule_id', 'chauffeur_id', 'date_debut', 'date_fin'];

    protected $casts = ['date_debut' => 'date', 'date_fin' => 'date'];

    public function vehicule()
    {
        return $this->belongsTo(ParcVehicule::class, 'vehicule_id');
    }
    public function chauffeur()
    {
        return $this->belongsTo(ParcChauffeur::class, 'chauffeur_id');
    }
}
