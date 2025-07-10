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

/**
 * Class Evaluateur
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $lastname
 * @property string|null $telephone
 * @property string|null $email
 * @property string|null $adresse
 * @property Carbon|null $date
 * @property string|null $fonction
 * @property string|null $appreciation
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Evaluation[] $evaluations
 *
 * @package App\Models
 */
class Evaluateur extends Model
{

    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'evaluateurs';

    protected $dates = [
        'date',
    ];

    protected $fillable = [
        'uuid',
        'name',
        'lastname',
        'telephone',
        'email',
        'adresse',
        'date',
        'fonction',
        'appreciation',
    ];

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'evaluateurs_id');
    }

    /* public function formations()
    {
        return $this->hasMany(Formation::class, 'evaluateurs_id');
    } */

    public function formations()
    {
        return $this->belongsToMany(Formation::class, 'evaluateur_formation');
    }

    public function lettrevaluation()
    {
        return $this->hasOne(Lettrevaluation::class, 'evaluateurs_id');
    }
}
