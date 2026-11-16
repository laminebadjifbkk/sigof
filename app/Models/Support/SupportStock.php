<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportStock extends Model
{
    protected $table = 'support_stocks';

    protected $fillable = [
        'support_article_stock_id',
        'quantite',
        'cout_moyen_pondere',
        'valeur_stock',
        'verrouille',
        'support_inventaire_id',
    ];

    protected $casts = [
        'support_article_stock_id' => 'integer',
        'quantite' => 'decimal:3',
        'cout_moyen_pondere' => 'decimal:4',
        'valeur_stock' => 'decimal:4',
        'verrouille' => 'boolean',
        'support_inventaire_id' => 'integer',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(
            SupportArticleStock::class,
            'support_article_stock_id'
        );
    }

    public function inventaire(): BelongsTo
    {
        return $this->belongsTo(
            SupportInventaireStock::class,
            'support_inventaire_id'
        );
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(
            SupportMouvementStock::class,
            'support_article_stock_id',
            'support_article_stock_id'
        )->orderByDesc('date_mouvement');
    }
}
