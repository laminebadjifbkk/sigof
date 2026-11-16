<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportLigneDocumentStock extends Model
{
    protected $table = 'support_lignes_documents_stocks';

    protected $fillable = [
        'support_document_stock_id',
        'support_article_stock_id',
        'support_ligne_origine_id',
        'quantite',
        'cout_unitaire',
        'valeur_ligne',
        'observations',
    ];

    protected $casts = [
        'support_document_stock_id' => 'integer',
        'support_article_stock_id' => 'integer',
        'support_ligne_origine_id' => 'integer',
        'quantite' => 'decimal:3',
        'cout_unitaire' => 'decimal:4',
        'valeur_ligne' => 'decimal:4',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(
            SupportDocumentStock::class,
            'support_document_stock_id'
        );
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(
            SupportArticleStock::class,
            'support_article_stock_id'
        );
    }

    public function ligneOrigine(): BelongsTo
    {
        return $this->belongsTo(
            self::class,
            'support_ligne_origine_id'
        );
    }

    public function retours(): HasMany
    {
        return $this->hasMany(
            self::class,
            'support_ligne_origine_id'
        );
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(
            SupportMouvementStock::class,
            'support_ligne_document_stock_id'
        );
    }
}
