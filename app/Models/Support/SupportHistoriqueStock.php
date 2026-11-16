<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportHistoriqueStock extends Model
{
    protected $table = 'support_historiques_stocks';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'entite',
        'entite_id',
        'action',
        'description',
        'anciennes_valeurs',
        'nouvelles_valeurs',
        'adresse_ip',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'entite_id' => 'integer',
        'anciennes_valeurs' => 'array',
        'nouvelles_valeurs' => 'array',
        'created_at' => 'datetime',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePourEntite($query, string $entite, int $entiteId)
    {
        return $query->where('entite', $entite)
            ->where('entite_id', $entiteId);
    }

    public function scopeRecents($query)
    {
        return $query->orderByDesc('created_at');
    }
}
