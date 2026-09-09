<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpActiviteIndicateur extends Model
{
    use HasFactory;
    protected $table = 'onfp_activite_indicateurs';
    protected $fillable = [
        'activite_id','code','libelle','description','unite','valeur_cible',
        'valeur_realisee','pourcentage','date_reference','sens','observation',
        'created_by','updated_by',
    ];
    protected $casts = [
        'activite_id'=>'integer','valeur_cible'=>'decimal:2','valeur_realisee'=>'decimal:2',
        'pourcentage'=>'decimal:2','date_reference'=>'date','created_by'=>'integer','updated_by'=>'integer',
    ];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function createdBy() { return $this->belongsTo(Employee::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(Employee::class, 'updated_by'); }
}
