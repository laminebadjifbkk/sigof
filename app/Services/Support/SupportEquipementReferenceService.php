<?php

namespace App\Services\Support;

use App\Models\Support\SupportSequence;
use Illuminate\Support\Facades\DB;

class SupportEquipementReferenceService
{
    public function generer(): string
    {
        return DB::transaction(function () {
            $annee = (int) now()->format('Y');

            SupportSequence::query()->firstOrCreate(
                [
                    'code' => 'EQUIPEMENT',
                    'annee' => $annee,
                ],
                [
                    'dernier_numero' => 0,
                ]
            );

            $sequence = SupportSequence::query()
                ->where('code', 'EQUIPEMENT')
                ->where('annee', $annee)
                ->lockForUpdate()
                ->firstOrFail();

            $sequence->dernier_numero++;
            $sequence->save();

            return sprintf(
                'EQ-%d-%06d',
                $annee,
                $sequence->dernier_numero
            );
        }, 5);
    }
}
