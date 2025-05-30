<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Fonction
 *
 * @property int $id
 * @property string $uuid
 * @property string|null $name
 * @property string|null $sigle
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Employee[] $employees
 *
 * @package App\Models
 */
class Fonction extends Model
{

    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'fonctions';

    protected $fillable = [
        'uuid',
        'name',
        'sigle',
    ];

    /* public function getRouteKeyName()
    {
        return 'uuid';
    } */
   
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'fonctions_id');
    }
}
