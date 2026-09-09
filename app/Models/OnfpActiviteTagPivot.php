<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OnfpActiviteTagPivot extends Pivot
{
    protected $table = 'onfp_activite_tag_pivot';
    public $incrementing = false;
    public $timestamps = true;
    protected $fillable = ['activite_id','tag_id'];
    protected $casts = ['activite_id'=>'integer','tag_id'=>'integer'];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function tag() { return $this->belongsTo(OnfpActiviteTag::class, 'tag_id'); }
}
