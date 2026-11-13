<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketCommentaire extends Model
{
    use SoftDeletes;

    protected $table = 'support_ticket_commentaires';

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'contenu',
        'visible_demandeur',
    ];

    protected $casts = [
        'support_ticket_id' => 'integer',
        'user_id' => 'integer',
        'visible_demandeur' => 'boolean',
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

    public function scopeVisiblesDemandeur($query)
    {
        return $query->where('visible_demandeur', true);
    }

    public function scopeInternes($query)
    {
        return $query->where('visible_demandeur', false);
    }
    public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}
}
