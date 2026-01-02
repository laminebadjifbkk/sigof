<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcEmployeeMission extends Model
{
    protected $table = 'parc_employee_mission';

    protected $fillable = ['mission_id', 'employee_id', 'role', 'vehicule_id'];

    public function mission()
    {
        return $this->belongsTo(ParcMission::class, 'mission_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
