<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Exports\Support\SupportStockRapportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SupportStockRapportController extends Controller
{
    private function autoriser(): void
    {
        abort_unless(
            auth()->user()?->can('support.stocks.view'),
            403
        );
    }

    private function stocksQuery()
    {
        return DB::table('support_articles_stocks as a')
            ->leftJoin('support_stocks as s', 's.support_article_stock_id', '=', 'a.id')
            ->leftJoin('support_categories_stocks as c', 'c.id', '=', 'a.support_categorie_stock_id')
            ->whereNull('a.deleted_at')
            ->where('a.actif', 1)
            ->select([
                'a.id',
                'a.reference',
                'a.designation',
                'a.stock_minimum',
                'a.stock_maximum',
                'a.support_categorie_stock_id',
                'c.libelle as categorie',
                DB::raw('COALESCE(s.quantite, 0) as quantite'),
                DB::raw('COALESCE(s.cout_moyen_pondere, 0) as cout_moyen_pondere'),
                DB::raw('COALESCE(s.valeur_stock, 0) as valeur_stock'),
                's.verrouille',
            ]);
    }

    private function appliquerFiltresStocks($query, Request $request)
    {
        if ($request->filled('article_id')) {
            $query->where('a.id', $request->integer('article_id'));
        }

        if ($request->filled('categorie_id')) {
            $query->where('a.support_categorie_stock_id', $request->integer('categorie_id'));
        }

        return $query;
    }

    private function mouvementsQuery(Request $request)
    {
        $query = DB::table('support_mouvements_stocks as m')
            ->join('support_articles_stocks as a', 'a.id', '=', 'm.support_article_stock_id')
            ->leftJoin('support_documents_stocks as d', 'd.id', '=', 'm.support_document_stock_id')
            ->select([
                'm.id',
                'm.date_mouvement',
                'm.type_mouvement',
                'm.quantite',
                'm.cout_unitaire',
                'm.valeur_mouvement',
                'm.quantite_avant',
                'm.quantite_apres',
                'a.reference',
                'a.designation',
                'd.reference as document_reference',
                'd.type_document',
            ]);

        if ($request->filled('article_id')) {
            $query->where('m.support_article_stock_id', $request->integer('article_id'));
        }

        if ($request->filled('type_mouvement')) {
            $query->where('m.type_mouvement', $request->input('type_mouvement'));
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('m.date_mouvement', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('m.date_mouvement', '<=', $request->input('date_fin'));
        }

        return $query->orderByDesc('m.date_mouvement')->orderByDesc('m.id');
    }

    public function index(Request $request)
    {
        $this->autoriser();

        $query = $this->appliquerFiltresStocks($this->stocksQuery(), $request);

        $stocks = $query->orderBy('a.designation')->paginate(25)->withQueryString();

        $articles = DB::table('support_articles_stocks')
            ->where('actif', 1)
            ->whereNull('deleted_at')
            ->orderBy('designation')
            ->get(['id', 'reference', 'designation']);

        $categories = DB::table('support_categories_stocks')
            ->where('actif', 1)
            ->whereNull('deleted_at')
            ->orderBy('libelle')
            ->get(['id', 'libelle']);

        $totaux = DB::table('support_articles_stocks as a')
            ->leftJoin('support_stocks as s', 's.support_article_stock_id', '=', 'a.id')
            ->whereNull('a.deleted_at')
            ->where('a.actif', 1)
            ->selectRaw('COUNT(*) as nombre_articles')
            ->selectRaw('COALESCE(SUM(s.quantite), 0) as quantite_totale')
            ->selectRaw('COALESCE(SUM(s.valeur_stock), 0) as valeur_totale')
            ->first();

        return view('support.stocks.rapports.index', compact(
            'stocks', 'articles', 'categories', 'totaux'
        ));
    }

    public function alertes(Request $request)
    {
        $this->autoriser();

        $query = $this->appliquerFiltresStocks($this->stocksQuery(), $request)
            ->where(function ($q) {
                $q->whereRaw('COALESCE(s.quantite, 0) <= 0')
                  ->orWhere(function ($sub) {
                      $sub->whereRaw('a.stock_minimum > 0')
                          ->whereRaw('COALESCE(s.quantite, 0) <= a.stock_minimum');
                  });
            });

        $alertes = $query
            ->orderByRaw('CASE WHEN COALESCE(s.quantite, 0) <= 0 THEN 0 ELSE 1 END')
            ->orderByRaw('COALESCE(s.quantite, 0) ASC')
            ->paginate(25)
            ->withQueryString();

        $articles = DB::table('support_articles_stocks')
            ->where('actif', 1)
            ->whereNull('deleted_at')
            ->orderBy('designation')
            ->get(['id', 'reference', 'designation']);

        $categories = DB::table('support_categories_stocks')
            ->where('actif', 1)
            ->whereNull('deleted_at')
            ->orderBy('libelle')
            ->get(['id', 'libelle']);

        $nombreRuptures = (clone $this->stocksQuery())
            ->whereRaw('COALESCE(s.quantite, 0) <= 0')
            ->count();

        return view('support.stocks.rapports.alertes', compact(
            'alertes', 'articles', 'categories', 'nombreRuptures'
        ));
    }

    public function mouvements(Request $request)
    {
        $this->autoriser();

        $request->validate([
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'article_id' => ['nullable', 'integer', 'exists:support_articles_stocks,id'],
            'type_mouvement' => ['nullable', 'string', 'max:60'],
        ]);

        $mouvements = $this->mouvementsQuery($request)
            ->paginate(30)->withQueryString();

        $articles = DB::table('support_articles_stocks')
            ->whereNull('deleted_at')->orderBy('designation')
            ->get(['id', 'reference', 'designation']);

        $types = DB::table('support_mouvements_stocks')
            ->select('type_mouvement')->distinct()
            ->orderBy('type_mouvement')->pluck('type_mouvement');

        return view('support.stocks.rapports.mouvements', compact(
            'mouvements', 'articles', 'types'
        ));
    }

    private function lignesEtat(Request $request): array
    {
        return $this->appliquerFiltresStocks($this->stocksQuery(), $request)
            ->orderBy('a.designation')
            ->get()
            ->map(fn ($s) => [
                $s->reference,
                $s->designation,
                $s->categorie ?? '',
                (float) $s->quantite,
                (float) $s->stock_minimum,
                (float) $s->cout_moyen_pondere,
                (float) $s->valeur_stock,
            ])->all();
    }

    private function lignesMouvements(Request $request): array
    {
        return $this->mouvementsQuery($request)->get()
            ->map(fn ($m) => [
                $m->date_mouvement,
                $m->document_reference ?? '',
                $m->type_mouvement,
                $m->reference,
                $m->designation,
                (float) $m->quantite,
                (float) $m->cout_unitaire,
                (float) $m->valeur_mouvement,
                (float) $m->quantite_avant,
                (float) $m->quantite_apres,
            ])->all();
    }

    public function exporterEtatExcel(Request $request)
    {
        $this->autoriser();

        return Excel::download(
            new SupportStockRapportExport(
                $this->lignesEtat($request),
                ['Référence', 'Désignation', 'Catégorie', 'Quantité', 'Stock minimum', 'CMP', 'Valeur du stock']
            ),
            'etat-stocks-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function exporterMouvementsExcel(Request $request)
    {
        $this->autoriser();

        $request->validate([
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
        ]);

        return Excel::download(
            new SupportStockRapportExport(
                $this->lignesMouvements($request),
                ['Date', 'Document', 'Mouvement', 'Référence article', 'Désignation', 'Quantité', 'Coût unitaire', 'Valeur', 'Stock avant', 'Stock après']
            ),
            'mouvements-stocks-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function exporterEtatPdf(Request $request)
    {
        $this->autoriser();

        $lignes = $this->lignesEtat($request);
        $colonnes = ['Référence', 'Désignation', 'Catégorie', 'Quantité', 'Stock min.', 'CMP', 'Valeur'];

        return Pdf::loadView('support.stocks.rapports.pdf', [
            'titre' => 'État général des stocks',
            'colonnes' => $colonnes,
            'lignes' => $lignes,
        ])->setPaper('a4', 'landscape')
          ->download('etat-stocks-' . now()->format('Ymd') . '.pdf');
    }

    public function exporterMouvementsPdf(Request $request)
    {
        $this->autoriser();

        $request->validate([
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
        ]);

        $lignes = $this->lignesMouvements($request);
        $colonnes = ['Date', 'Document', 'Type', 'Référence', 'Article', 'Qté', 'Coût', 'Valeur', 'Avant', 'Après'];

        return Pdf::loadView('support.stocks.rapports.pdf', [
            'titre' => 'Historique des mouvements de stock',
            'colonnes' => $colonnes,
            'lignes' => $lignes,
        ])->setPaper('a4', 'landscape')
          ->download('mouvements-stocks-' . now()->format('Ymd') . '.pdf');
    }
}
