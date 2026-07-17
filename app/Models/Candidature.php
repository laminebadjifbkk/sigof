<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'users_id',
        'langue_specialisation_id',

        // Étape 2 — Langues
        'certification_obtenue',
        'diplome',
        'langue_maternelle',
        'niveau_francais',
        'langue_vivante_2',

        // Étape 3 — Disponibilité et affectation
        'disponible_debut',
        'disponible_fin',
        'zone',
        'delegation_souhaitee',

        // Étape 4 — Documents
        'piece_identite_path',
        'diplome_fichier_path',
        'certification_fichier_path',
        'cv_path',
        'attestation',

        // Traitement
        'statut',
        'commentaire_admin',
    ];

    protected $casts = [
        'disponible_debut' => 'date',
        'disponible_fin'   => 'date',
        'attestation'      => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($candidature) {
            if (empty($candidature->uuid)) {
                $candidature->uuid = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function langueSpecialisation(): BelongsTo
    {
        return $this->belongsTo(LanguesSpecialisation::class, 'langue_specialisation_id');
    }

    /**
     * @param Builder<Candidature> $query
     * @return Builder<Candidature>
     */
    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * @param Builder<Candidature> $query
     * @return Builder<Candidature>
     */
    public function scopeValidee(Builder $query): Builder
    {
        return $query->where('statut', 'validee');
    }

    /**
     * @param Builder<Candidature> $query
     * @return Builder<Candidature>
     */
    public function scopeRejetee(Builder $query): Builder
    {
        return $query->where('statut', 'rejetee');
    }
}
