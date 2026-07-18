<?php

namespace App\Http\Controllers;

use App\Models\LanguesSpecialisation;
use App\Models\SessionsFormationTraducteur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function show(SessionsFormationTraducteur $session)
    {
        $session->load('langueSpecialisation', 'participants.candidature.user');

        return view('candidatures.sessions-formation.show', ['session' => $session]);
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
}
