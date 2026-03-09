<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuiviPostGroupement extends Model
{
    use HasFactory;

    protected $table = 'suivi_post_groupements';

    protected $fillable = [
        'projets_id',
        'activite_principale',
        'nombre_membres',
        'nombre_femmes',
        'nombre_hommes',
        'application_acquis',
        'activites_developpees',
        'augmentation_production',
        'amelioration_qualite',
        'nouveaux_marches',
        'emplois_crees',
        'augmentation_revenus',
        'difficultes',
        'besoins',
        'satisfaction',
        'recommandation',
        'attestation_retiree',
        'commentaires',
    ];

    protected $casts = [
        'augmentation_production' => 'boolean',
        'amelioration_qualite' => 'boolean',
        'nouveaux_marches' => 'boolean',
        'augmentation_revenus' => 'boolean',
        'attestation_retiree' => 'boolean',
        'difficultes' => 'array',
        'besoins' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

}