<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = [
        'structure',
        'nom',
        'fonction',
        'telephone',
        'email',
        'commentaire',
        'autre'
    ];
}
