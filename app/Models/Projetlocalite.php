<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Projetlocalite extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'projetlocalites';
    protected $casts = [
        'projets_id' => 'int',
    ];
    protected $fillable = [
        'uuid',
        'localite',
        'effectif',
        'projets_id',
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
    
    public function projetmodules()
    {
        return $this->belongsToMany(Projetmodule::class, 'projetmodulelocalites', 'projetlocalites_id', 'projetmodules_id');
    }

}
