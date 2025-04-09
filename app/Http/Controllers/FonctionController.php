<?php
namespace App\Http\Controllers;

use App\Models\Fonction;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class FonctionController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DRH|ADRH']);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }
    public function index()
    {
        $fonctions = Fonction::orderBy('created_at', 'desc')->get();
        return view("employes.fonctions.index", compact('fonctions'));
    }

    public function create()
    {
        return view("employes.fonctions.create");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            /* 'fonctions.*.name' => 'required|unique:fonctions,name', */
            'fonctions.*.name'  => 'required|string',
            'fonctions.*.sigle' => 'required|string',
        ]);

        /* dd($request->fonctions); */

        foreach ($request->fonctions as $key => $value) {
            Fonction::create($value);
        }

        /* Fonction::create([
            "name" => $request->name
        ]); */

        Alert::success('Succès !', "La fonction a été ajoutée avec succès.");

        return redirect()->back();

    }

    public function edit($id)
    {
        $fonction = Fonction::findOrFail($id);
        return view("employes.fonctions.update", compact('fonction'));
    }

    public function show($id)
    {
        $fonction = Fonction::findOrFail($id);
        
        return view("employes.fonctions.show", compact('fonction'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            /* 'name' => ['required', 'string', Rule::unique(Fonction::class)->ignore($id)], */
            'name'  => ['required', 'string'],
            'sigle' => ['required', 'string'],
        ]);

        Fonction::findOrFail($id)->update([
            'name'  => $request->name,
            'sigle' => $request->sigle,
        ]);

        Alert::success('Succès !', "La fonction a été modifiée avec succès.");

        return redirect()->back();
    }

    public function destroy($id)
    {
        $fonction = Fonction::findOrFail($id);
        $fonction->delete();
        $mesage = 'La Fonction ' . $fonction->name . ' a été supprimée';
        return redirect()->back()->with("danger", $mesage);
    }
}
