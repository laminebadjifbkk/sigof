<?php

namespace App\Models\Support;

use App\Models\User;
use App\Models\Employee;
use App\Models\Direction;
use App\Models\Antenne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportAffectationEquipement extends Model
{
    protected $table = 'support_affectations_equipements';

    protected $fillable = [
        'support_equipement_id',
        'employee_id',
        'direction_id',
        'antenne_id',
        'date_debut',
        'date_fin',
        'etat_remise',
        'etat_retour',
        'observations',
        'motif_retour',
        'user_create_id',
        'user_update_id',
    ];

    protected $casts = [
        'support_equipement_id' => 'integer',
        'employee_id' => 'integer',
        'direction_id' => 'integer',
        'antenne_id' => 'integer',
        'user_create_id' => 'integer',
        'user_update_id' => 'integer',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function equipement(): BelongsTo
    {
        return $this->belongsTo(
            SupportEquipement::class,
            'support_equipement_id'
        );
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }

    public function antenne(): BelongsTo
    {
        return $this->belongsTo(Antenne::class, 'antenne_id');
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
        return $query->whereNull('date_fin');
    }
}
