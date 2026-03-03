<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetfsPayment extends Model
{
    protected $fillable = ['detfs_budget_item_id', 'date_paiement', 'montant', 'mode_paiement', 'reference'];

    public function budgetItem()
    {
        return $this->belongsTo(DetfsBudgetItem::class);
    }
}