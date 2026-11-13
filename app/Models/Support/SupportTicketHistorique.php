<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketHistorique extends Model
{
    protected $table = 'support_ticket_historiques';

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'action',
        'champ',
        'ancienne_valeur',
        'nouvelle_valeur',
        'description',
    ];

    protected $casts = [
        'support_ticket_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            SupportTicket::class,
            'support_ticket_id'
        );
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}
}
