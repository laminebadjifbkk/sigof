<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpTacheSuiveur extends Model
{
    use HasFactory;
    protected $table = 'onfp_tache_suiveurs';
    protected $fillable = ['tache_id','employee_id'];
    protected $casts = ['tache_id'=>'integer','employee_id'=>'integer'];
    public function tache() { return $this->belongsTo(OnfpTache::class, 'tache_id'); }
    public function employee() { return $this->belongsTo(Employee::class, 'employee_id'); }
}
