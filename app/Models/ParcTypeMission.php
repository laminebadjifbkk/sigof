<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcTypeMission extends Model
{
    use HasFactory;

    protected $table = 'parc_type_missions';

    protected $fillable = ['libelle'];

    public function missions()
    {
        return $this->hasMany(ParcMission::class, 'type_mission_id');
    }
}