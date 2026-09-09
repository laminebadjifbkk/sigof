<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpSousActivite extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'onfp_sous_activites';

    protected $fillable = [
        'activite_id','reference','titre','description','statut','priorite',
        'progression','date_debut','date_fin_prevue','date_fin_reelle',
        'observation','created_by','updated_by',
    ];

    protected $casts = [
        'activite_id'=>'integer','progression'=>'integer',
        'created_by'=>'integer','updated_by'=>'integer',
        'date_debut'=>'date','date_fin_prevue'=>'date','date_fin_reelle'=>'date',
    ];

    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function taches() { return $this->hasMany(OnfpTache::class, 'sous_activite_id'); }
    public function createdBy() { return $this->belongsTo(Employee::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(Employee::class, 'updated_by'); }
}
