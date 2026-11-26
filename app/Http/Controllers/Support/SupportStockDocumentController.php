<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Support\SupportTicket;
use App\Models\Employee;
use App\Models\Direction;
use App\Models\Antenne;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportDocumentStock;
use App\Models\Support\SupportFournisseurStock;
use App\Models\Support\SupportLigneDocumentStock;
use App\Services\Support\SupportDocumentStockService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SupportStockDocumentController extends Controller
{
    private const TYPES = [
        'RECEPTION',
        'SORTIE',
        'RETOUR',
        'AJUSTEMENT_POSITIF',
        'AJUSTEMENT_NEGATIF',
    ];

    /**
     * Vérifie le rattachement du destinataire et prépare les champs métier.
     */
    private function preparerDestinataire(
        array $donnees,
        string $typeDocument
    ): array {
        $employeeId = $donnees['employee_id'] ?? null;
        $entiteType = $donnees['entite_type'] ?? null;
        $entiteId = $donnees['entite_id'] ?? null;

        unset($donnees['entite_type'], $donnees['entite_id']);

        $donnees['employee_id'] = null;
        $donnees['direction_id'] = null;
        $donnees['antenne_id'] = null;

        if ($typeDocument !== 'SORTIE') {
            if ($employeeId || $entiteId) {
                throw ValidationException::withMessages([
                    'entite_id' => 'Un destinataire est autorisé uniquement pour une sortie.',
                ]);
            }

            return $donnees;
        }

        if (!$entiteType || !$entiteId) {
            throw ValidationException::withMessages([
                'entite_id' => 'Sélectionnez une direction ou une antenne.',
            ]);
        }

        if ($entiteType === 'direction') {
            $entite = Direction::query()->find($entiteId);

            if (!$entite) {
                throw ValidationException::withMessages([
                    'entite_id' => 'La direction sélectionnée est introuvable ou inactive.',
                ]);
            }

            if ($employeeId) {
                $employe = Employee::query()
                    ->whereKey($employeeId)
                    ->where('directions_id', $entiteId)
                    ->first();

                if (!$employe) {
                    throw ValidationException::withMessages([
                        'employee_id' => 'Cet employé n’est pas rattaché à cette direction.',
                    ]);
                }

                $donnees['employee_id'] = (int) $employe->id;
            } else {
                $donnees['direction_id'] = (int) $entite->id;
            }
        } elseif ($entiteType === 'antenne') {
            $entite = Antenne::query()->find($entiteId);

            if (!$entite) {
                throw ValidationException::withMessages([
                    'entite_id' => 'L’antenne sélectionnée est introuvable ou inactive.',
                ]);
            }

            if ($employeeId) {
                $employe = Employee::query()
                    ->whereKey($employeeId)
                    ->whereHas('antennes', function ($query) use ($entiteId) {
                        $query->where('antennes.id', $entiteId);
                    })
                    ->first();

                if (!$employe) {
                    throw ValidationException::withMessages([
                        'employee_id' => 'Cet employé n’est pas rattaché à cette antenne.',
                    ]);
                }

                $donnees['employee_id'] = (int) $employe->id;
            } else {
                $donnees['antenne_id'] = (int) $entite->id;
            }
        } else {
            throw ValidationException::withMessages([
                'entite_type' => 'Type d’entité invalide.',
            ]);
        }

        return $donnees;
    }

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

        $directions = Direction::query()
            ->orderBy('name')
            ->get(['id', 'name', 'sigle']);

        $antennes = Antenne::query()
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        $tickets = SupportTicket::query()
            ->with('statut')
            ->whereHas('statut', function ($query) {
                $query->where('est_terminal', false)
                    ->where('actif', true);
            })
            ->orderByDesc('date_soumission')
            ->get(['id', 'reference', 'objet', 'support_statut_id']);

        return view('support.stocks.documents.form', compact(
            'type',
            'fournisseurs',
            'directions',
            'antennes',
            'tickets'
        ));
    }

    /**
     * Retourne les employés rattachés à une direction ou une antenne.
     */
    public function employesParEntite(
        Request $request,
        string $type,
        int $id
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        abort_unless(in_array($type, ['direction', 'antenne'], true), 404);

        if ($type === 'direction') {
            Direction::query()->findOrFail($id);

            $employes = Employee::query()
                ->with('user')
                ->where('directions_id', $id)
                ->orderBy('id')
                ->get();
        } else {
            Antenne::query()->findOrFail($id);

            $employes = Employee::query()
                ->with('user')
                ->whereHas('antennes', function ($query) use ($id) {
                    $query->where('antennes.id', $id);
                })
                ->orderBy('id')
                ->get();
        }

        return response()->json(
            $employes->map(function ($employe) {
                $nom = trim(
                    ($employe->user->firstname ?? '') . ' ' .
                    ($employe->user->name ?? '')
                );

                return [
                    'id' => $employe->id,
                    'matricule' => $employe->matricule,
                    'label' => ($employe->matricule
                        ? $employe->matricule . ' — '
                        : '') . ($nom ?: 'Employé #' . $employe->id),
                ];
            })->values()
        );
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
            'entite_type' => ['required_if:type_document,SORTIE', 'nullable', 'in:direction,antenne'],
            'entite_id' => ['required_if:type_document,SORTIE', 'nullable', 'integer'],
            'employee_id' => [
                'nullable',
                'integer',
                'exists:employees,id,deleted_at,NULL',
            ],
            'support_ticket_id' => [
                'nullable',
                'integer',
                'exists:support_tickets,id,deleted_at,NULL',
            ],
            'motif' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
        ]);

        $data = $this->preparerDestinataire(
            $data,
            $data['type_document']
        );

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

    /**
     * Génère le bon de stock au format PDF.
     */
    public function exporterPdf(Request $request, int $id)
    {
        $this->autoriser($request, 'support.stocks.view');

        $document = $this->document($id);

        $libelles = [
            'RECEPTION' => 'Bon de réception',
            'SORTIE' => 'Bon de sortie',
            'RETOUR' => 'Bon de retour',
            'AJUSTEMENT_POSITIF' => 'Bon d’ajustement positif',
            'AJUSTEMENT_NEGATIF' => 'Bon d’ajustement négatif',
        ];

        $titre = $libelles[$document->type_document]
            ?? 'Document de stock';

        $reference = preg_replace(
            '/[^A-Za-z0-9._-]/',
            '-',
            $document->reference
        );

        return Pdf::loadView('support.stocks.documents.pdf', [
            'document' => $document,
            'titre' => $titre,
        ])
            ->setPaper('a4', 'landscape')
            ->download($reference . '.pdf');
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

        $directions = Direction::query()
            ->orderBy('name')
            ->get(['id', 'name', 'sigle']);

        $antennes = Antenne::query()
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        $tickets = SupportTicket::query()
            ->with('statut')
            ->whereHas('statut', function ($query) {
                $query->where('est_terminal', false)
                    ->where('actif', true);
            })
            ->orderByDesc('date_soumission')
            ->get(['id', 'reference', 'objet', 'support_statut_id']);

        return view('support.stocks.documents.form', compact(
            'document',
            'type',
            'fournisseurs',
            'directions',
            'antennes',
            'tickets'
        ));
    }

    public function update(
        Request $request,
        int $id,
        SupportDocumentStockService $service
    ) {
        $this->autoriser($request, 'support.stocks.manage');

        $document = $this->document($id);
        $typeDocument = $document->type_document;

        abort_unless(
            (int) $document->user_create_id === (int) $request->user()->id
            && in_array($document->statut, ['BROUILLON', 'REJETEE'], true),
            403
        );

        $data = $request->validate([
            'date_document' => ['required', 'date'],
            'support_fournisseur_stock_id' => [
                'nullable',
                'integer',
                'exists:support_fournisseurs_stocks,id',
            ],
            'entite_type' => [
                $typeDocument === 'SORTIE' ? 'required' : 'nullable',
                'in:direction,antenne',
            ],
            'entite_id' => [
                $typeDocument === 'SORTIE' ? 'required' : 'nullable',
                'integer',
            ],
            'employee_id' => [
                'nullable',
                'integer',
                'exists:employees,id,deleted_at,NULL',
            ],
            'support_ticket_id' => [
                'nullable',
                'integer',
                'exists:support_tickets,id,deleted_at,NULL',
            ],
            'motif' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string'],
        ]);

        $data = $this->preparerDestinataire($data, $typeDocument);

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
