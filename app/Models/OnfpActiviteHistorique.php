<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpActiviteHistorique extends Model
{
    use HasFactory;
    protected $table = 'onfp_activite_historiques';
    protected $fillable = [
        'activite_id','employee_id','action','ancien_statut','nouveau_statut',
        'ancienne_progression','nouvelle_progression','description',
        'donnees_avant','donnees_apres',
    ];
    protected $casts = [
        'activite_id'=>'integer','employee_id'=>'integer',
        'ancienne_progression'=>'integer','nouvelle_progression'=>'integer',
        'donnees_avant'=>'array','donnees_apres'=>'array',
    ];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function employee() { return $this->belongsTo(Employee::class, 'employee_id'); }
}
