<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpActiviteDocument extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'onfp_activite_documents';
    protected $fillable = [
        'activite_id','tache_id','employee_id','type','nom_original','nom_fichier',
        'disk','chemin','mime_type','taille','description','document_final',
    ];
    protected $casts = [
        'activite_id'=>'integer','tache_id'=>'integer','employee_id'=>'integer',
        'taille'=>'integer','document_final'=>'boolean',
    ];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function tache() { return $this->belongsTo(OnfpTache::class, 'tache_id'); }
    public function employee() { return $this->belongsTo(Employee::class, 'employee_id'); }
}
