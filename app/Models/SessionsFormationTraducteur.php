<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionsFormationTraducteur extends Model
{
    protected $fillable = [
        'nom',
        'langue_specialisation_id',
        'formateur',
        'lieu',
        'date_debut',
        'date_fin',
        'statut',
        'description',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
    ];

    private const STATUTS = [
        'planifiee' => ['label' => 'Planifiée', 'classe' => 'status-en-attente'],
        'en_cours'  => ['label' => 'En cours',   'classe' => 'status-en-cours'],
        'terminee'  => ['label' => 'Terminée',   'classe' => 'status-validee'],
        'annulee'   => ['label' => 'Annulée',    'classe' => 'status-rejetee'],
    ];

    public function getStatutLabelAttribute(): string
    {
        return self::STATUTS[$this->statut]['label'] ?? ucfirst($this->statut);
    }

    public function getStatutClasseAttribute(): string
    {
        return self::STATUTS[$this->statut]['classe'] ?? 'status-inconnu';
    }

    public function langueSpecialisation(): BelongsTo
    {
        return $this->belongsTo(LanguesSpecialisation::class);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(FormationsTraducteur::class, 'session_formation_id');
    }
}