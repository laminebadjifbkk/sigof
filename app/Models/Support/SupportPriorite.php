<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportPriorite extends Model
{
    use SoftDeletes;

    protected $table = 'support_priorites';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'delai_prise_en_charge_minutes',
        'actif',
        'ordre',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'delai_prise_en_charge_minutes' => 'integer',
        'actif' => 'boolean',
        'ordre' => 'integer',
    ];

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }

    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdonnees($query)
    {
        return $query->orderBy('ordre')
                     ->orderBy('code');
    }
}
