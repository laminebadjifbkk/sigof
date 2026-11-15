<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SupportIncidentHistorique extends Model
{
    protected $table = 'support_incident_historiques';

    protected $fillable = [
        'support_incident_id',
        'user_id',
        'action',
        'champ',
        'ancienne_valeur',
        'nouvelle_valeur',
        'description',
    ];

    protected $casts = [
        'support_incident_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function incident(): BelongsTo
    {
        return $this->belongsTo(
            SupportIncident::class,
            'support_incident_id'
        );
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
