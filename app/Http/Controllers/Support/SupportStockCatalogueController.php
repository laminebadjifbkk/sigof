<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Support\SupportArticleStock;
use App\Models\Support\SupportCategorieStock;
use App\Models\Support\SupportUniteStock;
use App\Models\Support\SupportFournisseurStock;
use App\Services\Support\SupportStockReferenceService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SupportStockCatalogueController extends Controller
{
    private function configurations(): array
    {
        return [
            'categories' => [
                'titre' => 'Catégories',
                'singulier' => 'Catégorie',
                'modele' => SupportCategorieStock::class,
                'recherche' => ['code', 'libelle'],
                'champs' => [
                    'code' => ['label' => 'Code', 'type' => 'text', 'required' => true],
                    'libelle' => ['label' => 'Libellé', 'type' => 'text', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'textarea'],
                    'ordre' => ['label' => 'Ordre d’affichage', 'type' => 'number'],
                ],
            ],

            'unites' => [
                'titre' => 'Unités de mesure',
                'singulier' => 'Unité',
                'modele' => SupportUniteStock::class,
                'recherche' => ['code', 'libelle', 'symbole'],
                'champs' => [
                    'code' => ['label' => 'Code', 'type' => 'text', 'required' => true],
                    'libelle' => ['label' => 'Libellé', 'type' => 'text', 'required' => true],
                    'symbole' => ['label' => 'Symbole', 'type' => 'text', 'required' => true],
                    'autorise_decimales' => ['label' => 'Autoriser les quantités décimales', 'type' => 'checkbox'],
                    'ordre' => ['label' => 'Ordre d’affichage', 'type' => 'number'],
                ],
            ],

            'fournisseurs' => [
                'titre' => 'Fournisseurs',
                'singulier' => 'Fournisseur',
                'modele' => SupportFournisseurStock::class,
                'recherche' => [
                    'code', 'raison_sociale', 'nom_contact',
                    'telephone', 'email', 'numero_ninea'
                ],
                'champs' => [
                    'code' => ['label' => 'Code', 'type' => 'text', 'required' => true],
                    'raison_sociale' => ['label' => 'Raison sociale', 'type' => 'text', 'required' => true],
                    'nom_contact' => ['label' => 'Nom du contact', 'type' => 'text'],
                    'telephone' => ['label' => 'Téléphone', 'type' => 'text'],
                    'telephone_secondaire' => ['label' => 'Téléphone secondaire', 'type' => 'text'],
                    'email' => ['label' => 'Adresse e-mail', 'type' => 'email'],
                    'adresse' => ['label' => 'Adresse', 'type' => 'textarea'],
                    'numero_ninea' => ['label' => 'NINEA', 'type' => 'text'],
                    'numero_registre_commerce' => ['label' => 'Registre de commerce', 'type' => 'text'],
                    'observations' => ['label' => 'Observations', 'type' => 'textarea'],
                ],
            ],

            'articles' => [
                'titre' => 'Articles de stock',
                'singulier' => 'Article',
                'modele' => SupportArticleStock::class,
                'recherche' => ['reference', 'designation'],
                'champs' => [
                    'designation' => ['label' => 'Désignation', 'type' => 'text', 'required' => true],
                    'support_categorie_stock_id' => ['label' => 'Catégorie', 'type' => 'categorie', 'required' => true],
                    'support_unite_stock_id' => ['label' => 'Unité de mesure', 'type' => 'unite', 'required' => true],
                    'description' => ['label' => 'Description', 'type' => 'textarea'],
                    'stock_minimum' => ['label' => 'Seuil minimum', 'type' => 'number', 'step' => '0.001'],
                    'stock_maximum' => ['label' => 'Stock maximum', 'type' => 'number', 'step' => '0.001'],
                ],
            ],
        ];
    }

    private function config(string $type): array
    {
        $configurations = $this->configurations();

        abort_unless(isset($configurations[$type]), 404);

        return $configurations[$type];
    }

    private function autoriser(Request $request, bool $ecriture = false): void
    {
        abort_unless(
            $request->user()->can(
                $ecriture ? 'support.stocks.manage' : 'support.stocks.view'
            ),
            403
        );
    }

    private function chargerReferentiels(string $type): array
    {
        return [
            'categories' => $type === 'articles'
                ? SupportCategorieStock::actives()->ordonnees()->get()
                : collect(),

            'unites' => $type === 'articles'
                ? SupportUniteStock::actives()->ordonnees()->get()
                : collect(),
        ];
    }

    public function index(Request $request, string $type)
    {
        $this->autoriser($request);

        $config = $this->config($type);
        $model = $config['modele'];

        $query = $model::query();

        // Les fournisseurs supprimés logiquement restent exclus
        // des listes normales. Ils peuvent être restaurés via toggle.

        if ($request->filled('recherche')) {
            $terme = trim($request->input('recherche'));

            $query->where(function ($q) use ($config, $terme) {
                foreach ($config['recherche'] as $champ) {
                    $q->orWhere($champ, 'like', "%{$terme}%");
                }
            });
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'supprime') {
                $query->onlyTrashed();
            } else {
                $query->where(
                    'actif',
                    $request->statut === 'actif'
                );
            }
        }

        if ($type === 'articles') {
            $query->with(['categorie', 'unite', 'stock']);
        }

        $elements = $query
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view(
            'support.stocks.catalogue.index',
            compact('type', 'config', 'elements')
        );
    }

    public function create(Request $request, string $type)
    {
        $this->autoriser($request, true);

        $config = $this->config($type);

        $referentiels = $this->chargerReferentiels($type);

        $categories = $referentiels['categories'];
        $unites = $referentiels['unites'];

        return view(
            'support.stocks.catalogue.form',
            compact('type', 'config', 'categories', 'unites')
        );
    }

    public function store(
        Request $request,
        string $type,
        SupportStockReferenceService $references
    ) {
        $this->autoriser($request, true);

        $config = $this->config($type);
        $data = $this->valider($request, $type);

        if ($type === 'articles') {
            $data['reference'] = $references->generer('ARTICLE');
        }

        $data['actif'] = true;
        $data['user_create_id'] = $request->user()->id;

        $modelClass = $config['modele'];
        $modelClass::create($data);

        return redirect()
            ->route('support.stocks.catalogue.index', ['type' => $type])
            ->with('success', $config['singulier'].' enregistré(e) avec succès.');
    }

    public function edit(Request $request, string $type, int $id)
    {
        $this->autoriser($request, true);

        $config = $this->config($type);
        $element = $config['modele']::findOrFail($id);

        $referentiels = $this->chargerReferentiels($type);

        $categories = $referentiels['categories'];
        $unites = $referentiels['unites'];

        return view(
            'support.stocks.catalogue.form',
            compact('type', 'config', 'element', 'categories', 'unites')
        );
    }

    public function update(Request $request, string $type, int $id)
    {
        $this->autoriser($request, true);

        $config = $this->config($type);
        $element = $config['modele']::findOrFail($id);

        $data = $this->valider($request, $type, $element);

        $data['user_update_id'] = $request->user()->id;

        $element->update($data);

        return redirect()
            ->route('support.stocks.catalogue.index', ['type' => $type])
            ->with('success', $config['singulier'].' modifié(e) avec succès.');
    }

    public function toggle(Request $request, string $type, int $id)
    {
        $this->autoriser($request, true);

        $config = $this->config($type);
        $modelClass = $config['modele'];

        // Recherche également les éléments supprimés logiquement.
        $element = $modelClass::withTrashed()->findOrFail($id);

        if ($element->trashed()) {
            $element->restore();

            $element->update([
                'actif' => true,
                'user_update_id' => $request->user()->id,
            ]);

            return back()->with(
                'success',
                $config['singulier'].' restauré(e) et réactivé(e).'
            );
        }

        if ($type === 'articles' && $element->actif) {
            $element->loadMissing('stock');

            $quantite = (float) ($element->stock?->quantite ?? 0);

            if ($quantite > 0) {
                return back()->with(
                    'error',
                    'Impossible de désactiver cet article : son stock disponible est positif.'
                );
            }
        }

        $element->update([
            'actif' => !$element->actif,
            'user_update_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Statut mis à jour.');
    }

    private function valider(
        Request $request,
        string $type,
        $element = null
    ): array {
        $config = $this->config($type);
        $rules = [];

        foreach ($config['champs'] as $champ => $definition) {
            $required = !empty($definition['required']);

            $rules[$champ] = match ($definition['type']) {
                'checkbox' => ['nullable', 'boolean'],

                'email' => ['nullable', 'email', 'max:190'],

                'number' => ['nullable', 'numeric', 'min:0'],

                'categorie' => [
                    'required',
                    'integer',
                    Rule::exists('support_categories_stocks', 'id')
                        ->where('actif', true),
                ],

                'unite' => [
                    'required',
                    'integer',
                    Rule::exists('support_unites_stocks', 'id')
                        ->where('actif', true),
                ],

                default => [
                    $required ? 'required' : 'nullable',
                    'string',
                    'max:2000',
                ],
            };
        }

        if (isset($rules['code'])) {
            $modelClass = $config['modele'];

            $table = $element
                ? $element->getTable()
                : (new $modelClass)->getTable();

            $rules['code'][] = Rule::unique($table, 'code')
                ->ignore($element?->id);
        }

        if ($type === 'articles') {
            $rules['stock_minimum'][] = 'max:999999999';
            $rules['stock_maximum'][] = 'max:999999999';
        }

        $validator = Validator::make(
            $request->all(),
            $rules
        );

        $validator->after(function ($validator) use ($request, $type) {
            if ($type !== 'articles') {
                return;
            }

            $minimum = $request->input('stock_minimum');
            $maximum = $request->input('stock_maximum');

            if (
                $minimum !== null &&
                $minimum !== '' &&
                $maximum !== null &&
                $maximum !== '' &&
                is_numeric($minimum) &&
                is_numeric($maximum) &&
                (float) $maximum < (float) $minimum
            ) {
                $validator->errors()->add(
                    'stock_maximum',
                    'Le stock maximum doit être supérieur ou égal au stock minimum.'
                );
            }
        });

        $data = $validator->validate();

        foreach ($config['champs'] as $champ => $definition) {
            if ($definition['type'] === 'checkbox') {
                $data[$champ] = $request->boolean($champ);
            }
        }

        return $data;
    }
}
