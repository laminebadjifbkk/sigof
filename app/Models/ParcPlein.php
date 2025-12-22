<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcPlein extends Model
{
    protected $table = 'parc_pleins';

    protected $fillable = ['vehicule_id', 'date', 'quantite_l', 'prix_unitaire', 'montant', 'kilometrage'];

    protected $casts = ['date' => 'date'];

    public function vehicule()
    {
        return $this->belongsTo(ParcVehicule::class, 'vehicule_id');
    }
}
