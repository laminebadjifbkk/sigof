<?php

namespace App\Services\Support;

use App\Models\Support\SupportSequence;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class SupportStockReferenceService
{
    private const TYPES = [
        'ARTICLE' => [
            'code' => 'STOCK_ARTICLE',
            'prefixe' => 'ART',
        ],
        'RECEPTION' => [
            'code' => 'STOCK_RECEPTION',
            'prefixe' => 'REC',
        ],
        'SORTIE' => [
            'code' => 'STOCK_SORTIE',
            'prefixe' => 'SOR',
        ],
        'RETOUR' => [
            'code' => 'STOCK_RETOUR',
            'prefixe' => 'RET',
        ],
        'AJUSTEMENT_POSITIF' => [
            'code' => 'STOCK_AJUSTEMENT_POSITIF',
            'prefixe' => 'AJP',
        ],
        'AJUSTEMENT_NEGATIF' => [
            'code' => 'STOCK_AJUSTEMENT_NEGATIF',
            'prefixe' => 'AJN',
        ],
        'INVENTAIRE' => [
            'code' => 'STOCK_INVENTAIRE',
            'prefixe' => 'INV',
        ],
    ];

    public function generer(string $type): string
    {
        $type = strtoupper(trim($type));

        if (!isset(self::TYPES[$type])) {
            throw new InvalidArgumentException(
                "Type de référence de stock non pris en charge : {$type}"
            );
        }

        $configuration = self::TYPES[$type];

        return DB::transaction(function () use ($configuration) {
            $annee = (int) now()->format('Y');

            SupportSequence::query()->firstOrCreate(
                [
                    'code' => $configuration['code'],
                    'annee' => $annee,
                ],
                [
                    'dernier_numero' => 0,
                ]
            );

            $sequence = SupportSequence::query()
                ->where('code', $configuration['code'])
                ->where('annee', $annee)
                ->lockForUpdate()
                ->firstOrFail();

            $sequence->dernier_numero++;
            $sequence->save();

            return sprintf(
                '%s-%d-%06d',
                $configuration['prefixe'],
                $annee,
                $sequence->dernier_numero
            );
        }, 5);
    }
}
