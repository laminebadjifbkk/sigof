<?php
namespace App\Http\Controllers;

use App\Models\Convention;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ReferentielController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Demandeur|DIOF|ADIOF|Ingenieur|DEC']);
        $this->middleware("permission:referentiel-view", ["only" => ["index"]]);
        $this->middleware("permission:referentiel-create", ["only" => ["create", "store"]]);
        $this->middleware("permission:referentiel-update", ["only" => ["update", "edit"]]);
        $this->middleware("permission:referentiel-show", ["only" => ["show"]]);
        $this->middleware("permission:referentiel-delete", ["only" => ["destroy"]]);
    }
    public function index()
    {
        $referentiels = Referentiel::get();
        $conventions  = Convention::get();

        return view('referentiels.index', compact('referentiels', 'conventions'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            "intitule"  => "required|string",
            'titre'     => [
                'required',
                'string',
                'max:250',
                Rule::unique('referentiels', 'titre')->whereNull('deleted_at'),
            ],
            "categorie" => "nullable|string",
            "reference" => "nullable|string",
        ]);

        $convention = Convention::where('name', $request?->convention)->first();

        if (! empty($convention)) {
            $convention_id = $convention?->id;
        } else {

            $convention = Convention::create([
                'name' => $request?->convention,
            ]);

            $convention?->save();

            $convention_id = $convention?->id;
        }

        $referentiel = Referentiel::create([
            'intitule'       => $request?->intitule,
            'titre'          => $request?->titre,
            'categorie'      => $request?->categorie,
            'reference'      => $request?->reference,
            'conventions_id' => $convention_id,
        ]);

        $referentiel?->save();

        Alert::success('Le référentiel ' . $referentiel?->titre, ' a été ajouté avec succès');

        return redirect()->back();
    }

    public function edit(Request $request, $id)
    {

        $referentiel = Referentiel::find($id);
        $conventions = Convention::get();

        return view('referentiels.update', compact('referentiel', 'conventions'));
    }

    public function update(Request $request, $id)
    {
        $referentiel = Referentiel::find($id);

        $this->validate($request, [
            "intitule"  => ['required', 'string'],
            'titre'     => [
                'required',
                'string',
                'max:250',
                Rule::unique('referentiels', 'titre')
                    ->ignore($id)
                    ->whereNull('deleted_at'),
            ],
            "categorie" => ['nullable', 'string'],
            "reference" => ['nullable', 'string'],
        ]);

        $convention = Convention::where('name', $request?->convention)->first();

        if (! empty($convention)) {
            $convention_id = $convention?->id;
        } else {

            $convention = Convention::create([
                'name' => $request?->convention,
            ]);

            $convention?->save();

            $convention_id = $convention?->id;
        }

        $referentiel->update([
            'intitule'       => $request?->intitule,
            'titre'          => $request?->titre,
            'categorie'      => $request?->categorie,
            'reference'      => $request?->reference,
            'conventions_id' => $convention_id,
        ]);

        $referentiel->save();

        Alert::success('Le référentiel ' . $referentiel?->titre, ' a été modifié avec succès');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $referentiel = Referentiel::find($id);
        $referentiel->delete();

        Alert::success('Le référentiel ' . $referentiel?->titre, 'a été supprimée avec succès');

        return redirect()->back();
    }
}
