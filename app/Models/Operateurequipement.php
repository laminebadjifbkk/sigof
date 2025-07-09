<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Operateurequipement extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'operateurequipements';

    protected $casts = [
        'operateurs_id' => 'int',
    ];
    protected $fillable = [
        'uuid',
        'designation',
        'quantite',
        'etat',
        'statut',
        'type',
        'details',
        'operateurs_id',
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

    public function operateur()
    {
        return $this->belongsTo(Operateur::class, 'operateurs_id')->latest();
    }

}
