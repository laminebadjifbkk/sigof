<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpActiviteNotification extends Model
{
    use HasFactory;

    protected $table = 'onfp_activite_notifications';

    protected $fillable = [
        'activite_id',
        'employee_id',
        'type',
        'titre',
        'message',
        'priorite',
        'lu_at',
        'envoye_at',
    ];

    protected $casts = [
        'activite_id' => 'integer',
        'employee_id' => 'integer',
        'lu_at' => 'datetime',
        'envoye_at' => 'datetime',
    ];

    public const TYPE_CHANGEMENT_STATUT = 'changement_statut';
    public const TYPE_ECHEANCE_PROCHE = 'echeance_proche';

    public function activite()
    {
        return $this->belongsTo(OnfpActivite::class, 'activite_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function scopeNonLues($query)
    {
        return $query->whereNull('lu_at');
    }

    public function scopePourEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function marquerCommeLue(): void
    {
        if (is_null($this->lu_at)) {
            $this->update(['lu_at' => now()]);
        }
    }
}
