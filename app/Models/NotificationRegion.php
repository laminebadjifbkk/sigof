<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRegion extends Model
{
    use HasFactory;
    
	protected $fillable = [
		'region',
		'dernier_palier_notifie',
	];

    protected $casts = [
        'region' => 'integer',
        'dernier_palier_notifie' => 'integer',
    ];

    protected $table = 'notifications_region';

    /* public $timestamps = true;

    public function region()
    {
        return $this->belongsTo(Region::class, 'region');
    } */
}
