<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpTiers extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'onfp_tiers';
    protected $fillable = ['type','nom','organisation','fonction','telephone','email','adresse','observation','actif'];
    protected $casts = ['actif'=>'boolean'];
    public function activites() { return $this->hasMany(OnfpActiviteTiers::class, 'tiers_id'); }
}
