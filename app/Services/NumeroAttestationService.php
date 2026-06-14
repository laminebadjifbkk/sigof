<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class NumeroAttestationService
{
    private static ?int $compteurSession = null;

    public static function generer(): string
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

        $sequenceFormatee = str_pad(self::$compteurSession, 6, '0', STR_PAD_LEFT);
        /* $base             = "TIT-{$annee}-ONFP-{$sequenceFormatee}"; */
        $base             = "ONFP-{$annee}-{$sequenceFormatee}";
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