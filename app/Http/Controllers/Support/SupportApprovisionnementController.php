<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Exports\Support\SupportStockRapportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class SupportApprovisionnementController extends Controller
{
    private const STATUTS = [
        'BROUILLON',
        'EN_ATTENTE_VALIDATION',
        'VALIDEE',
        'REJETEE',
    ];

    private function autoriser(): void
    {
        abort_unless(
            auth()->user()?->can('support.stocks.view'),
            403
        );
    }

    private function validerFiltres(Request $request): void
    {
        $request->validate([
            'fournisseur_id' => [
                'nullable',
                'integer',
                'exists:support_fournisseurs_stocks,id',
            ],
            'statut' => ['nullable', 'in:' . implode(',', self::STATUTS)],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => [
                'nullable',
                'date',
                'after_or_equal:date_debut',
            ],
        ]);
    }

    /**
     * Requête commune des réceptions.
     * Le montant est calculé à partir des lignes du document.
     */
    private function receptionsQuery(
        Request $request,
        ?string $statut = null
    ) {
        $lignes = DB::table('support_lignes_documents_stocks')
            ->select('support_document_stock_id')
            ->selectRaw('SUM(valeur_ligne) as montant_total')
            ->selectRaw('SUM(quantite) as quantite_totale')
            ->selectRaw('COUNT(*) as nombre_lignes')
            ->groupBy('support_document_stock_id');

        $query = DB::table('support_documents_stocks as d')
            ->leftJoin(
                'support_fournisseurs_stocks as f',
                'f.id',
                '=',
                'd.support_fournisseur_stock_id'
            )
            ->leftJoinSub($lignes, 'l', function ($join) {
                $join->on('l.support_document_stock_id', '=', 'd.id');
            })
            ->where('d.type_document', 'RECEPTION')
            ->select([
                'd.id',
                'd.reference',
                'd.statut',
                'd.date_document',
                'd.date_validation',
                'd.motif',
                'd.observations',
                'd.user_create_id',
                'd.user_validation_id',
                'd.support_fournisseur_stock_id',
                'f.code as fournisseur_code',
                'f.raison_sociale as fournisseur',
                DB::raw('COALESCE(l.montant_total, 0) as montant_total'),
                DB::raw('COALESCE(l.quantite_totale, 0) as quantite_totale'),
                DB::raw('COALESCE(l.nombre_lignes, 0) as nombre_lignes'),
            ]);

        if ($request->filled('fournisseur_id')) {
            $query->where(
                'd.support_fournisseur_stock_id',
                $request->integer('fournisseur_id')
            );
        }

        if ($request->filled('date_debut')) {
            $query->whereDate(
                'd.date_document',
                '>=',
                $request->input('date_debut')
            );
        }

        if ($request->filled('date_fin')) {
            $query->whereDate(
                'd.date_document',
                '<=',
                $request->input('date_fin')
            );
        }

        if ($statut !== null) {
            $query->where('d.statut', $statut);
        } elseif ($request->filled('statut')) {
            $query->where('d.statut', $request->input('statut'));
        }

        return $query;
    }

    public function index(Request $request)
    {
        $this->autoriser();
        $this->validerFiltres($request);

        $fournisseurs = DB::table('support_fournisseurs_stocks')
            ->whereNull('deleted_at')
            ->where('actif', 1)
            ->orderBy('raison_sociale')
            ->get(['id', 'code', 'raison_sociale']);

        // Sous-requête : une ligne agrégée par réception.
        $lignesAgregees = DB::table('support_lignes_documents_stocks')
            ->select('support_document_stock_id')
            ->selectRaw('SUM(valeur_ligne) as montant_total')
            ->selectRaw('SUM(quantite) as quantite_totale')
            ->groupBy('support_document_stock_id');

        // Indicateurs : uniquement les réceptions validées.
        // La sélection ne contient que des agrégats.
        $indicateursQuery = DB::table('support_documents_stocks as d')
            ->leftJoinSub($lignesAgregees, 'l', function ($join) {
                $join->on('l.support_document_stock_id', '=', 'd.id');
            })
            ->where('d.type_document', 'RECEPTION')
            ->where('d.statut', 'VALIDEE');

        if ($request->filled('fournisseur_id')) {
            $indicateursQuery->where(
                'd.support_fournisseur_stock_id',
                $request->integer('fournisseur_id')
            );
        }

        if ($request->filled('date_debut')) {
            $indicateursQuery->whereDate('d.date_document', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $indicateursQuery->whereDate('d.date_document', '<=', $request->input('date_fin'));
        }

        $indicateurs = $indicateursQuery
            ->selectRaw('COUNT(DISTINCT d.id) as nombre_receptions')
            ->selectRaw('COALESCE(SUM(l.montant_total), 0) as montant_total')
            ->selectRaw('COALESCE(SUM(l.quantite_totale), 0) as quantite_totale')
            ->first();

        // Nombre global de fournisseurs actifs.
        $nombreFournisseurs = DB::table('support_fournisseurs_stocks')
            ->whereNull('deleted_at')
            ->where('actif', 1)
            ->count();

        $receptions = $this->receptionsQuery($request)
            ->orderByDesc('d.date_document')
            ->orderByDesc('d.id')
            ->paginate(20)
            ->withQueryString();

        // Classement : uniquement les réceptions validées.
        // On sélectionne uniquement les colonnes groupées et agrégées.
        $classementQuery = DB::table('support_documents_stocks as d')
            ->join(
                'support_fournisseurs_stocks as f',
                'f.id',
                '=',
                'd.support_fournisseur_stock_id'
            )
            ->leftJoinSub($lignesAgregees, 'l', function ($join) {
                $join->on('l.support_document_stock_id', '=', 'd.id');
            })
            ->where('d.type_document', 'RECEPTION')
            ->where('d.statut', 'VALIDEE');

        if ($request->filled('fournisseur_id')) {
            $classementQuery->where(
                'd.support_fournisseur_stock_id',
                $request->integer('fournisseur_id')
            );
        }

        if ($request->filled('date_debut')) {
            $classementQuery->whereDate('d.date_document', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $classementQuery->whereDate('d.date_document', '<=', $request->input('date_fin'));
        }

        $classement = $classementQuery
            ->select([
                'd.support_fournisseur_stock_id',
                'f.code',
                'f.raison_sociale',
            ])
            ->selectRaw('COUNT(DISTINCT d.id) as nombre_receptions')
            ->selectRaw('COALESCE(SUM(l.montant_total), 0) as montant_total')
            ->selectRaw('COALESCE(SUM(l.quantite_totale), 0) as quantite_totale')
            ->groupBy(
                'd.support_fournisseur_stock_id',
                'f.code',
                'f.raison_sociale'
            )
            ->orderByDesc('montant_total')
            ->limit(10)
            ->get();

        return view('support.stocks.approvisionnements.index', compact(
            'fournisseurs',
            'indicateurs',
            'nombreFournisseurs',
            'receptions',
            'classement'
        ));
    }

    public function show(int $id)
    {
        $this->autoriser();

        $reception = DB::table('support_documents_stocks as d')
            ->leftJoin(
                'support_fournisseurs_stocks as f',
                'f.id',
                '=',
                'd.support_fournisseur_stock_id'
            )
            ->leftJoin('users as uc', 'uc.id', '=', 'd.user_create_id')
            ->leftJoin('users as uv', 'uv.id', '=', 'd.user_validation_id')
            ->where('d.id', $id)
            ->where('d.type_document', 'RECEPTION')
            ->select([
                'd.*',
                'f.code as fournisseur_code',
                'f.raison_sociale as fournisseur',
                'f.nom_contact',
                'f.telephone',
                'f.email',
                'uc.firstname as createur_prenom',
                'uc.name as createur_nom',
                'uv.firstname as validateur_prenom',
                'uv.name as validateur_nom',
            ])
            ->first();

        abort_unless($reception, 404);

        $lignes = DB::table('support_lignes_documents_stocks as l')
            ->join(
                'support_articles_stocks as a',
                'a.id',
                '=',
                'l.support_article_stock_id'
            )
            ->where('l.support_document_stock_id', $id)
            ->orderBy('a.designation')
            ->get([
                'l.id',
                'l.quantite',
                'l.cout_unitaire',
                'l.valeur_ligne',
                'l.observations',
                'a.reference',
                'a.designation',
            ]);

        $totaux = DB::table('support_lignes_documents_stocks')
            ->where('support_document_stock_id', $id)
            ->selectRaw('COALESCE(SUM(quantite), 0) as quantite_totale')
            ->selectRaw('COALESCE(SUM(valeur_ligne), 0) as montant_total')
            ->first();

        return view('support.stocks.approvisionnements.show', compact(
            'reception',
            'lignes',
            'totaux'
        ));
    }

    private function lignesExport(Request $request): array
    {
        return $this->receptionsQuery($request)
            ->orderByDesc('d.date_document')
            ->get()
            ->map(fn ($r) => [
                $r->reference,
                $r->date_document,
                $r->fournisseur ?? 'Fournisseur non renseigné',
                $r->statut,
                (float) $r->nombre_lignes,
                (float) $r->quantite_totale,
                (float) $r->montant_total,
            ])
            ->all();
    }

    public function exporterExcel(Request $request)
    {
        $this->autoriser();
        $this->validerFiltres($request);

        return Excel::download(
            new SupportStockRapportExport(
                $this->lignesExport($request),
                [
                    'Référence',
                    'Date',
                    'Fournisseur',
                    'Statut',
                    'Nombre de lignes',
                    'Quantité totale',
                    'Montant (FCFA)',
                ]
            ),
            'approvisionnements-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function exporterPdf(Request $request)
    {
        $this->autoriser();
        $this->validerFiltres($request);

        return Pdf::loadView('support.stocks.rapports.pdf', [
            'titre' => 'Historique des approvisionnements',
            'colonnes' => [
                'Référence',
                'Date',
                'Fournisseur',
                'Statut',
                'Lignes',
                'Quantité',
                'Montant FCFA',
            ],
            'lignes' => $this->lignesExport($request),
        ])->setPaper('a4', 'landscape')
          ->download('approvisionnements-' . now()->format('Ymd') . '.pdf');
    }
}
