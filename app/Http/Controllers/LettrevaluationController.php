<?php
namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\Lettrevaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LettrevaluationController extends Controller
{
    // Affiche la liste
    public function index()
    {
        // Vérifie si l'utilisateur est un administrateur

        if (! Auth::user()->hasAnyRole(['DEC', 'ADEC', 'super-admin', 'admin'])) {
            Alert::error('Attention !', 'Accès refusé.');
            /* return redirect()->route('home')->with('error', 'Accès refusé.'); */
            return redirect()->back();
        }

        /* $lettres    = Lettrevaluation::latest()->get(); */
        /* $formations = Formation::latest()->get(); */
        /*  $formations = Formation::with('lettrevaluations')->latest()->get(); */
        $formations = Formation::select('*')->orderBy('created_at', 'desc')->get();
        dd("ok");
        //$lettres = Lettrevaluation::where('users_id', Auth::id())->latest()->get();
        return view('formations.lettrevaluations.index', compact('formations'));
    }

    // Formulaire de création
    public function create()
    {
        return view('formations.lettrevaluations.create');
    }

    // Enregistre une nouvelle lettre
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'              => 'required|string|max:255',
            'contenu'            => 'required|string',
            'users_id'           => 'nullable|exists:users,id',
            'formations_id'      => 'nullable|exists:formations,id',
            'operateurs_id'      => 'nullable|exists:operateurs,id',
            'onfpevaluateurs_id' => 'nullable|exists:users,id',
            'evaluateurs_id'     => 'nullable|exists:users,id',
        ]);

        Lettrevaluation::create($validated);

        Alert::success('Succès !', 'Lettre créée avec succès.');
        return redirect()->back();
    }

    // Affiche une lettre
    public function show(Lettrevaluation $lettrevaluation)
    {
        return view('formations.lettrevaluations.show', compact('lettrevaluation'));
    }

    // Formulaire d'édition
    public function edit(Lettrevaluation $lettrevaluation)
    {
        return view('formations.lettrevaluations.edit', compact('lettrevaluation'));
    }

    // Met à jour une lettre
    public function update(Request $request, Lettrevaluation $lettrevaluation)
    {
        $validated = $request->validate([
            'titre'              => 'nullable|string|max:255',
            'contenu'            => 'nullable|string',
            'users_id'           => 'nullable|exists:users,id',
            'formations_id'      => 'nullable|exists:formations,id',
            'operateurs_id'      => 'nullable|exists:operateurs,id',
            'onfpevaluateurs_id' => 'nullable|exists:users,id',
            'evaluateurs_id'     => 'nullable|exists:users,id',
        ]);

        $lettrevaluation->update($validated);

        Alert::success('Succès !', 'Lettre mise à jour avec succès.');
        // Envoie un message de succès
        return redirect()->back();
    }

    // Supprime une lettre
    public function destroy(Lettrevaluation $lettrevaluation)
    {
        $lettrevaluation->delete();
        Alert::success('Succès !', 'Lettre supprimée avec succès.');
        return redirect()->back();
    }
}
