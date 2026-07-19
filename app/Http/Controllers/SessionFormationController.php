<?php

namespace App\Http\Controllers;

use App\Models\LanguesSpecialisation;
use App\Models\SessionsFormationTraducteur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Candidature;
use App\Models\FormationsTraducteur;

class SessionFormationController extends Controller
{
    public function index()
    {
        $sessions = SessionsFormationTraducteur::with('langueSpecialisation')
            ->withCount('participants')
            ->latest('date_debut')
            ->get();

        return view('candidatures.sessions-formation.index', compact('sessions'));
    }

    public function create()
    {
        $languesSpecialisations = LanguesSpecialisation::orderBy('nom')->get();

        return view('candidatures.sessions-formation.create', compact('languesSpecialisations'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSession($request);

        SessionsFormationTraducteur::create($validated);

        return redirect()
            ->route('sessions-formation.index')
            ->with('success', 'La session de formation a été créée.');
    }

    /* public function show(SessionsFormationTraducteur $session)
    {
        $session->load('langueSpecialisation', 'participants.candidature.user');

        return view('candidatures.sessions-formation.show', ['session' => $session]);
    } */

    public function show(SessionsFormationTraducteur $session)
    {
        $session->load('langueSpecialisation', 'participants.candidature.user');

        // Candidatures validées, pas encore affectées à une session
        $candidaturesDisponibles = Candidature::with('user', 'langueSpecialisation')
            ->where('statut', 'validee')
            ->whereDoesntHave('formation', function ($query) {
                $query->whereNotNull('session_formation_id');
            })
            ->when($session->langue_specialisation_id, function ($query) use ($session) {
                $query->where('langue_specialisation_id', $session->langue_specialisation_id);
            })
            ->get();

        return view('candidatures.sessions-formation.show', [
            'session'                  => $session,
            'candidaturesDisponibles' => $candidaturesDisponibles,
        ]);
    }

    public function edit(SessionsFormationTraducteur $session)
    {
        $languesSpecialisations = LanguesSpecialisation::orderBy('nom')->get();

        return view('candidatures.sessions-formation.edit', compact(
            'session',
            'languesSpecialisations'
        ));
    }

    public function update(Request $request, SessionsFormationTraducteur $session)
    {
        $validated = $this->validateSession($request);

        $session->update($validated);

        return redirect()
            ->route('sessions-formation.show', $session)
            ->with('success', 'La session de formation a été mise à jour.');
    }

    public function destroy(SessionsFormationTraducteur $session)
    {
        if ($session->participants()->exists()) {
            return redirect()
                ->route('sessions-formation.index')
                ->with('error', 'Impossible de supprimer une session qui a des traducteurs affectés. Retirez-les d\'abord.');
        }

        $session->delete();

        return redirect()
            ->route('sessions-formation.index')
            ->with('success', 'La session de formation a été supprimée.');
    }

    private function validateSession(Request $request): array
    {
        return $request->validate([
            'nom'                       => ['required', 'string', 'max:255'],
            'langue_specialisation_id'  => ['nullable', 'exists:langues_specialisations,id'],
            'formateur'                  => ['nullable', 'string', 'max:255'],
            'lieu'                       => ['nullable', 'string', 'max:255'],
            'date_debut'                 => ['required', 'date'],
            'date_fin'                   => ['required', 'date', 'after_or_equal:date_debut'],
            'statut'                     => ['required', Rule::in(['planifiee', 'en_cours', 'terminee', 'annulee'])],
            'description'                => ['nullable', 'string', 'max:2000'],
        ]);
    }

    public function affecterTraducteurs(Request $request, SessionsFormationTraducteur $session)
    {
        $validated = $request->validate([
            'candidature_ids'   => ['required', 'array', 'min:1'],
            'candidature_ids.*' => ['exists:candidatures,id'],
        ]);

        foreach ($validated['candidature_ids'] as $candidatureId) {
            FormationsTraducteur::updateOrCreate(
                ['candidature_id' => $candidatureId],
                [
                    'session_formation_id' => $session->id,
                    'statut_formation'     => 'inscrit',
                ]
            );
        }

        $count = count($validated['candidature_ids']);

        return redirect()
            ->route('sessions-formation.show', $session)
            ->with('success', $count . ' traducteur(s) affecté(s) à cette session.');
    }

    public function retirerTraducteur(SessionsFormationTraducteur $session, FormationsTraducteur $formationTraducteur)
    {
        if ($formationTraducteur->session_formation_id !== $session->id) {
            abort(404);
        }

        $formationTraducteur->update([
            'session_formation_id' => null,
            'statut_formation'     => 'non_inscrit',
        ]);

        return redirect()
            ->route('sessions-formation.show', $session)
            ->with('success', 'Le traducteur a été retiré de cette session.');
    }

    public function evaluer(Request $request, SessionsFormationTraducteur $session, FormationsTraducteur $participant)
    {
        $data = $request->validate([
            'note_evaluation' => 'nullable|numeric|min:0|max:20',
            'resultat_evaluation' => 'required|in:reussi,echoue,rattrapage',
            'commentaire_evaluation' => 'nullable|string|max:1000',
        ]);

        $participant->update([
            ...$data,
            'date_evaluation' => now(),
            'evalue_par' => auth()->id(),
            /* 'statut_formation' => $data['resultat_evaluation'] === 'reussi' ? 'complete' : 'en_cours', */
            'statut_formation' => $data['resultat_evaluation'] === 'reussi' ? 'complete' : 'complete',
        ]);

        return back()->with('success', 'Évaluation enregistrée avec succès.');
    }
}
