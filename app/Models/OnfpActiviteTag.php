<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnfpActiviteTag extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'onfp_activite_tags';
    protected $fillable = ['nom','slug','couleur','description','actif'];
    protected $casts = ['actif'=>'boolean'];
    public function activites()
    {
        return $this->belongsToMany(
            OnfpActivite::class, 'onfp_activite_tag_pivot',
            'tag_id', 'activite_id'
        )->withTimestamps();
    }
}
