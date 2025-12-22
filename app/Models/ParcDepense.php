<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcDepense extends Model
{
    protected $table = 'parc_depenses';

    protected $fillable = ['mission_id', 'type', 'montant', 'note'];

    public function mission()
    {
        return $this->belongsTo(ParcMission::class, 'mission_id');
    }
}
