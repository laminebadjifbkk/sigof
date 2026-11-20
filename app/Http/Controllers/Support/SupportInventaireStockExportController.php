<?php

namespace App\Http\Controllers\Support;

use App\Exports\Support\SupportInventaireExport;
use App\Http\Controllers\Controller;
use App\Models\Support\SupportInventaireStock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SupportInventaireStockExportController extends Controller
{
    private function autoriser(Request $request): void
    {
        abort_unless(
            $request->user()->can('support.stocks.view'),
            403
        );
    }

    private function donnees(int $id): array
    {
        $inventaire = SupportInventaireStock::query()
            ->with([
                'createur',
                'validateur',
                'lignes.article.unite',
                'lignes.compteur',
            ])
            ->findOrFail($id);

        $lignes = $inventaire->lignes
            ->sortBy(fn ($ligne) => $ligne->article?->designation ?? '')
            ->values();

        $nombreArticles = $lignes->count();

        $nombreComptes = $lignes
            ->filter(fn ($ligne) => $ligne->quantite_comptee !== null)
            ->count();

        $ecartsPositifs = $lignes
            ->filter(fn ($ligne) => $ligne->ecart !== null && (float) $ligne->ecart > 0)
            ->count();

        $ecartsNegatifs = $lignes
            ->filter(fn ($ligne) => $ligne->ecart !== null && (float) $ligne->ecart < 0)
            ->count();

        $ecartsNuls = $lignes
            ->filter(fn ($ligne) => $ligne->ecart !== null && (float) $ligne->ecart == 0)
            ->count();

        $totalTheorique = $lignes->sum(
            fn ($ligne) => (float) $ligne->quantite_theorique
        );

        $totalCompte = $lignes->sum(
            fn ($ligne) => $ligne->quantite_comptee === null
                ? 0
                : (float) $ligne->quantite_comptee
        );

        $totalEcart = $lignes->sum(
            fn ($ligne) => $ligne->ecart === null
                ? 0
                : (float) $ligne->ecart
        );

        return compact(
            'inventaire',
            'lignes',
            'nombreArticles',
            'nombreComptes',
            'ecartsPositifs',
            'ecartsNegatifs',
            'ecartsNuls',
            'totalTheorique',
            'totalCompte',
            'totalEcart'
        );
    }

    private function exporterPdf(array $donnees, string $titre, array $colonnes, array $lignes, string $nom)
    {
        return Pdf::loadView('support.stocks.inventaires.exports.pdf', [
            'inventaire' => $donnees['inventaire'],
            'titre' => $titre,
            'colonnes' => $colonnes,
            'lignes' => $lignes,
            'statistiques' => $donnees,
        ])
            ->setPaper('a4', 'landscape')
            ->download($nom);
    }

    private function nomFichier($inventaire, string $suffixe): string
    {
        return $inventaire->reference . '_' . $suffixe;
    }

    /**
     * Fiche de comptage Excel.
     */
    public function ficheComptageExcel(Request $request, int $id)
    {
        $this->autoriser($request);
        $data = $this->donnees($id);

        $lignes = $data['lignes']->map(fn ($ligne) => [
            $ligne->article?->reference ?? '',
            $ligne->article?->designation ?? '',
            $ligne->article?->unite?->libelle ?? '',
            (float) $ligne->quantite_theorique,
            $ligne->quantite_comptee === null ? '' : (float) $ligne->quantite_comptee,
            $ligne->observations ?? '',
        ])->all();

        return Excel::download(
            new SupportInventaireExport(
                ['Référence', 'Désignation', 'Unité', 'Quantité théorique', 'Quantité comptée', 'Observations'],
                $lignes
            ),
            $this->nomFichier($data['inventaire'], 'fiche_comptage.xlsx')
        );
    }

    /**
     * Fiche de comptage PDF.
     */
    public function ficheComptagePdf(Request $request, int $id)
    {
        $this->autoriser($request);
        $data = $this->donnees($id);

        $lignes = $data['lignes']->map(fn ($ligne) => [
            $ligne->article?->reference ?? '',
            $ligne->article?->designation ?? '',
            $ligne->article?->unite?->libelle ?? '',
            number_format((float) $ligne->quantite_theorique, 3, ',', ' '),
            $ligne->quantite_comptee === null
                ? ''
                : number_format((float) $ligne->quantite_comptee, 3, ',', ' '),
            $ligne->observations ?? '',
        ])->all();

        return $this->exporterPdf(
            $data,
            'FICHE DE COMPTAGE PHYSIQUE',
            ['Référence', 'Désignation', 'Unité', 'Théorique', 'Compté', 'Observations'],
            $lignes,
            $this->nomFichier($data['inventaire'], 'fiche_comptage.pdf')
        );
    }

    /**
     * État des écarts Excel.
     */
    public function ecartsExcel(Request $request, int $id)
    {
        $this->autoriser($request);
        $data = $this->donnees($id);

        $lignes = $data['lignes']->map(fn ($ligne) => [
            $ligne->article?->reference ?? '',
            $ligne->article?->designation ?? '',
            $ligne->article?->unite?->libelle ?? '',
            (float) $ligne->quantite_theorique,
            $ligne->quantite_comptee === null ? '' : (float) $ligne->quantite_comptee,
            $ligne->ecart === null ? '' : (float) $ligne->ecart,
            $ligne->observations ?? '',
        ])->all();

        return Excel::download(
            new SupportInventaireExport(
                ['Référence', 'Désignation', 'Unité', 'Quantité théorique', 'Quantité comptée', 'Écart', 'Observations'],
                $lignes
            ),
            $this->nomFichier($data['inventaire'], 'etat_ecarts.xlsx')
        );
    }

    /**
     * État des écarts PDF.
     */
    public function ecartsPdf(Request $request, int $id)
    {
        $this->autoriser($request);
        $data = $this->donnees($id);

        $lignes = $data['lignes']->map(fn ($ligne) => [
            $ligne->article?->reference ?? '',
            $ligne->article?->designation ?? '',
            $ligne->article?->unite?->libelle ?? '',
            number_format((float) $ligne->quantite_theorique, 3, ',', ' '),
            $ligne->quantite_comptee === null
                ? '—'
                : number_format((float) $ligne->quantite_comptee, 3, ',', ' '),
            $ligne->ecart === null
                ? '—'
                : number_format((float) $ligne->ecart, 3, ',', ' '),
            $ligne->observations ?? '',
        ])->all();

        return $this->exporterPdf(
            $data,
            'ÉTAT DES ÉCARTS D’INVENTAIRE',
            ['Référence', 'Désignation', 'Unité', 'Théorique', 'Compté', 'Écart', 'Observations'],
            $lignes,
            $this->nomFichier($data['inventaire'], 'etat_ecarts.pdf')
        );
    }

    /**
     * Récapitulatif Excel.
     */
    public function recapitulatifExcel(Request $request, int $id)
    {
        $this->autoriser($request);
        $data = $this->donnees($id);
        $inventaire = $data['inventaire'];

        $lignes = [
            ['Référence', $inventaire->reference],
            ['Libellé', $inventaire->libelle],
            ['Description', $inventaire->description ?? ''],
            ['Statut', str_replace('_', ' ', $inventaire->statut)],
            ['Date de début', $inventaire->date_debut?->format('d/m/Y') ?? ''],
            ['Date de fin', $inventaire->date_fin?->format('d/m/Y') ?? ''],
            ['Créateur', trim(($inventaire->createur?->firstname ?? '') . ' ' . ($inventaire->createur?->name ?? ''))],
            ['Validateur', trim(($inventaire->validateur?->firstname ?? '') . ' ' . ($inventaire->validateur?->name ?? ''))],
            ['Date de soumission', $inventaire->date_soumission?->format('d/m/Y H:i') ?? ''],
            ['Date de validation', $inventaire->date_validation?->format('d/m/Y H:i') ?? ''],
            ['Nombre d’articles', (int) $data['nombreArticles']],
            ['Articles comptés', (int) $data['nombreComptes']],
            ['Écarts positifs', (int) $data['ecartsPositifs']],
            ['Écarts négatifs', (int) $data['ecartsNegatifs']],
            ['Écarts nuls', (int) $data['ecartsNuls']],
            ['Observations', $inventaire->observations ?? ''],
            ['Motif du rejet', $inventaire->motif_rejet ?? ''],
        ];

        return Excel::download(
            new SupportInventaireExport(['Indicateur', 'Valeur'], $lignes),
            $this->nomFichier($inventaire, 'recapitulatif.xlsx')
        );
    }

    /**
     * Récapitulatif PDF.
     */
    public function recapitulatifPdf(Request $request, int $id)
    {
        $this->autoriser($request);
        $data = $this->donnees($id);
        $inventaire = $data['inventaire'];

        $lignes = [
            ['Référence', $inventaire->reference],
            ['Libellé', $inventaire->libelle],
            ['Description', $inventaire->description ?? '—'],
            ['Statut', str_replace('_', ' ', $inventaire->statut)],
            ['Date de début', $inventaire->date_debut?->format('d/m/Y') ?? '—'],
            ['Date de fin', $inventaire->date_fin?->format('d/m/Y') ?? '—'],
            ['Créateur', trim(($inventaire->createur?->firstname ?? '') . ' ' . ($inventaire->createur?->name ?? ''))],
            ['Validateur', trim(($inventaire->validateur?->firstname ?? '') . ' ' . ($inventaire->validateur?->name ?? ''))],
            ['Date de soumission', $inventaire->date_soumission?->format('d/m/Y H:i') ?? '—'],
            ['Date de validation', $inventaire->date_validation?->format('d/m/Y H:i') ?? '—'],
            ['Nombre d’articles', (int) $data['nombreArticles']],
            ['Articles comptés', (int) $data['nombreComptes']],
            ['Écarts positifs', (int) $data['ecartsPositifs']],
            ['Écarts négatifs', (int) $data['ecartsNegatifs']],
            ['Écarts nuls', (int) $data['ecartsNuls']],
            ['Observations', $inventaire->observations ?? '—'],
            ['Motif du rejet', $inventaire->motif_rejet ?? '—'],
        ];

        return $this->exporterPdf(
            $data,
            'RÉCAPITULATIF DE L’INVENTAIRE',
            ['Indicateur', 'Valeur'],
            $lignes,
            $this->nomFichier($inventaire, 'recapitulatif.pdf')
        );
    }
}
