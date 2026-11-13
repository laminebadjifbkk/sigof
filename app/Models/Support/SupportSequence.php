<?php

namespace App\Models\Support;

use Illuminate\Database\Eloquent\Model;

class SupportSequence extends Model
{
    protected $table = 'support_sequences';

    protected $fillable = [
        'code',
        'annee',
        'dernier_numero',
    ];

    protected $casts = [
        'annee' => 'integer',
        'dernier_numero' => 'integer',
    ];
}
