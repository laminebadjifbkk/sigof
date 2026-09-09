<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpActiviteResponsable extends Model
{
    use HasFactory;
    protected $table = 'onfp_activite_responsables';
    protected $fillable = ['activite_id','employee_id','role','is_principal'];
    protected $casts = ['activite_id'=>'integer','employee_id'=>'integer','is_principal'=>'boolean'];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function employee() { return $this->belongsTo(Employee::class, 'employee_id'); }
}
