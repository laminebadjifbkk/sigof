<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportInventaireLigne extends Model
{
    protected $table = 'support_inventaire_lignes';

    protected $fillable = [
        'support_inventaire_id',
        'support_article_stock_id',
        'quantite_theorique',
        'quantite_comptee',
        'ecart',
        'user_comptage_id',
        'date_comptage',
        'observations',
    ];

    protected $casts = [
        'support_inventaire_id' => 'integer',
        'support_article_stock_id' => 'integer',
        'quantite_theorique' => 'decimal:3',
        'quantite_comptee' => 'decimal:3',
        'ecart' => 'decimal:3',
        'user_comptage_id' => 'integer',
        'date_comptage' => 'datetime',
    ];

    public function inventaire(): BelongsTo
    {
        return $this->belongsTo(
            SupportInventaireStock::class,
            'support_inventaire_id'
        );
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(
            SupportArticleStock::class,
            'support_article_stock_id'
        );
    }

    public function compteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_comptage_id');
    }
}
