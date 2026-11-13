<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketAffectation extends Model
{
    protected $table = 'support_ticket_affectations';

    protected $fillable = [
        'support_ticket_id',
        'ancien_user_assigne_id',
        'nouveau_user_assigne_id',
        'affecte_par_user_id',
        'date_affectation',
        'motif',
    ];

    protected $casts = [
        'support_ticket_id' => 'integer',
        'ancien_user_assigne_id' => 'integer',
        'nouveau_user_assigne_id' => 'integer',
        'affecte_par_user_id' => 'integer',
        'date_affectation' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function ancienTechnicien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ancien_user_assigne_id');
    }

    public function nouveauTechnicien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nouveau_user_assigne_id');
    }

    public function auteurAffectation(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affecte_par_user_id');
    }
}
