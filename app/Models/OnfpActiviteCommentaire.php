<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpActiviteCommentaire extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'onfp_activite_commentaires';
    protected $fillable = ['activite_id','employee_id','commentaire','interne'];
    protected $casts = ['activite_id'=>'integer','employee_id'=>'integer','interne'=>'boolean'];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function employee() { return $this->belongsTo(Employee::class, 'employee_id'); }
}
