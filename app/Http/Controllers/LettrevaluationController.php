<?php
namespace App\Http\Controllers;

use App\Models\Evaluateur;
use App\Models\Formation;
use App\Models\Lettrevaluation;
use App\Models\Onfpevaluateur;
use App\Models\Referentiel;
use Dompdf\Dompdf;
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

        $lettrevaluations = Lettrevaluation::latest()->get();
        $formations       = Formation::whereNotNull('numero_convention')->latest()->get();
        $onfpevaluateurs  = Onfpevaluateur::latest()->get();
        $evaluateurs      = Evaluateur::latest()->get();
        //$lettres = Lettrevaluation::where('users_id', Auth::id())->latest()->get();
        return view('formations.lettrevaluations.index', compact('lettrevaluations', 'formations', 'onfpevaluateurs', 'evaluateurs'));
    }

    // Formulaire de création
    public function create()
    {
        return view('formations.lettrevaluations.create');
    }

    // Enregistre une nouvelle lettre
    public function store(Request $request)
    {
        /* $validated = $request->validate([
            'titre'              => 'nullable|string|max:255',
            'contenu'            => 'nullable|string',
            'formations_id'      => 'required|exists:formations,id',
            'operateurs_id'      => 'nullable|exists:operateurs,id',
            'onfpevaluateurs_id' => 'required|exists:users,id',
            'evaluateurs_id'     => 'required|exists:users,id',
        ]);

        Lettrevaluation::create($validated); */

        $request->validate([
            'formation' => 'required|string|unique:lettrevaluations,formations_id',
            'contenu'   => 'nullable|string|max:500',
        ]);

        Lettrevaluation::create([
            'formations_id' => $request->input('formation'),
            'titre'         => Auth::user()->firstname . ' ' . Auth::user()->name,
            'contenu'       => $request->input('contenu'),
        ]);

        Alert::success('Succès !', 'Lettre créée avec succès.');
        return redirect()->back();
    }

    // Affiche une lettre
    public function show(Lettrevaluation $lettrevaluation, Request $request)
    {

        $formation = $lettrevaluation->formation;

        $title = 'Lettre de mission évaluation formation en  ' . $formation->name;

        $membres_jury  = explode(";", $formation->membres_jury);
        $count_membres = count($membres_jury);

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('formations.lettrevaluations.show', compact(
            'formation',
            'title',
            'membres_jury',
            'count_membres',
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'Lettre de mission évaluation formation en  ' . $formation->name . ', code ' . $formation->code . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);

    }

    // Formulaire d'édition
    public function edit(Lettrevaluation $lettrevaluation)
    {
        if (! Auth::user()->hasAnyRole(['DEC', 'ADEC', 'super-admin', 'admin'])) {
            Alert::error('Attention !', 'Accès refusé.');
            return redirect()->back();
        }

        $formation = $lettrevaluation->formation;

        $lettrevaluations = Lettrevaluation::latest()->get();
        $formations       = Formation::whereNotNull('numero_convention')->latest()->get();
        $onfpevaluateurs  = Onfpevaluateur::latest()->get();
        $evaluateurs      = Evaluateur::latest()->get();
        $referentiels     = Referentiel::latest()->get();
        return view('formations.lettrevaluations.update', compact('lettrevaluations', 'formations', 'onfpevaluateurs', 'evaluateurs', 'lettrevaluation', 'formation', 'referentiels'));
    }

    // Met à jour une lettre
    public function update(Request $request, Lettrevaluation $lettrevaluation)
    {
        $validated = $request->validate([
            'formation'        => 'required|string',
            'onfpevaluateur'   => 'required|string',
            'evaluateur'       => 'required|string',
            'frais_evaluateur' => 'nullable|string',
            'date_pv'          => 'nullable|string',
            'contenu'          => 'nullable|string|max:500',
            'execution_statut' => 'required|in:0,1',
        ]);
        function parseDateOrNull($value)
        {
            return ! empty($value) ? date('Y-m-d H:i:s', strtotime($value)) : null;
        }

        $formation = Formation::findOrFail($request->input('formation'));
        $date_pv   = parseDateOrNull($request->input('date_pv'));

        $referentiel = Referentiel::where('titre', $request->titre)->first();

// Détermination du type et du titre
        if (! empty($referentiel) && $request->titre !== 'Renforcement de capacités') {
            $referentiel_id = $referentiel->id;
            $titre          = null;
            $type           = 'Titre';
        } elseif ($request->titre === 'Renforcement de capacités') {
            $referentiel_id = null;
            $titre          = 'Renforcement de capacités';
            $type           = 'Attestation';
        } else {
            $referentiel_id = null;
            $titre          = null;
            $type           = null;
        }

        $lettrevaluation->update([
            'formations_id'    => $request->input('formation'),
            "execution_statut" => $request->input('execution_statut'),
            'contenu'          => $request->input('contenu'),
        ]);

        $formation->update([
            'evaluateurs_id'     => $request->input('evaluateur'),
            'onfpevaluateurs_id' => $request->input('onfpevaluateur'),
            'frais_evaluateur'   => $request->input('frais_evaluateur'),
            'date_pv'            => $date_pv,
            "type_certification" => $request->input('type_certification'),
            "titre"              => $titre,
            "referentiels_id"    => $referentiel_id,
        ]);

        Alert::success('Succès !', 'Mise à jour effectuée avec succès.');
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
