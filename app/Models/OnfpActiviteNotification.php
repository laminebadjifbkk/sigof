<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpActiviteNotification extends Model
{
    use HasFactory;
    protected $table = 'onfp_activite_notifications';
    protected $fillable = ['activite_id','employee_id','type','titre','message','priorite','lu_at','envoye_at'];
    protected $casts = ['activite_id'=>'integer','employee_id'=>'integer','lu_at'=>'datetime','envoye_at'=>'datetime'];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function employee() { return $this->belongsTo(Employee::class, 'employee_id'); }
}
