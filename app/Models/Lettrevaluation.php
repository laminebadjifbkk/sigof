<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lettrevaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'titre',
        'contenu',
        'users_id',
        'formations_id',
        'operateurs_id',
        'onfpevaluateurs_id',
        'evaluateurs_id',
    ];

    protected static function boot()
    {
        parent::boot();

        // Génère automatiquement un UUID lors de la création
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    // Relations
    public function auteur()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formations_id');
    }

    public function operateur()
    {
        return $this->belongsTo(Operateur::class, 'operateurs_id');
    }

    public function evaluateurOnfp()
    {
        return $this->belongsTo(User::class, 'onfpevaluateurs_id');
    }

    public function autreEvaluateur()
    {
        return $this->belongsTo(User::class, 'evaluateurs_id');
    }
}
