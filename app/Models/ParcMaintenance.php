<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcMaintenance extends Model
{
    protected $table = 'parc_maintenances';

    protected $fillable = ['vehicule_id', 'type', 'date', 'kilometrage', 'montant', 'fournisseur', 'note'];

    protected $casts = ['date' => 'date'];

    public function vehicule()
    {
        return $this->belongsTo(ParcVehicule::class, 'vehicule_id');
    }
}
