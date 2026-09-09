<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpModeleSousActivite extends Model
{
    use HasFactory;
    protected $table = 'onfp_modele_sous_activites';
    protected $fillable = ['modele_id','titre','description','ordre','duree_estimee'];
    protected $casts = ['modele_id'=>'integer','ordre'=>'integer','duree_estimee'=>'integer'];
    public function modele() { return $this->belongsTo(OnfpActiviteModele::class, 'modele_id'); }
    public function taches() { return $this->hasMany(OnfpModeleTache::class, 'modele_sous_activite_id')->orderBy('ordre'); }
}
