<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuiviPostIndividuelHistory extends Model
{
    protected $table = 'suivi_post_individuels_histories';

    protected $fillable = [
        'suivi_post_individuel_id',
        'action',
        'old_values',
        'new_values',
        'user_id',
        'note',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Relation vers le suivi principal
     */
    public function suivi()
    {
        return $this->belongsTo(SuiviPostIndividuel::class, 'suivi_post_individuel_id');
    }

    /**
     * Utilisateur qui a fait l'action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
