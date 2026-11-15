<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportCategorieEquipement extends Model
{
    use SoftDeletes;

    protected $table = 'support_categories_equipements';

    protected $fillable = [
        'code', 'libelle', 'description', 'actif', 'ordre',
        'user_create_id', 'user_update_id',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
        'user_create_id' => 'integer',
        'user_update_id' => 'integer',
    ];

    public function equipements(): HasMany
    {
        return $this->hasMany(
            SupportEquipement::class,
            'support_categorie_equipement_id'
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

    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    public function scopeOrdonnees($query)
    {
        return $query->orderBy('ordre')->orderBy('libelle');
    }
}
