<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationsTraducteur extends Model
{
    protected $fillable = [
        'candidature_id',
        'session_formation_id',
        'statut_formation',
        'attestation_path',
        'date_attestation',
        'commentaire',
    ];

    protected $casts = [
        'date_attestation' => 'date',
    ];

    private const STATUTS = [
        'non_inscrit' => ['label' => 'Non inscrit', 'classe' => 'status-inconnu'],
        'inscrit'     => ['label' => 'Inscrit',     'classe' => 'status-en-attente'],
        'en_cours'    => ['label' => 'En cours',    'classe' => 'status-en-cours'],
        'complete'    => ['label' => 'Complétée',   'classe' => 'status-validee'],
        'absent'      => ['label' => 'Absent',      'classe' => 'status-rejetee'],
    ];

    public function getStatutFormationLabelAttribute(): string
    {
        return self::STATUTS[$this->statut_formation]['label'] ?? ucfirst($this->statut_formation);
    }

    public function getStatutFormationClasseAttribute(): string
    {
        return self::STATUTS[$this->statut_formation]['classe'] ?? 'status-inconnu';
    }

    public function candidature(): BelongsTo
    {
        return $this->belongsTo(Candidature::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(SessionsFormationTraducteur::class, 'session_formation_id');
    }
}