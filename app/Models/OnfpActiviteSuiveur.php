<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpActiviteSuiveur extends Model
{
    use HasFactory;
    protected $table = 'onfp_activite_suiveurs';
    protected $fillable = ['activite_id','employee_id'];
    protected $casts = ['activite_id'=>'integer','employee_id'=>'integer'];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function employee() { return $this->belongsTo(Employee::class, 'employee_id'); }
}
