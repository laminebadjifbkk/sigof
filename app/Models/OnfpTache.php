<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OnfpTache extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'onfp_taches';

    protected $fillable = [
        'activite_id',
        'sous_activite_id',
        'reference',
        'titre',
        'description',
        'statut',
        'priorite',
        'progression',
        'date_debut',
        'date_echeance',
        'date_realisation',
        'observation',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activite_id' => 'integer',
        'sous_activite_id' => 'integer',
        'progression' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'date_debut' => 'date',
        'date_echeance' => 'date',
        'date_realisation' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (OnfpTache $tache) {

            // Toujours générer une référence si elle est absente ou vide
            if (blank($tache->reference)) {

                do {
                    $reference = 'TAC-' . strtoupper(Str::random(8));
                } while (
                    OnfpTache::where('reference', $reference)->exists()
                );

                $tache->reference = $reference;
            }
        });
    }

    public function activite()
    {
        return $this->belongsTo(OnfpActivite::class, 'activite_id');
    }
    public function sousActivite()
    {
        return $this->belongsTo(OnfpSousActivite::class, 'sous_activite_id');
    }
    public function responsables()
    {
        return $this->hasMany(OnfpTacheResponsable::class, 'tache_id');
    }
    public function suiveurs()
    {
        return $this->hasMany(OnfpTacheSuiveur::class, 'tache_id');
    }
    public function documents()
    {
        return $this->hasMany(OnfpActiviteDocument::class, 'tache_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }

    public function commentaires()
    {
        return $this->hasMany(OnfpActiviteCommentaire::class, 'tache_id');
    }

    public const STATUTS = [
        'a_faire'   => 'À faire',
        'en_cours'  => 'En cours',
        'suspendue' => 'Suspendue',
        'terminee'  => 'Terminée',
        'annulee'   => 'Annulée',
    ];

    public const PRIORITES = [
        'basse'   => 'Basse',
        'normale' => 'Normale',
        'haute'   => 'Haute',
        'urgente' => 'Urgente',
    ];
}
