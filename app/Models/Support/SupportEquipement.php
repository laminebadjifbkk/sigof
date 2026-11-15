<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupportEquipement extends Model
{
    use SoftDeletes;

    protected $table = 'support_equipements';

    protected $fillable = [
        'reference',
        'designation',
        'support_categorie_equipement_id',
        'marque',
        'modele',
        'numero_serie',
        'date_acquisition',
        'fournisseur',
        'prix_acquisition',
        'date_fin_garantie',
        'etat_physique',
        'statut',
        'emplacement',
        'description',
        'observations',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'support_categorie_equipement_id' => 'integer',
        'user_create_id' => 'integer',
        'user_update_id' => 'integer',
        'date_acquisition' => 'date',
        'date_fin_garantie' => 'date',
        'prix_acquisition' => 'decimal:2',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(
            SupportCategorieEquipement::class,
            'support_categorie_equipement_id'
        );
    }

    public function affectations(): HasMany
    {
        return $this->hasMany(
            SupportAffectationEquipement::class,
            'support_equipement_id'
        )->orderByDesc('date_debut');
    }

    public function affectationActive(): HasOne
    {
        return $this->hasOne(
            SupportAffectationEquipement::class,
            'support_equipement_id'
        )->whereNull('date_fin');
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(
            SupportMouvementEquipement::class,
            'support_equipement_id'
        )->orderByDesc('date_mouvement');
    }

    public function historiques(): HasMany
    {
        return $this->hasMany(
            SupportEquipementHistorique::class,
            'support_equipement_id'
        )->latest();
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }
}
