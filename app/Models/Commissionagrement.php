<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commissionagrement extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'commissionagrements';

    protected $casts = [
        'date'             => 'date',
        'date_ouverture'   => 'date',
        'date_fermeture'   => 'date',
        'debut_commission' => 'datetime',
        'fin_commission'   => 'datetime',
    ];
    /*
	protected $dates = [,
		'date',
	]; */

    protected $fillable = [
        'uuid',
        'commission',
        'date',
        'session',
        'lieu',
        'annee',
        'statut',
        'description',
        'date_ouverture',
        'date_fermeture',
        'debut_commission',
        'fin_commission',
        'chef_id',
        'secretaire_id',
        'recommandations',
    ];

    /*  public function operateurs()
    {
        return $this->hasMany(Operateur::class, 'commissionagrements_id');
    } */

    public function operateurs()
    {
        return $this->belongsToMany(
            Operateur::class,
            'commissionagrement_operateurs', // nom de la table pivot
            'commissionagrement_id',         // clé étrangère dans la table pivot pointant vers ce modèle
            'operateur_id'                   // clé étrangère dans la table pivot pointant vers le modèle cible
        );
    }

    public function commissionmembres()
    {
        return $this->belongsToMany(Commissionmembre::class, 'commissionagrementcommissionmembres')
            ->withTimestamps();
    }

    public function chef()
    {
        return $this->belongsTo(Commissionmembre::class, 'chef_id');
    }

    public function secretaire()
    {
        return $this->belongsTo(Commissionmembre::class, 'secretaire_id');
    }
}
