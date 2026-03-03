<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Detf
 * 
 * @property int $id
 * @property string $uuid
 * @property string $numero
 * @property string|null $titre1
 * @property string|null $titre2
 * @property Carbon|null $date1
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Formation[] $formations
 *
 * @package App\Models
 */
class Detf extends Model
{

	use HasFactory;
	use SoftDeletes;
	use \App\Helpers\UuidForKey;
	protected $table = 'detfs';

	protected $dates = [
		'date1'
	];

	protected $casts = [
		'date1' => 'date',
	];

	protected $fillable = [
		'uuid',
		'numero',
		'titre1',
		'titre2',
		'date1',
		'operateurs_id',
		'ingenieurs_id',
		'montant_prevu',
		'montant_realise',
		'etat',
		'description',
	];

	public function formations()
	{
		return $this->hasMany(Formation::class, 'detfs_id');
	}

	public function ingenieur()
	{
		return $this->belongsTo(Ingenieur::class, 'ingenieurs_id');
	}

	public function operateur()
	{
		return $this->belongsTo(Operateur::class, 'operateurs_id');
	}
}
