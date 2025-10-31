<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Inscription extends Model
{
    protected $fillable = [
        'structure',
        'nom',
        'fonction',
        'telephone',
        'email',
        'commentaire',
        'autre',
    ];

   /*  protected static function booted()
    {
        static::creating(function ($inscription) {
            $inscription->uuid = (string) Str::uuid();
        });
    } */

}
