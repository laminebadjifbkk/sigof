<?php
namespace App\Http\Controllers;

use App\Models\Operateur;
use App\Models\Validationoperateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ValidationoperateurController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DEC|ADEC']);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "motif" => "required|string",
        ]);

        $operateur = Operateur::findOrFail($id);

        $operateur->update([
            'statut_agrement' => 'sous réserve',
            'motif'           => $request->input('motif'),
        ]);

        $operateur->save();

        $validationoperateur = new Validationoperateur([
            'action'        => "agréé sous réserve",
            'motif'         => $request->input('motif'),
            'validated_id'  => Auth::user()->id,
            'session'       => $operateur?->session_agrement,
            'operateurs_id' => $operateur->id,

        ]);

        $validationoperateur->save();

        Alert::success('Effectué !', $operateur->sigle . ' agréé sous réserve');

        return redirect()->back();
    }

    public function destroy(Request $request, $id)
    {
        /* $this->validate($request, [
            "motif" => "required|string",
        ]);

        $operateur = Operateur::findOrFail($id);

        $operateur->update([
            'statut_agrement' => 'Rejetée',
            'motif'           => $request->input('motif'),
        ]);

        $operateur->save();

        $validationoperateur = new Validationoperateur([
            'action'        => 'Rejetée',
            'motif'         => $request->input('motif'),
            'validated_id'  => Auth::user()->id,
            'session'       => $operateur?->session_agrement,
            'operateurs_id' => $operateur->id,

        ]);

        $validationoperateur->save();

        Alert::success('Succès !', $operateur->sigle . ' a été rejeté');

        return redirect()->back(); */

        $statut = $request->statut;

        $request->validate([
            'motif' => 'required|string',
        ]);

        $operateur = Operateur::findOrFail($id);
        $statut    = $operateur->statut_agrement;

        // Bloquer certains statuts uniquement pour les non-super-admins
        if (! auth()->user()->hasAnyRole(['super-admin', 'Ingenieur'])) {
            $messages = [
                'Rejetée'      => 'demande déjà rejetée',
                'Programmer'   => 'demande déjà programmée',
                'Attente'      => 'demande déjà traitée',
                'Retenue'      => 'demande déjà traitée',
                'Terminée'     => 'demandeur déjà formé',
                'Former'       => 'demandeur déjà formé',
                'À corriger'   => 'demandeur déjà traitée',
                'Non validé'   => 'demandeur déjà traitée',
                'Conforme'     => 'demandeur déjà traitée',
                'Non conforme' => 'demandeur déjà traitée',
                'agréé'        => 'demandeur déjà traitée',
                'sous réserve' => 'demandeur déjà traitée',
                'rejeté'       => 'demandeur déjà traitée',
            ];

            if (array_key_exists($statut, $messages)) {
                Alert::warning('Désolé !', $messages[$statut]);
                return redirect()->back();
            }
        }

        /* if ($operateur->statut_agrement == 'Nouveau' || $operateur->statut_agrement == 'Retenue') { */
        $operateur->update([
            'statut_agrement' => $request->statut,
            'motif'           => $request->input('motif'),
        ]);

        $operateur->save();

        $validationoperateur = new Validationoperateur([
            'action'        => $request->statut,
            'motif'         => $request->input('motif'),
            'validated_id'  => Auth::user()->id,
            'session'       => $operateur?->session_agrement,
            'operateurs_id' => $operateur->id,

        ]);

        $validationoperateur->save();

        Alert::success('Succès !', $operateur?->user?->username . " est " . $request->statut);

        return redirect()->back();
    }
}
