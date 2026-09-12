<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OnfpActivite extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'onfp_activites';

    protected $fillable = [
        'uuid',
        'reference',
        'direction_id',
        'type_id',
        'parent_id',
        'titre',
        'description',
        'statut',
        'priorite',
        'progression',
        'date_enclenchement',
        'date_execution_prevue',
        'date_fin_prevue',
        'date_execution_reelle',
        'date_fin_reelle',
        'etat_sante',
        'observation',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'direction_id' => 'integer',
        'type_id' => 'integer',
        'parent_id' => 'integer',
        'progression' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'date_enclenchement' => 'date',
        'date_execution_prevue' => 'date',
        'date_fin_prevue' => 'date',
        'date_execution_reelle' => 'date',
        'date_fin_reelle' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'direction_id');
    }
    public function type()
    {
        return $this->belongsTo(OnfpActiviteType::class, 'type_id');
    }
    public function parent()
    {
        return $this->belongsTo(OnfpActivite::class, 'parent_id');
    }
    public function enfants()
    {
        return $this->hasMany(OnfpActivite::class, 'parent_id');
    }
    public function sousActivites()
    {
        return $this->hasMany(OnfpSousActivite::class, 'activite_id');
    }
    public function taches()
    {
        return $this->hasMany(OnfpTache::class, 'activite_id');
    }
    public function responsables()
    {
        return $this->hasMany(OnfpActiviteResponsable::class, 'activite_id');
    }
    public function suiveurs()
    {
        return $this->hasMany(OnfpActiviteSuiveur::class, 'activite_id');
    }
    public function tiers()
    {
        return $this->hasMany(OnfpActiviteTiers::class, 'activite_id');
    }
    public function historiques()
    {
        return $this->hasMany(OnfpActiviteHistorique::class, 'activite_id');
    }
    public function commentaires()
    {
        return $this->hasMany(OnfpActiviteCommentaire::class, 'activite_id');
    }
    public function documents()
    {
        return $this->hasMany(OnfpActiviteDocument::class, 'activite_id');
    }
    public function notifications()
    {
        return $this->hasMany(OnfpActiviteNotification::class, 'activite_id');
    }
    public function indicateurs()
    {
        return $this->hasMany(OnfpActiviteIndicateur::class, 'activite_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            OnfpActiviteTag::class,
            'onfp_activite_tag_pivot',
            'activite_id',
            'tag_id'
        )->withTimestamps();
    }

    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by');
    }
}
