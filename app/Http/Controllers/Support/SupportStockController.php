<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportStock;
use App\Models\Support\SupportDocumentStock;
use App\Models\Support\SupportMouvementStock;
use App\Models\Support\SupportInventaireStock;
use Illuminate\Support\Facades\DB;

class SupportStockController extends Controller
{
    public function dashboard()
    {
        abort_unless(auth()->user()->can('support.stocks.view'), 403);

        $articlesActifs = SupportArticleStock::query()
            ->actifs()
            ->count();

        $stocksActifs = SupportStock::query()
            ->whereHas('article', fn ($q) => $q->where('actif', true));

        $quantiteTotale = (clone $stocksActifs)->sum('quantite');
        $valeurTotale = (clone $stocksActifs)->sum('valeur_stock');

        $documentsEnAttente = SupportDocumentStock::query()
            ->where('statut', 'EN_ATTENTE_VALIDATION')
            ->count();

        $inventairesEnCours = SupportInventaireStock::query()
            ->whereIn('statut', ['EN_COURS', 'EN_ATTENTE_VALIDATION'])
            ->count();

        $articlesSousSeuil = DB::table('support_articles_stocks as a')
            ->leftJoin('support_stocks as s', 's.support_article_stock_id', '=', 'a.id')
            ->whereNull('a.deleted_at')
            ->where('a.actif', true)
            ->where('a.stock_minimum', '>', 0)
            ->whereRaw('COALESCE(s.quantite, 0) < a.stock_minimum')
            ->count();

        $listeArticlesSousSeuil = DB::table('support_articles_stocks as a')
            ->leftJoin('support_stocks as s', 's.support_article_stock_id', '=', 'a.id')
            ->leftJoin('support_unites_stocks as u', 'u.id', '=', 'a.support_unite_stock_id')
            ->whereNull('a.deleted_at')
            ->where('a.actif', true)
            ->where('a.stock_minimum', '>', 0)
            ->whereRaw('COALESCE(s.quantite, 0) < a.stock_minimum')
            ->select([
                'a.id',
                'a.reference',
                'a.designation',
                'a.stock_minimum',
                'u.libelle as unite',
                's.quantite',
            ])
            ->orderBy('a.designation')
            ->limit(8)
            ->get();

        $mouvementsRecents = SupportMouvementStock::query()
            ->with(['article', 'document', 'createur'])
            ->orderByDesc('date_mouvement')
            ->limit(10)
            ->get();

        $documentsRecents = SupportDocumentStock::query()
            ->with(['fournisseur', 'createur'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $stats = [
            'articles' => $articlesActifs,
            'quantite' => $quantiteTotale,
            'valeur' => $valeurTotale,
            'sous_seuil' => $articlesSousSeuil,
            'documents_attente' => $documentsEnAttente,
            'inventaires' => $inventairesEnCours,
        ];

        return view('support.stocks.dashboard', compact(
            'stats',
            'listeArticlesSousSeuil',
            'mouvementsRecents',
            'documentsRecents'
        ));
    }
}
