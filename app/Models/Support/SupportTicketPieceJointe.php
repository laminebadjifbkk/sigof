<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketPieceJointe extends Model
{
    use SoftDeletes;

    protected $table = 'support_ticket_pieces_jointes';

    protected $fillable = [
        'support_ticket_id',
        'user_id',
        'nom_original',
        'nom_stockage',
        'disque',
        'chemin',
        'mime_type',
        'taille_octets',
    ];

    protected $casts = [
        'support_ticket_id' => 'integer',
        'user_id' => 'integer',
        'taille_octets' => 'integer',
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

    public function getTailleLisibleAttribute(): string
    {
        $taille = $this->taille_octets;

        if ($taille >= 1048576) {
            return number_format($taille / 1048576, 2) . ' Mo';
        }

        if ($taille >= 1024) {
            return number_format($taille / 1024, 2) . ' Ko';
        }

        return $taille . ' octets';
    }

    public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id');
}
}
