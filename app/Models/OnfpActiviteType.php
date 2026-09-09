<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpActiviteType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'onfp_activite_types';

    protected $fillable = ['code', 'libelle', 'description', 'actif', 'ordre'];

    protected $casts = ['actif' => 'boolean', 'ordre' => 'integer'];

    public function activites()
    {
        return $this->hasMany(OnfpActivite::class, 'type_id');
    }

    public function modeles()
    {
        return $this->hasMany(OnfpActiviteModele::class, 'type_id');
    }
}
