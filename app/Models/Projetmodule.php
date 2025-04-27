<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Projetmodule extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'projetmodules';

    protected $casts = [
        'projets_id' => 'int',
    ];
    protected $fillable = [
        'uuid',
        'module',
        'domaine',
        'effectif',
        'description',
        'statut',
        'projets_id'
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

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projets_id')->latest();
    }

	public function individuelles()
	{
		return $this->hasMany(Individuelle::class, 'modules_id');
	}

    public function projetlocalites()
{
    return $this->belongsToMany(Projetlocalite::class, 'projetmodulelocalites', 'projetmodules_id', 'projetlocalites_id');
}
}
