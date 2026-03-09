<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuiviPostIndividuel extends Model
{
    use HasFactory;

    protected $table = 'suivi_post_individuels';

    protected $fillable = [
        'individuelles_id',
        'situation_actuelle',
        'temps_emploi',
        'entreprise',
        'secteur',
        'lien_formation',
        'revenu',
        'formation_marche',
        'competences_utilisees',
        'recommande',
        'difficultes',
        'besoins',
        'diplome_retire',
        'commentaires',
    ];

    protected $casts = [
        'difficultes' => 'array',
        'besoins' => 'array',
        'diplome_retire' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function individuelle()
    {
        return $this->belongsTo(Individuelle::class, 'individuelles_id');
    }
}
