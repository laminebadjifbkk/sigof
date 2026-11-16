<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportFournisseurStock extends Model
{
    use SoftDeletes;

    protected $table = 'support_fournisseurs_stocks';

    protected $fillable = [
        'code',
        'raison_sociale',
        'nom_contact',
        'telephone',
        'telephone_secondaire',
        'email',
        'adresse',
        'numero_ninea',
        'numero_registre_commerce',
        'observations',
        'actif',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'user_create_id' => 'integer',
        'user_update_id' => 'integer',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(
            SupportDocumentStock::class,
            'support_fournisseur_stock_id'
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

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('raison_sociale');
    }
}
