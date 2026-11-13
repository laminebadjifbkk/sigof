<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class SupportCategory extends Model
{
    use SoftDeletes;

    protected $table = 'support_categories';

    protected $fillable = [
        'libelle',
        'description',
        'actif',
        'ordre',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    /**
     * Utilisateur ayant créé la catégorie.
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    /**
 * Types de demandes associés à cette catégorie.
 */
public function types(): HasMany
{
    return $this->hasMany(
        SupportType::class,
        'support_category_id'
    );
}
    /**
     * Utilisateur ayant effectué la dernière modification.
     */
    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }

    /**
     * Périmètre des catégories actives.
     */
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Ordre d'affichage des catégories.
     */
    public function scopeOrdonnees($query)
    {
        return $query->orderBy('ordre')
                     ->orderBy('libelle');
    }
}
