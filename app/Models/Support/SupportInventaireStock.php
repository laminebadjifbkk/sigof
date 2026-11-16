<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportInventaireStock extends Model
{
    protected $table = 'support_inventaires_stocks';

    protected $fillable = [
        'reference',
        'libelle',
        'description',
        'date_debut',
        'date_fin',
        'statut',
        'user_create_id',
        'user_validation_id',
        'date_soumission',
        'date_validation',
        'motif_rejet',
        'observations',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'date_soumission' => 'datetime',
        'date_validation' => 'datetime',
        'user_create_id' => 'integer',
        'user_validation_id' => 'integer',
    ];

    public function lignes(): HasMany
    {
        return $this->hasMany(
            SupportInventaireLigne::class,
            'support_inventaire_id'
        );
    }

    public function stocksVerrouilles(): HasMany
    {
        return $this->hasMany(
            SupportStock::class,
            'support_inventaire_id'
        );
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_validation_id');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'EN_COURS');
    }

    public function scopeEnAttenteValidation($query)
    {
        return $query->where('statut', 'EN_ATTENTE_VALIDATION');
    }
}
