<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportDocumentStock;
use App\Models\Support\SupportFournisseurStock;
use App\Models\Support\SupportLigneDocumentStock;
use App\Services\Support\SupportDocumentStockService;
use Illuminate\Http\Request;

class SupportStockDocumentController extends Controller
{
    private const TYPES = [
        'RECEPTION',
        'SORTIE',
        'RETOUR',
        'AJUSTEMENT_POSITIF',
        'AJUSTEMENT_NEGATIF',
    ];

    private function autoriser(Request $request, string $permission): void
    {
        abort_unless($request->user()->can($permission), 403);
    }

    private function document(int $id): SupportDocumentStock
    {
        return SupportDocumentStock::with([
            'fournisseur',
            'employe',
            'direction',
            'antenne',
            'ticket',
            'createur',
            'validateur',
            'lignes.article.unite',
            'lignes.ligneOrigine.document',
            'mouvements',
        ])->findOrFail($id);
    }

    public function index(Request $request)
    {
        $this->autoriser($request, 'support.stocks.view');

        $query = SupportDocumentStock::query()
            ->with(['fournisseur', 'createur', 'validateur']);

        if ($request->filled('type')) {
            $query->where('type_document', $request->type);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('recherche')) {
            $terme = trim($request->recherche);

            $query->where(function ($q) use ($terme) {
                $q->where('reference', 'like', "%{$terme}%")
                    ->orWhere('motif', 'like', "%{$terme}%");
            });
        }

        $documents = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('support.stocks.documents.index', compact('documents'));
    }

    public function create(Request $request, string $type)
    {
        $this->autoriser($request, 'support.stocks.manage');

        abort_unless(in_array($type, self::TYPES, true), 404);

        $fournisseurs = SupportFournisseurStock::query()
            ->where('actif', true)
            ->orderBy('raison_sociale')
            ->get();

        return view('support.stocks.documents.form', compact(
            'type',
            'fournisseurs'
        ));
    }

    public function store(
        Request $request,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $data = $request->validate([
            'type_document' => ['required', 'in:'.implode(',', self::TYPES)],
            'date_document' => ['required', 'date'],
            'support_fournisseur_stock_id' => [
                'nullable',
                'integer',
                'exists:support_fournisseurs_stocks,id',
            ],
            'employee_id' => ['nullable', 'integer'],
            'direction_id' => ['nullable', 'integer'],
            'antenne_id' => ['nullable', 'integer'],
            'support_ticket_id' => ['nullable', 'integer'],
            'motif' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
        ]);

        $document = $service->creerDocument(
            $data,
            (int) $request->user()->id
        );

        return redirect()
            ->route('support.stocks.documents.show', $document->id)
            ->with('success', 'Document créé. Vous pouvez maintenant ajouter ses articles.');
    }

    public function show(Request $request, int $id)
    {
        $this->autoriser($request, 'support.stocks.view');

        $document = $this->document($id);

        $articles = SupportArticleStock::query()
            ->actifs()
            ->with('unite')
            ->orderBy('designation')
            ->get();

        $lignesSortie = collect();

        if ($document->type_document === 'RETOUR') {
            $lignesSortie = SupportLigneDocumentStock::query()
                ->with(['article', 'document'])
                ->whereHas('document', function ($q) {
                    $q->where('type_document', 'SORTIE')
                        ->where('statut', 'VALIDEE');
                })
                ->orderByDesc('id')
                ->get();
        }

        return view('support.stocks.documents.show', compact(
            'document',
            'articles',
            'lignesSortie'
        ));
    }

    public function edit(Request $request, int $id)
    {
        $this->autoriser($request, 'support.stocks.manage');

        $document = $this->document($id);

        abort_unless(
            (int) $document->user_create_id === (int) $request->user()->id
            && in_array($document->statut, ['BROUILLON', 'REJETEE'], true),
            403
        );

        $type = $document->type_document;

        $fournisseurs = SupportFournisseurStock::query()
            ->where('actif', true)
            ->orderBy('raison_sociale')
            ->get();

        return view('support.stocks.documents.form', compact(
            'document',
            'type',
            'fournisseurs'
        ));
    }

    public function update(
        Request $request,
        int $id,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $data = $request->validate([
            'date_document' => ['required', 'date'],
            'support_fournisseur_stock_id' => [
                'nullable',
                'integer',
                'exists:support_fournisseurs_stocks,id',
            ],
            'employee_id' => ['nullable', 'integer'],
            'direction_id' => ['nullable', 'integer'],
            'antenne_id' => ['nullable', 'integer'],
            'support_ticket_id' => ['nullable', 'integer'],
            'motif' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
        ]);

        $service->modifierDocument(
            $id,
            $data,
            (int) $request->user()->id
        );

        return redirect()
            ->route('support.stocks.documents.show', $id)
            ->with('success', 'Document modifié avec succès.');
    }

    public function ajouterLigne(
        Request $request,
        int $id,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $data = $request->validate([
            'support_article_stock_id' => ['required', 'integer'],
            'quantite' => ['required', 'numeric', 'gt:0'],
            'cout_unitaire' => ['nullable', 'numeric', 'min:0'],
            'support_ligne_origine_id' => ['nullable', 'integer'],
            'observations' => ['nullable', 'string'],
        ]);

        $service->ajouterLigne(
            $id,
            $data,
            (int) $request->user()->id
        );

        return back()->with('success', 'Article ajouté au document.');
    }

    public function modifierLigne(
        Request $request,
        int $id,
        int $ligne,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $data = $request->validate([
            'support_article_stock_id' => ['required', 'integer'],
            'quantite' => ['required', 'numeric', 'gt:0'],
            'cout_unitaire' => ['nullable', 'numeric', 'min:0'],
            'support_ligne_origine_id' => ['nullable', 'integer'],
            'observations' => ['nullable', 'string'],
        ]);

        $service->modifierLigne(
            $id,
            $ligne,
            $data,
            (int) $request->user()->id
        );

        return back()->with('success', 'Ligne modifiée.');
    }

    public function supprimerLigne(
        Request $request,
        int $id,
        int $ligne,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $service->supprimerLigne(
            $id,
            $ligne,
            (int) $request->user()->id
        );

        return back()->with('success', 'Ligne supprimée.');
    }

    public function soumettre(
        Request $request,
        int $id,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $service->soumettre($id, (int) $request->user()->id);

        return back()->with('success', 'Document soumis à validation.');
    }

    public function valider(
        Request $request,
        int $id,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.movement');

        $service->valider($id, (int) $request->user()->id);

        return back()->with('success', 'Document validé et mouvements appliqués au stock.');
    }

    public function rejeter(
        Request $request,
        int $id,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.movement');

        $data = $request->validate([
            'motif_rejet' => ['required', 'string', 'max:5000'],
        ]);

        $service->rejeter(
            $id,
            $data['motif_rejet'],
            (int) $request->user()->id
        );

        return back()->with('success', 'Document rejeté. Son créateur pourra le corriger.');
    }
}
