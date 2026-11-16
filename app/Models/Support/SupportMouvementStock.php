<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportMouvementStock extends Model
{
    protected $table = 'support_mouvements_stocks';

    protected $fillable = [
        'support_document_stock_id',
        'support_ligne_document_stock_id',
        'support_article_stock_id',
        'type_mouvement',
        'quantite',
        'cout_unitaire',
        'valeur_mouvement',
        'quantite_avant',
        'quantite_apres',
        'user_create_id',
        'date_mouvement',
    ];

    protected $casts = [
        'support_document_stock_id' => 'integer',
        'support_ligne_document_stock_id' => 'integer',
        'support_article_stock_id' => 'integer',
        'quantite' => 'decimal:3',
        'cout_unitaire' => 'decimal:4',
        'valeur_mouvement' => 'decimal:4',
        'quantite_avant' => 'decimal:3',
        'quantite_apres' => 'decimal:3',
        'user_create_id' => 'integer',
        'date_mouvement' => 'datetime',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(
            SupportDocumentStock::class,
            'support_document_stock_id'
        );
    }

    public function ligneDocument(): BelongsTo
    {
        return $this->belongsTo(
            SupportLigneDocumentStock::class,
            'support_ligne_document_stock_id'
        );
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(
            SupportArticleStock::class,
            'support_article_stock_id'
        );
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function scopeParType($query, string $type)
    {
        return $query->where('type_mouvement', $type);
    }

    public function scopeRecents($query)
    {
        return $query->orderByDesc('date_mouvement');
    }
}
