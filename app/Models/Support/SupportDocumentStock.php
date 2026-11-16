<?php

namespace App\Models\Support;

use App\Models\User;
use App\Models\Employee;
use App\Models\Direction;
use App\Models\Antenne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportDocumentStock extends Model
{
    protected $table = 'support_documents_stocks';

    protected $fillable = [
        'reference',
        'type_document',
        'statut',
        'support_fournisseur_stock_id',
        'employee_id',
        'direction_id',
        'antenne_id',
        'support_ticket_id',
        'date_document',
        'date_soumission',
        'date_validation',
        'user_create_id',
        'user_validation_id',
        'motif',
        'observations',
        'motif_rejet',
    ];

    protected $casts = [
        'support_fournisseur_stock_id' => 'integer',
        'employee_id' => 'integer',
        'direction_id' => 'integer',
        'antenne_id' => 'integer',
        'support_ticket_id' => 'integer',
        'user_create_id' => 'integer',
        'user_validation_id' => 'integer',
        'date_document' => 'date',
        'date_soumission' => 'datetime',
        'date_validation' => 'datetime',
    ];

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(
            SupportFournisseurStock::class,
            'support_fournisseur_stock_id'
        );
    }

    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }

    public function antenne(): BelongsTo
    {
        return $this->belongsTo(Antenne::class, 'antenne_id');
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
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_validation_id');
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(
            SupportLigneDocumentStock::class,
            'support_document_stock_id'
        );
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(
            SupportMouvementStock::class,
            'support_document_stock_id'
        )->orderByDesc('date_mouvement');
    }

    public function scopeBrouillons($query)
    {
        return $query->where('statut', 'BROUILLON');
    }

    public function scopeEnAttenteValidation($query)
    {
        return $query->where('statut', 'EN_ATTENTE_VALIDATION');
    }

    public function scopeValides($query)
    {
        return $query->where('statut', 'VALIDEE');
    }

    public function scopeParType($query, string $type)
    {
        return $query->where('type_document', $type);
    }
}
