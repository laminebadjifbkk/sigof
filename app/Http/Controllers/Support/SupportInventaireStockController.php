<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportInventaireStock;
use App\Services\Support\SupportInventaireStockService;
use Illuminate\Http\Request;

class SupportInventaireStockController extends Controller
{
    private function autoriser(Request $request, string $permission): void
    {
        abort_unless($request->user()->can($permission), 403);
    }

    private function inventaire(int $id): SupportInventaireStock
    {
        return SupportInventaireStock::query()
            ->with([
                'createur',
                'validateur',
                'lignes.article.unite',
                'lignes.compteur',
            ])
            ->findOrFail($id);
    }

    public function index(Request $request)
    {
        $this->autoriser($request, 'support.stocks.view');

        $query = SupportInventaireStock::query()
            ->with(['createur', 'validateur'])
            ->withCount('lignes');

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('recherche')) {
            $terme = trim($request->recherche);

            $query->where(function ($q) use ($terme) {
                $q->where('reference', 'like', "%{$terme}%")
                    ->orWhere('libelle', 'like', "%{$terme}%");
            });
        }

        $inventaires = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('support.stocks.inventaires.index', compact('inventaires'));
    }

    public function create(Request $request)
    {
        $this->autoriser($request, 'support.stocks.manage');

        return view('support.stocks.inventaires.create');
    }

    public function store(
        Request $request,
        SupportInventaireStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $data = $request->validate([
            'libelle' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'date_debut' => ['required', 'date'],
            'observations' => ['nullable', 'string'],
        ]);

        $inventaire = $service->creer(
            $data,
            (int) $request->user()->id
        );

        return redirect()
            ->route('support.stocks.inventaires.show', $inventaire->id)
            ->with('success', 'Inventaire créé. Sélectionnez les articles à inventorier.');
    }

    public function show(Request $request, int $id)
    {
        $this->autoriser($request, 'support.stocks.view');

        $inventaire = $this->inventaire($id);

        $articles = collect();

        if ($inventaire->statut === 'BROUILLON') {
            $articles = SupportArticleStock::query()
                ->where('actif', true)
                ->with('unite')
                ->orderBy('designation')
                ->get();
        }

        $estCreateur = (int) $inventaire->user_create_id
            === (int) $request->user()->id;

        $peutValider = $request->user()->can('support.stocks.movement')
            && !$estCreateur
            && $inventaire->statut === 'EN_ATTENTE_VALIDATION';

        return view('support.stocks.inventaires.show', compact(
            'inventaire',
            'articles',
            'estCreateur',
            'peutValider'
        ));
    }

    public function demarrer(
        Request $request,
        int $id,
        SupportInventaireStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $data = $request->validate([
            'article_ids' => ['required', 'array', 'min:1'],
            'article_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:support_articles_stocks,id',
            ],
        ]);

        $service->demarrer(
            $id,
            $data['article_ids'],
            (int) $request->user()->id
        );

        return redirect()
            ->route('support.stocks.inventaires.show', $id)
            ->with('success', 'Inventaire démarré. Les stocks sélectionnés sont verrouillés.');
    }

    public function comptage(
        Request $request,
        int $id
    ) {
        $this->autoriser($request, 'support.stocks.view');

        $inventaire = $this->inventaire($id);

        abort_unless(
            in_array($inventaire->statut, ['EN_COURS', 'REJETEE'], true),
            403
        );

        abort_unless(
            (int) $inventaire->user_create_id === (int) $request->user()->id,
            403
        );

        return view('support.stocks.inventaires.comptage', compact('inventaire'));
    }

    public function enregistrerComptage(
        Request $request,
        int $id,
        SupportInventaireStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $data = $request->validate([
            'article_id' => ['required', 'integer'],
            'quantite_comptee' => ['required', 'numeric', 'min:0'],
            'observations' => ['nullable', 'string', 'max:2000'],
        ]);

        $service->enregistrerComptage(
            $id,
            (int) $data['article_id'],
            $data['quantite_comptee'],
            $data['observations'] ?? null,
            (int) $request->user()->id
        );

        return redirect()
            ->route('support.stocks.inventaires.comptage', $id)
            ->with('success', 'Comptage enregistré.');
    }

    public function soumettre(
        Request $request,
        int $id,
        SupportInventaireStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $service->soumettre($id, (int) $request->user()->id);

        return redirect()
            ->route('support.stocks.inventaires.show', $id)
            ->with('success', 'Inventaire soumis pour validation.');
    }

    public function valider(
        Request $request,
        int $id,
        SupportInventaireStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.movement');

        $service->valider($id, (int) $request->user()->id);

        return redirect()
            ->route('support.stocks.inventaires.show', $id)
            ->with('success', 'Inventaire validé. Les stocks ont été régularisés.');
    }

    public function rejeter(
        Request $request,
        int $id,
        SupportInventaireStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.movement');

        $data = $request->validate([
            'motif_rejet' => ['required', 'string', 'min:3', 'max:5000'],
        ]);

        $service->rejeter(
            $id,
            $data['motif_rejet'],
            (int) $request->user()->id
        );

        return redirect()
            ->route('support.stocks.inventaires.show', $id)
            ->with('success', 'Inventaire rejeté. Le créateur peut corriger les comptages.');
    }
}
