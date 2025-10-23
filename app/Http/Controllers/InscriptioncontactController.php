<?php
namespace App\Http\Controllers;

use App\Mail\ConfirmationInscription;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class InscriptioncontactController extends Controller
{
    /* public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\App\Forms\InscriptioncontactForm::class, [
            'method' => 'POST',
            'url'    => route('inscriptioncontact.store'),
        ]);

        return view('inscriptioncontact.create', compact('form'));
    } */

    public function create()
    {
        // Pas besoin de FormBuilder, on affiche directement la vue
        return view('inscriptioncontact.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'structure'   => 'required|string|max:255|unique:inscriptions,structure',
            'nom'         => 'required|string|max:255',
            'fonction'    => 'required|string|max:255',
            'telephone'   => 'required|string|max:50',
            'email'       => 'required|email|max:255|unique:inscriptions,email',
            'commentaire' => 'nullable|string|max:255',
        ]);

        // Enregistrer en base
        $inscription = Inscription::create($validated);

        // Envoyer l'email
        Mail::to($inscription->email)->send(new ConfirmationInscription($inscription));

        // Si tu ne stockes pas, tu peux par exemple juste afficher un message :
        /* return back()->with('success', 'Votre confirmation a été enregistrée. Merci !'); */

        // Ici tu peux envoyer un mail ou traiter les données si nécessaire
        return redirect()->route('inscriptioncontact.merci');
    }

    public function merci()
    {
        return view('inscriptioncontact.merci');
    }

    public function index()
    {
        $inscriptions = Inscription::all();
        return view('inscriptioncontact.index', compact('inscriptions'));
    }

    public function show(Inscription $inscription)
    {
        return view('inscriptioncontact.show', compact('inscription'));
    }

    public function showAjax($id)
    {
        try {
            $inscription = Inscription::find($id);

            if (! $inscription) {
                return response()->json(['error' => 'Inscription non trouvée'], 404);
            }

            return response()->json([
                'structure'   => $inscription->structure ?? '',
                'nom'         => $inscription->nom ?? '',
                'telephone'   => $inscription->telephone ?? '',
                'email'       => $inscription->email ?? '',
                'commentaire' => $inscription->commentaire ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {

        $inscription = Inscription::findOrFail($id);

        $inscription->delete();

        Alert::success('Succès', 'Inscription supprimée avec succès.');

        return redirect()->route('inscriptioncontacts.index');
    }
}
