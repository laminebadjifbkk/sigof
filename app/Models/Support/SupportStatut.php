<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportStatut extends Model
{
    use SoftDeletes;

    protected $table = 'support_statuts';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'est_terminal',
        'actif',
        'ordre',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'est_terminal' => 'boolean',
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

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdonnes($query)
    {
        return $query->orderBy('ordre')
                     ->orderBy('libelle');
    }
}
