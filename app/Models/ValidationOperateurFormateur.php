<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValidationOperateurFormateur extends Model
{
    use HasFactory;
	use SoftDeletes;
	use \App\Helpers\UuidForKey;
	protected $table = 'validationoperateurformateurs';

    
	protected $casts = [
		'operateurformateurs_id' => 'int',
	];

	protected $fillable = [
		'uuid',
		'validated_id',
		'action',
		'session',
		'motif',
		'operateurformateurs_id'
	];

    
	public function operateurformateur()
	{
		return $this->belongsTo(Operateurformateur::class, 'operateurformateurs_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'validated_id');
	}
}
