<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Operateurformateur extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'operateurformateurs';

    protected $casts = [
        'operateurs_id' => 'int',
    ];
    protected $fillable = [
        'uuid',
        'cin',
        'name',
        'domaine',
        'nbre_annees_experience',
        'references',
        'statut',
        'file',
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

    public function getCVFormateurs()
    {
        $cvPath = $this->file;
        return "/storage/" . $cvPath;
    }
}
