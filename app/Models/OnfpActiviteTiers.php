<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnfpActiviteTiers extends Model
{
    use HasFactory;
    protected $table = 'onfp_activite_tiers';
    protected $fillable = ['activite_id','tiers_id','role','observation'];
    protected $casts = ['activite_id'=>'integer','tiers_id'=>'integer'];
    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function tiers() { return $this->belongsTo(OnfpTiers::class, 'tiers_id'); }
}
