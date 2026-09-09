<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpModeleTache extends Model
{
    use HasFactory;
    protected $table = 'onfp_modele_taches';
    protected $fillable = [
        'modele_id','modele_sous_activite_id','titre','description',
        'ordre','duree_estimee','priorite',
    ];
    protected $casts = [
        'modele_id'=>'integer','modele_sous_activite_id'=>'integer',
        'ordre'=>'integer','duree_estimee'=>'integer',
    ];
    public function modele() { return $this->belongsTo(OnfpActiviteModele::class, 'modele_id'); }
    public function modeleSousActivite() { return $this->belongsTo(OnfpModeleSousActivite::class, 'modele_sous_activite_id'); }
}
