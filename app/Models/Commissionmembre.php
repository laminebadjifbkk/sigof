<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commissionmembre extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'commissionmembres';

    protected $fillable = [
        'uuid',
        'civilite',
        'prenom',
        'nom',
        'fonction',
        'structure',
        'email',
        'telephone',
        'signature',
        'description',
        'statut',
    ];

    public function commissionagrements()
    {
        return $this->belongsToMany(Commissionagrement::class, 'commissionagrementcommissionmembres')
            ->withTimestamps();
    }

    public function commissions_dirigees()
    {
        return $this->hasMany(Commissionagrement::class, 'chef_id');
    }

    public function commissions_secretaire()
    {
        return $this->hasMany(Commissionagrement::class, 'secretaire_id');
    }

}
