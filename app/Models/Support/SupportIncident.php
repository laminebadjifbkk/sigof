<?php

namespace App\Models\Support;

use App\Models\User;
use App\Models\Support\SupportIncidentHistorique;
use App\Models\Support\SupportIncidentTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupportIncident extends Model
{
    use SoftDeletes;

    protected $table = 'support_incidents';

    protected $fillable = [
        'reference',
        'titre',
        'description',
        'support_incident_type_id',
        'support_priorite_id',
        'support_incident_statut_id',
        'user_declarant_id',
        'user_responsable_id',
        'date_detection',
        'date_declaration',
        'date_prise_en_charge',
        'date_resolution',
        'date_cloture',
        'cause',
        'solution',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'support_incident_type_id' => 'integer',
        'support_priorite_id' => 'integer',
        'support_incident_statut_id' => 'integer',
        'user_declarant_id' => 'integer',
        'user_responsable_id' => 'integer',
        'user_create_id' => 'integer',
        'user_update_id' => 'integer',
        'date_detection' => 'datetime',
        'date_declaration' => 'datetime',
        'date_prise_en_charge' => 'datetime',
        'date_resolution' => 'datetime',
        'date_cloture' => 'datetime',
    ];

    // Référentiels

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            SupportIncidentType::class,
            'support_incident_type_id'
        );
    }

    public function priorite(): BelongsTo
    {
        return $this->belongsTo(
            SupportPriorite::class,
            'support_priorite_id'
        );
    }

    public function statut(): BelongsTo
    {
        return $this->belongsTo(
            SupportIncidentStatut::class,
            'support_incident_statut_id'
        );
    }

    // Utilisateurs

    public function declarant(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_declarant_id'
        );
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_responsable_id'
        );
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_create_id'
        );
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_update_id'
        );
    }

    // Historique

    public function historiques(): HasMany
    {
        return $this->hasMany(
            SupportIncidentHistorique::class,
            'support_incident_id'
        )->latest();
    }

    // Tickets associés

    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(
            SupportTicket::class,
            'support_incident_tickets',
            'support_incident_id',
            'support_ticket_id'
        )
        ->using(SupportIncidentTicket::class)
        ->withPivot('user_create_id')
        ->withTimestamps();
    }

    // Périmètre des incidents récents

    public function scopeRecents($query)
    {
        return $query->orderByDesc('date_detection');
    }
}
