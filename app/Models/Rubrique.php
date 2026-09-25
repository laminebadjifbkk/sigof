<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rubrique extends Model
{
    use SoftDeletes;

    protected $table = 'rubriques';

    protected $fillable = ['uuid', 'libelle', 'groupe', 'unite_defaut', 'ordre', 'actif'];

    public function lignes()
    {
        return $this->hasMany(NoteFraisLigne::class, 'rubriques_id');
    }
}
