<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetfsSupplier extends Model
{
    protected $fillable = ['detfs_budget_item_id', 'nom', 'contact', 'adresse'];

    public function budgetItem()
    {
        return $this->belongsTo(DetfsBudgetItem::class);
    }
}