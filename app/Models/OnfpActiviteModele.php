<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpActiviteModele extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'onfp_activite_modeles';
    protected $fillable = [
        'code','nom','type_id','direction_id','description','duree_estimee',
        'actif','created_by','updated_by',
    ];
    protected $casts = [
        'type_id'=>'integer','direction_id'=>'integer','duree_estimee'=>'integer',
        'actif'=>'boolean','created_by'=>'integer','updated_by'=>'integer',
    ];
    public function type() { return $this->belongsTo(OnfpActiviteType::class, 'type_id'); }
    public function direction() { return $this->belongsTo(Direction::class, 'direction_id'); }
    public function sousActivites() { return $this->hasMany(OnfpModeleSousActivite::class, 'modele_id')->orderBy('ordre'); }
    public function taches() { return $this->hasMany(OnfpModeleTache::class, 'modele_id')->orderBy('ordre'); }
    public function createdBy() { return $this->belongsTo(Employee::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(Employee::class, 'updated_by'); }
}
