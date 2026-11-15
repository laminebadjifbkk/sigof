<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Antenne;
use App\Models\Direction;
use App\Models\Employee;
use App\Models\Support\SupportCategorieEquipement;
use App\Models\Support\SupportEquipement;
use App\Services\Support\SupportEquipementService;
use App\Services\Support\SupportAffectationEquipementService;
use Illuminate\Http\Request;

class SupportEquipementController extends Controller
{
    /**
     * Liste du parc informatique.
     */
    public function index(Request $request)
    {
        abort_unless(
            $request->user()->can('support.equipements.view'),
            403
        );

        $query = SupportEquipement::query()
            ->with([
                'categorie',
                'affectationActive.employee.user',
                'affectationActive.direction',
                'affectationActive.antenne',
            ]);

        if ($request->filled('recherche')) {
            $recherche = trim($request->recherche);

            $query->where(function ($q) use ($recherche) {
                $q->where('reference', 'like', "%{$recherche}%")
                    ->orWhere('designation', 'like', "%{$recherche}%")
                    ->orWhere('marque', 'like', "%{$recherche}%")
                    ->orWhere('modele', 'like', "%{$recherche}%")
                    ->orWhere('numero_serie', 'like', "%{$recherche}%");
            });
        }

        if ($request->filled('categorie')) {
            $query->where(
                'support_categorie_equipement_id',
                $request->categorie
            );
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('etat_physique')) {
            $query->where('etat_physique', $request->etat_physique);
        }

        $equipements = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $categories = SupportCategorieEquipement::actives()
            ->ordonnes()
            ->get();

        $stats = [
            'total' => SupportEquipement::count(),
            'en_stock' => SupportEquipement::where('statut', 'EN_STOCK')->count(),
            'affectes' => SupportEquipement::where('statut', 'AFFECTE')->count(),
            'en_maintenance' => SupportEquipement::where('statut', 'EN_MAINTENANCE')->count(),
            'reformes' => SupportEquipement::where('statut', 'REFORME')->count(),
            'perdus' => SupportEquipement::where('statut', 'PERDU')->count(),
        ];

        return view('support.equipements.index', compact(
            'equipements',
            'categories',
            'stats'
        ));
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        abort_unless(
            $request->user()->can('support.equipements.create'),
            403
        );

        $categories = SupportCategorieEquipement::actives()
            ->ordonnes()
            ->get();

        return view('support.equipements.create', compact('categories'));
    }

    /**
     * Enregistrer un équipement.
     */
    public function store(
        Request $request,
        SupportEquipementService $equipementService
    ) {
        $equipement = $equipementService->creer(
            $request->all(),
            $request->user()
        );

        return redirect()
            ->route('support.equipements.show', $equipement)
            ->with('success', 'Équipement enregistré avec succès.');
    }

    /**
     * Consulter la fiche d'un équipement.
     */
    public function show(Request $request, SupportEquipement $equipement)
    {
        abort_unless(
            $request->user()->can('support.equipements.view'),
            403
        );

        $equipement->load([
            'categorie',
            'createur',
            'modificateur',
            'affectations.employee.user',
            'affectations.employee.direction',
            'affectations.direction',
            'affectations.antenne',
            'affectations.createur',
            'affectations.modificateur',
            'affectationActive.employee.user',
            'affectationActive.employee.direction',
            'affectationActive.direction',
            'affectationActive.antenne',
            'historiques.user',
            'mouvements.createur',
            'mouvements.affectationOrigine.employee.user',
            'mouvements.affectationOrigine.direction',
            'mouvements.affectationOrigine.antenne',
            'mouvements.affectationDestination.employee.user',
            'mouvements.affectationDestination.direction',
            'mouvements.affectationDestination.antenne',
        ]);

        $peutAffecter = $request->user()->can('support.equipements.assign');
        $peutTransferer = $request->user()->can('support.equipements.move');
        $peutModifier = $request->user()->can('support.equipements.update');
        $peutReformer = $request->user()->can('support.equipements.delete');

        $employes = collect();
        $directions = collect();
        $antennes = collect();

        if ($peutAffecter || $peutTransferer) {
            $employes = Employee::query()
                ->with(['user', 'direction'])
                ->orderBy('matricule')
                ->get();

            $directions = Direction::query()
                ->orderBy('name')
                ->get();

            $antennes = Antenne::query()
                ->orderBy('name')
                ->get();
        }

        return view('support.equipements.show', compact(
            'equipement',
            'employes',
            'directions',
            'antennes',
            'peutAffecter',
            'peutTransferer',
            'peutModifier',
            'peutReformer'
        ));
    }

    /**
     * Formulaire de modification.
     */
    public function edit(Request $request, SupportEquipement $equipement)
    {
        abort_unless(
            $request->user()->can('support.equipements.update'),
            403
        );

        $categories = SupportCategorieEquipement::actives()
            ->ordonnes()
            ->get();

        return view('support.equipements.edit', compact(
            'equipement',
            'categories'
        ));
    }

    /**
     * Mettre à jour un équipement.
     */
    public function update(
        Request $request,
        SupportEquipement $equipement,
        SupportEquipementService $equipementService
    ) {
        $equipementService->modifier(
            $equipement,
            $request->all(),
            $request->user()
        );

        return redirect()
            ->route('support.equipements.show', $equipement)
            ->with('success', 'Équipement modifié avec succès.');
    }

    /**
     * Affecter un équipement.
     */
    public function affecter(
        Request $request,
        SupportEquipement $equipement,
        SupportAffectationEquipementService $affectationService
    ) {
        $destinataire = $request->only([
            'employee_id',
            'direction_id',
            'antenne_id',
        ]);

        $options = $request->only([
            'etat_remise',
            'motif',
            'observations',
        ]);

        $affectationService->affecter(
            $equipement,
            $destinataire,
            $request->user(),
            $options
        );

        return redirect()
            ->route('support.equipements.show', $equipement)
            ->with('success', 'Équipement affecté avec succès.');
    }

    /**
     * Enregistrer le retour d'un équipement.
     */
    public function retourner(
        Request $request,
        SupportEquipement $equipement,
        SupportAffectationEquipementService $affectationService
    ) {
        $donnees = $request->only([
            'etat_retour',
            'motif_retour',
            'observations',
        ]);

        $affectationService->retourner(
            $equipement,
            $donnees,
            $request->user()
        );

        return redirect()
            ->route('support.equipements.show', $equipement)
            ->with('success', 'Retour de l’équipement enregistré.');
    }

    /**
     * Transférer un équipement.
     */
    public function transferer(
        Request $request,
        SupportEquipement $equipement,
        SupportAffectationEquipementService $affectationService
    ) {
        $destinataire = $request->only([
            'employee_id',
            'direction_id',
            'antenne_id',
        ]);

        $options = $request->only([
            'etat_retour',
            'etat_remise',
            'motif',
            'observations',
        ]);

        $affectationService->transferer(
            $equipement,
            $destinataire,
            $request->user(),
            $options
        );

        return redirect()
            ->route('support.equipements.show', $equipement)
            ->with('success', 'Transfert de l’équipement effectué.');
    }

    /**
     * Réformer un équipement.
     */
    public function reformer(
        Request $request,
        SupportEquipement $equipement,
        SupportEquipementService $equipementService
    ) {
        $validated = $request->validate([
            'motif' => ['nullable', 'string', 'max:1000'],
        ]);

        $equipementService->retirer(
            $equipement,
            $request->user(),
            $validated['motif'] ?? null
        );

        return redirect()
            ->route('support.equipements.show', $equipement)
            ->with('success', 'Équipement réformé avec succès.');
    }
}
