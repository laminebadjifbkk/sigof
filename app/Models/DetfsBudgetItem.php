<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetfsBudgetItem extends Model
{
    protected $fillable = ['detfs_id', 'budget_label_id', 'unite', 'quantite', 'prix_unitaire', 'montant', 'notes'];

    public function detfs()
    {
        return $this->belongsTo(Detf::class);
    }

    public function label()
    {
        return $this->belongsTo(BudgetLabel::class, 'budget_label_id');
    }

    public function suppliers()
    {
        return $this->hasMany(DetfsSupplier::class);
    }

    public function payments()
    {
        return $this->hasMany(DetfsPayment::class);
    }
}