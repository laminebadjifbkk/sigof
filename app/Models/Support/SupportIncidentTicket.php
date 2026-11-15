<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupportIncidentTicket extends Pivot
{
    protected $table = 'support_incident_tickets';

    public $incrementing = true;

    protected $fillable = [
        'support_incident_id',
        'support_ticket_id',
        'user_create_id',
    ];

    protected $casts = [
        'support_incident_id' => 'integer',
        'support_ticket_id' => 'integer',
        'user_create_id' => 'integer',
    ];

    public function incident(): BelongsTo
    {
        return $this->belongsTo(
            SupportIncident::class,
            'support_incident_id'
        );
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            SupportTicket::class,
            'support_ticket_id'
        );
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_create_id'
        );
    }
}
