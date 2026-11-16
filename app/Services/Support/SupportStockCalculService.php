<?php

namespace App\Services\Support;

use Brick\Math\BigDecimal;
use Brick\Math\RoundingMode;
use InvalidArgumentException;

class SupportStockCalculService
{
    private const ECHELLE_QUANTITE = 3;
    private const ECHELLE_VALEUR = 4;
    private const ECHELLE_CMP = 4;

    /**
     * Calcule la valeur d'une ligne ou d'un mouvement.
     */
    public function calculerValeur(
        string $quantite,
        string $coutUnitaire
    ): string {
        $qte = $this->quantitePositive($quantite);
        $cout = $this->valeurNonNegative($coutUnitaire);

        return (string) $qte
            ->multipliedBy($cout)
            ->toScale(self::ECHELLE_VALEUR, RoundingMode::HALF_UP);
    }

    /**
     * Calcule le CMP après une entrée (réception ou retour).
     */
    public function calculerCmpApresEntree(
        string $quantiteAvant,
        string $valeurAvant,
        string $quantiteEntree,
        string $coutEntree
    ): string {
        return $this->calculerCmpApresMouvementEntrant(
            $quantiteAvant,
            $valeurAvant,
            $quantiteEntree,
            $coutEntree
        );
    }

    /**
     * Calcule le CMP après un retour valorisé au coût d'origine.
     */
    public function calculerCmpApresRetour(
        string $quantiteAvant,
        string $valeurAvant,
        string $quantiteRetour,
        string $coutOrigine
    ): string {
        return $this->calculerCmpApresMouvementEntrant(
            $quantiteAvant,
            $valeurAvant,
            $quantiteRetour,
            $coutOrigine
        );
    }

    /**
     * Calcule la valeur d'une sortie au CMP courant.
     * Si la sortie vide entièrement le stock, la valeur exacte
     * du stock est reprise pour éviter un résidu d'arrondi.
     */
    public function calculerValeurSortie(
        string $quantiteSortie,
        string $quantiteDisponible,
        string $valeurStock,
        string $cmp
    ): string {
        $sortie = $this->quantitePositive($quantiteSortie);
        $disponible = $this->valeurNonNegative($quantiteDisponible);
        $valeur = $this->valeurNonNegative($valeurStock);
        $cout = $this->valeurNonNegative($cmp);

        if ($sortie->isGreaterThan($disponible)) {
            throw new InvalidArgumentException(
                'La quantité demandée dépasse le stock disponible.'
            );
        }

        if ($sortie->isEqualTo($disponible)) {
            return (string) $valeur->toScale(
                self::ECHELLE_VALEUR,
                RoundingMode::HALF_UP
            );
        }

        return (string) $sortie
            ->multipliedBy($cout)
            ->toScale(self::ECHELLE_VALEUR, RoundingMode::HALF_UP);
    }

    /**
     * Calcule la quantité restante après une sortie.
     */
    public function calculerQuantiteApresSortie(
        string $quantiteAvant,
        string $quantiteSortie
    ): string {
        $avant = $this->valeurNonNegative($quantiteAvant);
        $sortie = $this->quantitePositive($quantiteSortie);

        if ($sortie->isGreaterThan($avant)) {
            throw new InvalidArgumentException(
                'La quantité demandée dépasse le stock disponible.'
            );
        }

        return (string) $avant
            ->minus($sortie)
            ->toScale(self::ECHELLE_QUANTITE, RoundingMode::HALF_UP);
    }

    private function calculerCmpApresMouvementEntrant(
        string $quantiteAvant,
        string $valeurAvant,
        string $quantiteEntree,
        string $coutEntree
    ): string {
        $qteAvant = $this->valeurNonNegative($quantiteAvant);
        $valeurInitiale = $this->valeurNonNegative($valeurAvant);
        $qteEntree = $this->quantitePositive($quantiteEntree);
        $cout = $this->valeurNonNegative($coutEntree);

        $valeurEntree = $qteEntree->multipliedBy($cout)
            ->toScale(self::ECHELLE_VALEUR, RoundingMode::HALF_UP);

        $quantiteTotale = $qteAvant->plus($qteEntree);
        $valeurTotale = $valeurInitiale->plus($valeurEntree);

        return (string) $valeurTotale->dividedBy(
            $quantiteTotale,
            self::ECHELLE_CMP,
            RoundingMode::HALF_UP
        );
    }

    private function quantitePositive(string $valeur): BigDecimal
    {
        $decimal = BigDecimal::of($valeur);

        if (!$decimal->isGreaterThan(BigDecimal::zero())) {
            throw new InvalidArgumentException(
                'La quantité doit être strictement positive.'
            );
        }

        return $decimal;
    }

    private function valeurNonNegative(string $valeur): BigDecimal
    {
        $decimal = BigDecimal::of($valeur);

        if ($decimal->isLessThan(BigDecimal::zero())) {
            throw new InvalidArgumentException(
                'La valeur ne peut pas être négative.'
            );
        }

        return $decimal;
    }
}
