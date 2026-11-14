<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    use SoftDeletes;

    protected $table = 'support_tickets';

    protected $fillable = [
        'reference',
        'objet',
        'description',
        'support_category_id',
        'support_type_id',
        'support_priorite_id',
        'support_statut_id',
        'user_demandeur_id',
        'user_assigne_id',
        'date_soumission',
        'date_prise_en_charge',
        'date_resolution',
        'date_cloture',
        'motif_rejet',
        'motif_attente',
    ];

    protected $casts = [
        'support_category_id' => 'integer',
        'support_type_id' => 'integer',
        'support_priorite_id' => 'integer',
        'support_statut_id' => 'integer',
        'user_demandeur_id' => 'integer',
        'user_assigne_id' => 'integer',
        'date_soumission' => 'datetime',
        'date_prise_en_charge' => 'datetime',
        'date_resolution' => 'datetime',
        'date_cloture' => 'datetime',
    ];

    public function affectations(): HasMany
{
    return $this->hasMany(
        SupportTicketAffectation::class,
        'support_ticket_id'
    )->orderBy('date_affectation', 'desc');
}
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(
            SupportCategory::class,
            'support_category_id'
        );
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(
            SupportType::class,
            'support_type_id'
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
            SupportStatut::class,
            'support_statut_id'
        );
    }

    public function demandeur(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_demandeur_id'
        );
    }

    public function assigne(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_assigne_id'
        );
    }

    public function historiques(): HasMany
    {
        return $this->hasMany(
            SupportTicketHistorique::class,
            'support_ticket_id'
        )->latest();
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(
            SupportTicketCommentaire::class,
            'support_ticket_id'
        )->latest();
    }

    public function piecesJointes(): HasMany
    {
        return $this->hasMany(
            SupportTicketPieceJointe::class,
            'support_ticket_id'
        )->latest();
    }

    public function scopeRecents($query)
    {
        return $query->orderByDesc('date_soumission');
    }

    public function scopeEnCours($query)
    {
        return $query->whereHas('statut', function ($q) {
            $q->whereIn('code', ['OUVERT', 'EN_COURS', 'EN_ATTENTE']);
        });
    }


    public function interventions(): HasMany
    {
        return $this->hasMany(
            SupportTicketIntervention::class,
            'support_ticket_id'
        )->orderBy('date_intervention', 'desc');
    }

}
