<?php

namespace App\Observers;

use App\Models\SuiviPostIndividuel;
use App\Models\SuiviPostIndividuelHistory;

class SuiviPostIndividuelObserver
{
    /**
     * CREATE
     */
    public function created(SuiviPostIndividuel $suivi)
    {
        SuiviPostIndividuelHistory::create([
            'suivi_post_individuel_id' => $suivi->id,
            'action' => 'created',
            'old_values' => null,
            'new_values' => $suivi->getAttributes(),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * UPDATE
     */
    public function updated(SuiviPostIndividuel $suivi)
    {
        SuiviPostIndividuelHistory::create([
            'suivi_post_individuel_id' => $suivi->id,
            'action' => 'updated',
            'old_values' => $suivi->getOriginal(),
            'new_values' => $suivi->getChanges(),
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * DELETE (optionnel)
     */
    public function deleted(SuiviPostIndividuel $suivi)
    {
        SuiviPostIndividuelHistory::create([
            'suivi_post_individuel_id' => $suivi->id,
            'action' => 'deleted',
            'old_values' => $suivi->getOriginal(),
            'new_values' => null,
            'user_id' => auth()->id(),
        ]);
    }
}