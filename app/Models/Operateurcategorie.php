<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Operateurcategorie extends Model
{
    use HasFactory;
    use SoftDeletes;
    use \App\Helpers\UuidForKey;
    protected $table = 'operateurcategories';

    protected $fillable = [
        'uuid',
        'name',
    ];

    public function operateurs()
    {
        return $this->hasMany(Operateur::class, 'operateurcategories_id');
    }
}
