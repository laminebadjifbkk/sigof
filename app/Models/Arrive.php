<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Arrive
 *
 * @property int $id
 * @property string|null $numero_arrive
 * @property string|null $type
 * @property string $uuid
 * @property int $courriers_id
 * @property string|null $deleted_at
 * @property Carbon|null $jour_imputation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Courrier $courrier
 *
 * @package App\Models
 */
class Arrive extends Model
{

    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'arrives';

    protected $casts = [
        'courriers_id'    => 'int',
        'jour_imputation' => 'datetime',
    ];

    protected $dates = [
        'jour_imputation',
    ];

    protected $fillable = [
        'numero_arrive',
        'jour_imputation',
        'uuid',
        'courriers_id',
        'type',
    ];
    // Ajoute cette méthode pour forcer l'utilisation de l'uuid dans les routes
   /*  public function getRouteKeyName()
    {
        return 'uuid';
    } */
    // Ajoute cette méthode pour générer un UUID lors de la création du modèle
    // ou de la mise à jour
    /* protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    } */

    public function courrier()
    {
        return $this->belongsTo(Courrier::class, 'courriers_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'courrierarrivesemployes', 'arrives_id', 'employees_id')
            ->withPivot('id', 'deleted_at')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'courrierarrivesusers', 'arrives_id', 'users_id')
            ->withPivot('id', 'deleted_at')
            ->withTimestamps();
    }
}
