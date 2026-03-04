<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetLabel extends Model
{
    protected $fillable = ['libelle', 'type', 'description'];

    public function budgetItems()
    {
        return $this->hasMany(DetfsBudgetItem::class, 'label_id');
    }
}
