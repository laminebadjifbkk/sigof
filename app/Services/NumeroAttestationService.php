<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class NumeroAttestationService
{
    private static ?int $compteurSession = null;

    public static function generer(string $typeFormation, string $niveauQualification, ?int $annee = null): string
    {
        $annee = $annee ?? now()->year;

        // Initialise le compteur une seule fois, toutes années confondues
        if (self::$compteurSession === null) {
            self::$compteurSession = DB::table('listecollectives')
                ->whereNotNull('numero_attestation')
                ->count()
                + DB::table('individuelles')
                ->whereNotNull('numero_attestation')
                ->count();
        }

        self::$compteurSession++;


        $codeType = match (strtolower($typeFormation)) {
            'collective'  => 'COL',
            'individuelle' => 'IND',
            default       => 'X',
        };

        $codeQualification = match (strtolower(trim($niveauQualification))) {
            'attestation'            => 'ATT',
            'titre de qualification' => 'TIT',
            default                  => 'TIT',
        };

        $sequenceFormatee = str_pad(self::$compteurSession, 6, '0', STR_PAD_LEFT);
        /* $base             = "TIT-{$annee}-ONFP-{$sequenceFormatee}"; */
        /* $base             = "ONFP-{$annee}-{$sequenceFormatee}"; */
        $base = "{$codeQualification}-{$codeType}-{$annee}-{$sequenceFormatee}";
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
        if (count($parts) !== 4) return false;

        $checksum = array_pop($parts);
        $base     = implode('-', $parts);

        return self::calculerChecksum($base) === $checksum;
    }
}
