<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NumeroAttestationService
{
    private static ?int $compteurSession = null;

    public static function generer(string $typeFormation, string $niveauQualification, ?int $annee = null): string
    {
        /* $annee = $annee ?? now()->year; */
        /* $annee = $annee ?? now()->format('y'); */
        $annee = $annee ?? now()->year;
        $annee = substr((string) $annee, -2);

        // Initialise le compteur une seule fois, toutes années confondues
        /* if (self::$compteurSession === null) {
            self::$compteurSession = DB::table('listecollectives')
                ->whereNotNull('numero_attestation')
                ->count()
                + DB::table('individuelles')
                ->whereNotNull('numero_attestation')
                ->count();
        } */

        if (self::$compteurSession === null) {
            $maxCollective = DB::table('listecollectives')
                ->whereNotNull('numero_attestation')
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(numero_attestation, '-', -2), '-', 1) AS UNSIGNED)) as max_seq")
                ->value('max_seq') ?? 0;

            $maxIndividuelle = DB::table('individuelles')
                ->whereNotNull('numero_attestation')
                ->selectRaw("MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(numero_attestation, '-', -2), '-', 1) AS UNSIGNED)) as max_seq")
                ->value('max_seq') ?? 0;

            self::$compteurSession = max($maxCollective, $maxIndividuelle);
        }

        self::$compteurSession++;


        $codeType = match (strtolower($typeFormation)) {
            'collective'  => 'C',
            'individuelle' => 'I',
            default       => 'X',
        };

        $codeQualification = match (strtolower(trim($niveauQualification))) {
            'attestation'            => 'A',
            'titre de qualification' => 'T',
            default                  => 'X',
        };

        $sequenceFormatee = str_pad(self::$compteurSession, 7, '0', STR_PAD_LEFT);
        /* $base             = "TIT-{$annee}-ONFP-{$sequenceFormatee}"; */
        /* $base             = "ONFP-{$annee}-{$sequenceFormatee}"; */
        /* $base = "{$codeQualification}-{$codeType}-{$annee}{$sequenceFormatee}"; */
        $base = "{$codeQualification}{$codeType}-{$annee}{$sequenceFormatee}";
        $checksum         = self::calculerChecksum($base);

        /* return "{$base}-{$checksum}"; */
        return "{$base}-{$checksum}";
    }

    public static function reset(): void
    {
        self::$compteurSession = null;
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
        $parts = explode('-', $numero);
        if (count($parts) !== 5) return false;

        $checksum = array_pop($parts);
        $base     = implode('-', $parts);

        return self::calculerChecksum($base) === $checksum;
    }
}
