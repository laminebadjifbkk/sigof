<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpTache extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'onfp_taches';

    protected $fillable = [
        'activite_id','sous_activite_id','reference','titre','description',
        'statut','priorite','progression','date_debut','date_echeance',
        'date_realisation','observation','created_by','updated_by',
    ];

    protected $casts = [
        'activite_id'=>'integer','sous_activite_id'=>'integer',
        'progression'=>'integer','created_by'=>'integer','updated_by'=>'integer',
        'date_debut'=>'date','date_echeance'=>'date','date_realisation'=>'date',
    ];

    public function activite() { return $this->belongsTo(OnfpActivite::class, 'activite_id'); }
    public function sousActivite() { return $this->belongsTo(OnfpSousActivite::class, 'sous_activite_id'); }
    public function responsables() { return $this->hasMany(OnfpTacheResponsable::class, 'tache_id'); }
    public function suiveurs() { return $this->hasMany(OnfpTacheSuiveur::class, 'tache_id'); }
    public function documents() { return $this->hasMany(OnfpActiviteDocument::class, 'tache_id'); }
    public function createdBy() { return $this->belongsTo(Employee::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(Employee::class, 'updated_by'); }
}
