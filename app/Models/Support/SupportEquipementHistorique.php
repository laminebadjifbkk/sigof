<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportEquipementHistorique extends Model
{
    protected $table = 'support_equipement_historiques';

    protected $fillable = [
        'support_equipement_id',
        'user_id',
        'action',
        'champ',
        'ancienne_valeur',
        'nouvelle_valeur',
        'description',
    ];

    protected $casts = [
        'support_equipement_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function equipement(): BelongsTo
    {
        return $this->belongsTo(
            SupportEquipement::class,
            'support_equipement_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
