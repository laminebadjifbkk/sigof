<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NumeroAttestationService
{
    /**
     * Génère un numéro d'attestation unique, en incrémentant
     * de façon atomique le compteur de l'année en base de données.
     *
     * Aucun état n'est conservé en mémoire PHP : chaque appel
     * incrémente réellement le compteur. Il faut donc l'appeler
     * une seule fois par attestation réellement créée, jamais
     * pour un simple aperçu.
     */
    public static function generer(string $typeFormation, string $niveauQualification, ?int $annee = null): string
    {
        $annee = $annee ?? now()->year;

        $sequence = DB::transaction(function () use ($annee) {
            // Verrouille la ligne de l'année (ou la crée si elle n'existe pas)
            // pour empêcher deux requêtes concurrentes d'obtenir le même numéro.
            $row = DB::table('sequences_attestations')
                ->where('annee', $annee)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                DB::table('sequences_attestations')->insert([
                    'annee'          => $annee,
                    'dernier_numero' => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                return 1;
            }

            $nouveauNumero = $row->dernier_numero + 1;

            DB::table('sequences_attestations')
                ->where('annee', $annee)
                ->update([
                    'dernier_numero' => $nouveauNumero,
                    'updated_at'     => now(),
                ]);

            return $nouveauNumero;
        });

        $codeType = match (strtolower($typeFormation)) {
            'collective'   => 'C',
            'individuelle' => 'I',
            default        => 'X',
        };

        $codeQualification = match (strtolower(trim($niveauQualification))) {
            'attestation'            => 'A',
            'titre de qualification' => 'T',
            default                  => 'X',
        };

        $sequenceFormatee = str_pad((string) $sequence, 6, '0', STR_PAD_LEFT);

        $base     = "{$codeQualification}{$codeType}-{$annee}-{$sequenceFormatee}";
        $checksum = self::calculerChecksum($base);

        return "{$base}-{$checksum}";
    }

    /**
     * Remet à zéro le compteur d'une année donnée.
     * À utiliser uniquement en cas de réinitialisation volontaire (ex: tests, reset manuel).
     */
    public static function reset(?int $annee = null): void
    {
        $annee = $annee ?? now()->year;

        DB::table('sequences_attestations')
            ->where('annee', $annee)
            ->update(['dernier_numero' => 0]);
    }

    private static function calculerChecksum(string $chaine): string
    {
        $somme = 0;
        foreach (str_split(preg_replace('/[^A-Z0-9]/', '', strtoupper($chaine))) as $char) {
            $somme += ord($char);
        }
        return chr(65 + ($somme % 26));
    }

    public static function verifier(string $numero): bool
    {
        $parts = explode('-', $numero); // ex: AI-2025-0000002-B => 4 segments

        if (count($parts) !== 4) {
            return false;
        }

        $checksum = array_pop($parts);
        $base     = implode('-', $parts);

        return self::calculerChecksum($base) === $checksum;
    }
}
