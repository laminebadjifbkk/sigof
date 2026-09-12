<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class OnfpSousActivite extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'onfp_sous_activites';

    protected $fillable = [
        'uuid',
        'activite_id',
        'reference',
        'titre',
        'description',
        'statut',
        'priorite',
        'progression',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'observation',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'uuid' => 'string',
        'activite_id' => 'integer',
        'progression' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'date',
    ];

    /**
     * Génération automatique du UUID.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Utiliser le UUID pour le model binding des routes.
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function activite()
    {
        return $this->belongsTo(
            OnfpActivite::class,
            'activite_id'
        );
    }

    public function taches()
    {
        return $this->hasMany(
            OnfpTache::class,
            'sous_activite_id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            Employee::class,
            'created_by'
        );
    }

    public function updatedBy()
    {
        return $this->belongsTo(
            Employee::class,
            'updated_by'
        );
    }
}
