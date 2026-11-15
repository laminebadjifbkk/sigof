<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportMouvementEquipement extends Model
{
    protected $table = 'support_mouvements_equipements';

    protected $fillable = [
        'support_equipement_id',
        'affectation_origine_id',
        'affectation_destination_id',
        'type_mouvement',
        'date_mouvement',
        'motif',
        'observations',
        'user_create_id',
    ];

    protected $casts = [
        'support_equipement_id' => 'integer',
        'affectation_origine_id' => 'integer',
        'affectation_destination_id' => 'integer',
        'user_create_id' => 'integer',
        'date_mouvement' => 'datetime',
    ];

    public function equipement(): BelongsTo
    {
        return $this->belongsTo(
            SupportEquipement::class,
            'support_equipement_id'
        );
    }

    public function affectationOrigine(): BelongsTo
    {
        return $this->belongsTo(
            SupportAffectationEquipement::class,
            'affectation_origine_id'
        );
    }

    public function affectationDestination(): BelongsTo
    {
        return $this->belongsTo(
            SupportAffectationEquipement::class,
            'affectation_destination_id'
        );
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_create_id');
    }
}
