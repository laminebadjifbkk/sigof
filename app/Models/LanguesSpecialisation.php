<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguesSpecialisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'postes_disponibles',
        'niveau_langue_requis',
        'niveau_francais_requis',
        'diplome_minimum',
        'certification_recommandee',
    ];

    protected $casts = [
        'postes_disponibles' => 'integer',
    ];

    /**
     * Les candidatures rattachées à cette spécialisation (LV1).
     */
    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class, 'langue_specialisation_id');
    }
}