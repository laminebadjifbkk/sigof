<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketIntervention extends Model
{
    use SoftDeletes;

    protected $table = 'support_ticket_interventions';

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'date_intervention',
        'diagnostic',
        'travaux_realises',
        'duree_minutes',
        'resultat',
        'observations',
    ];

    protected $casts = [
        'support_ticket_id' => 'integer',
        'user_id' => 'integer',
        'date_intervention' => 'datetime',
        'duree_minutes' => 'integer',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            SupportTicket::class,
            'support_ticket_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function getDureeLisibleAttribute(): string
    {
        $heures = intdiv($this->duree_minutes, 60);
        $minutes = $this->duree_minutes % 60;

        if ($heures > 0 && $minutes > 0) {
            return "{$heures} h {$minutes} min";
        }

        if ($heures > 0) {
            return "{$heures} h";
        }

        return "{$minutes} min";
    }
}
