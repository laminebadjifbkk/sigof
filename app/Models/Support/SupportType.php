<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportType extends Model
{
    use SoftDeletes;

    protected $table = 'support_types';

    protected $fillable = [
        'support_category_id',
        'libelle',
        'description',
        'actif',
        'ordre',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'support_category_id' => 'integer',
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    /**
     * Catégorie à laquelle appartient ce type.
     */
    public function categorie(): BelongsTo
    {
        return $this->belongsTo(
            SupportCategory::class,
            'support_category_id'
        );
    }

    /**
     * Utilisateur ayant créé le type.
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    /**
     * Utilisateur ayant effectué la dernière modification.
     */
    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }

    /**
     * Types actifs.
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Types classés par ordre d'affichage.
     */
    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre')
                     ->orderBy('libelle');
    }
}
