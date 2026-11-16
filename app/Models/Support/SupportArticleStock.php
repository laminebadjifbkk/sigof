<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportArticleStock extends Model
{
    use SoftDeletes;

    protected $table = 'support_articles_stocks';

    protected $fillable = [
        'reference',
        'designation',
        'support_categorie_stock_id',
        'support_unite_stock_id',
        'description',
        'stock_minimum',
        'stock_maximum',
        'actif',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'support_categorie_stock_id' => 'integer',
        'support_unite_stock_id' => 'integer',
        'stock_minimum' => 'decimal:3',
        'stock_maximum' => 'decimal:3',
        'actif' => 'boolean',
        'user_create_id' => 'integer',
        'user_update_id' => 'integer',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(
            SupportCategorieStock::class,
            'support_categorie_stock_id'
        );
    }

    public function unite(): BelongsTo
    {
        return $this->belongsTo(
            SupportUniteStock::class,
            'support_unite_stock_id'
        );
    }

    public function stock(): HasOne
    {
        return $this->hasOne(
            SupportStock::class,
            'support_article_stock_id'
        );
    }

    public function lignesDocuments(): HasMany
    {
        return $this->hasMany(
            SupportLigneDocumentStock::class,
            'support_article_stock_id'
        );
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeRechercher($query, string $terme)
    {
        return $query->where(function ($q) use ($terme) {
            $q->where('reference', 'like', "%{$terme}%")
                ->orWhere('designation', 'like', "%{$terme}%");
        });
    }
}
