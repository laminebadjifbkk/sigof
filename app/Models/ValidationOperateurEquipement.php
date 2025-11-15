<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValidationOperateurEquipement extends Model
{
    use HasFactory;
	use SoftDeletes;
	use \App\Helpers\UuidForKey;
	protected $table = 'validationoperateurequipements';

    
    
	protected $casts = [
		'operateurformateurs_id' => 'int',
	];

	protected $fillable = [
		'uuid',
		'validated_id',
		'action',
		'session',
		'motif',
		'operateurequipements_id'
	];

    
	public function operateurequipement()
	{
		return $this->belongsTo(Operateurequipement::class, 'operateurequipements_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'validated_id');
	}
}
