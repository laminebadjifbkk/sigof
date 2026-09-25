<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteFraisRequest;
use App\Models\Formation;
use App\Models\NoteFrais;
use App\Models\Rubrique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class NoteFraisController extends Controller
{
    /**
     * Libellés et couleurs de badge pour chaque statut — réutilisés
     * à l'identique dans index/show.
     */
    private function statuts(): array
    {
        return [
            'BROUILLON' => ['label' => 'Brouillon', 'badge' => 'secondary'],
            'SOUMISE' => ['label' => 'Soumise', 'badge' => 'warning'],
            'VALIDEE_DIOF' => ['label' => 'Validée DIOF', 'badge' => 'info'],
            'VALIDEE_DF' => ['label' => 'Validée DF', 'badge' => 'primary'],
            'PAYEE' => ['label' => 'Payée', 'badge' => 'success'],
            'REJETEE' => ['label' => 'Rejetée', 'badge' => 'danger'],
        ];
    }

    public function index(Request $request)
    {
        $statuts = $this->statuts();

        $query = NoteFrais::query()
            ->with(['formation.operateur.user', 'lignes']);

        if ($search = $request->get('search')) {
            $query->whereHas('formation', function ($q) use ($search) {
                $q->where('intitule', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($statut = $request->get('statut')) {
            $query->where('statut', $statut);
        }

        $notesFrais = $query->orderByDesc('created_at')->get();

        return view('formations.notes-frais.index', [
            'notesFrais' => $notesFrais,
            'statuts' => $statuts,
            'hasActiveFilters' => $request->hasAny(['search', 'type', 'statut']),
            'totalNotes' => NoteFrais::count(),
            'notesEnAttente' => NoteFrais::whereIn('statut', ['SOUMISE', 'VALIDEE_DIOF'])->count(),
            'notesPayees' => NoteFrais::where('statut', 'PAYEE')->count(),
            'totalReliquats' => NoteFrais::where('type', 'DEFINITIVE')
                ->whereIn('statut', ['SOUMISE', 'VALIDEE_DIOF', 'VALIDEE_DF'])
                ->sum('reliquat'),
        ]);
    }

    public function create()
    {
        return view('formations.notes-frais.create', $this->sharedFormData());
    }

    public function store(NoteFraisRequest $request)
    {
        $noteFrais = DB::transaction(function () use ($request) {
            $noteFrais = NoteFrais::create($request->safe()->except('lignes'));
            $this->syncLignes($noteFrais, $request->input('lignes', []));
            $noteFrais->recalculerTotaux();

            return $noteFrais;
        });

        return redirect()
            ->route('formations.notes-frais.show', $noteFrais)
            ->with('success', 'Note de frais créée avec succès.');
    }

    public function show(NoteFrais $notes_frai)
    {
        $notes_frai->load(['formation.operateur.user', 'lignes.rubrique', 'noteAcompte']);

        return view('formations.notes-frais.show', [
            'noteFrais' => $notes_frai,
            'statuts' => $this->statuts(),
        ]);
    }

    public function edit(NoteFrais $notes_frai)
    {
        abort_unless($notes_frai->statut === 'BROUILLON', 403, 'Seule une note en brouillon peut être modifiée.');

        $notes_frai->load(['formation.operateur.user', 'lignes.rubrique']);

        return view('formations.notes-frais.edit', array_merge(
            ['noteFrais' => $notes_frai],
            $this->sharedFormData()
        ));
    }

    public function update(NoteFraisRequest $request, NoteFrais $notes_frai)
    {
        abort_unless($notes_frai->statut === 'BROUILLON', 403, 'Seule une note en brouillon peut être modifiée.');

        DB::transaction(function () use ($request, $notes_frai) {
            $notes_frai->update($request->safe()->except(['lignes', 'formations_id', 'type']));
            $this->syncLignes($notes_frai, $request->input('lignes', []));
            $notes_frai->recalculerTotaux();
        });

        return redirect()
            ->route('formations.notes-frais.show', $notes_frai)
            ->with('success', 'Note de frais mise à jour avec succès.');
    }

    public function destroy(NoteFrais $notes_frai)
    {
        abort_unless($notes_frai->statut === 'BROUILLON', 403, 'Seule une note en brouillon peut être supprimée.');

        $notes_frai->delete();

        return redirect()
            ->route('formations.notes-frais.index')
            ->with('success', 'Note de frais supprimée.');
    }

    public function soumettre(NoteFrais $notes_frai)
    {
        abort_unless($notes_frai->statut === 'BROUILLON', 403);
        abort_if($notes_frai->lignes()->count() === 0, 422, 'Impossible de soumettre une note sans lignes de frais.');

        $notes_frai->update(['statut' => 'SOUMISE']);

        return back()->with('success', 'Note de frais soumise pour validation.');
    }

    public function valider(NoteFrais $notes_frai)
    {
        abort_unless(in_array($notes_frai->statut, ['SOUMISE', 'VALIDEE_DIOF']), 403);

        $prochainStatut = $notes_frai->statut === 'SOUMISE' ? 'VALIDEE_DIOF' : 'VALIDEE_DF';

        $notes_frai->update([
            'statut' => $prochainStatut,
            'valide_par_id' => auth()->id(),
            'date_validation' => now(),
        ]);

        return back()->with('success', 'Note de frais validée.');
    }

    public function marquerPayee(Request $request, NoteFrais $notes_frai)
    {
        abort_unless($notes_frai->statut === 'VALIDEE_DF', 403, "Seule une note validée par la DF peut être marquée payée.");

        $data = $request->validate([
            'mode_paiement' => ['required', 'string', 'max:45'],
            'reference_paiement' => ['required', 'string', 'max:200'],
            'date_paiement' => ['required', 'date'],
        ]);

        $notes_frai->update(array_merge($data, [
            'statut' => 'PAYEE',
            'paye_par_id' => auth()->id(),
        ]));

        return back()->with('success', 'Paiement enregistré.');
    }

    public function pdf(NoteFrais $notes_frai)
    {
        $notes_frai->load(['formation', 'lignes.rubrique']);

        $pdf = Pdf::loadView('pdf.note_frais', [
            'title' => 'Note de frais',
            'noteFrais' => $notes_frai,
        ]);

        return $pdf->stream("note-frais-{$notes_frai->id}.pdf");
    }

    /**
     * Données communes aux formulaires create/edit : formations disponibles,
     * référentiel des rubriques, notes d'acompte pouvant être liées.
     */
    private function sharedFormData(): array
    {
        return [
            'formations' => Formation::with('operateur.user')->orderByDesc('id')->get(),
            'rubriques' => Rubrique::where('actif', true)->orderBy('ordre')->get(),
            'notesAcompte' => NoteFrais::where('type', 'ACOMPTE')
                ->whereIn('statut', ['VALIDEE_DF', 'PAYEE'])
                ->with('formation')
                ->get(),
        ];
    }

    /**
     * Synchronise les lignes soumises par le formulaire : met à jour les
     * lignes existantes (id présent), crée les nouvelles, supprime celles
     * retirées côté formulaire.
     */
    private function syncLignes(NoteFrais $noteFrais, array $lignes): void
    {
        $idsEnvoyes = collect($lignes)->pluck('id')->filter()->all();

        // Supprime les lignes retirées côté formulaire
        $noteFrais->lignes()->whereNotIn('id', $idsEnvoyes)->delete();

        foreach ($lignes as $ligne) {
            $noteFrais->lignes()->updateOrCreate(
                ['id' => $ligne['id'] ?? null],
                [
                    'uuid' => $ligne['id'] ?? (string) Str::uuid(),
                    'rubriques_id' => $ligne['rubriques_id'],
                    'unite' => $ligne['unite'] ?? null,
                    'qte' => $ligne['qte'],
                    'pu' => $ligne['pu'],
                ]
            );
        }
    }
}
