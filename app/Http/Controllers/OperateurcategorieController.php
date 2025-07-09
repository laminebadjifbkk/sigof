<?php
namespace App\Http\Controllers;

use App\Models\Operateurcategorie;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class OperateurcategorieController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DRH|ADRH|DG|SG']);
    }

    public function index()
    {
        $categories = Operateurcategorie::orderBy('created_at', 'desc')->get();
        return view("operateurs.categories.index", compact('categories'));
    }

    public function create()
    {
        return view("operateurs.categories.create");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'categories.*.name' => 'required|unique:operateurcategories,name',
        ]);

        /* dd($request->categories); */

        foreach ($request->categories as $key => $value) {
            Operateurcategorie::create($value);
        }

        Alert::success('Succès !', 'La catégorie a été ajoutée avec succès');
        return redirect()->back();
    }

    public function edit($id)
    {
        $categorie = Operateurcategorie::find($id);
        return view("operateurs.categories.update", compact('categorie'));
    }

    public function show($id)
    {
        $categorie = Operateurcategorie::find($id);
        return view("operateurs.categories.show", compact('categorie'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', Rule::unique(Operateurcategorie::class)->ignore($id)],
        ]);

        Operateurcategorie::findOrFail($id)->update([
            'name' => $request->name,
        ]);

        Alert::success('Succès !', 'La categorie a été modifiée avec succès');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $categorie = Operateurcategorie::find($id);

        $categorie->delete();

        Alert::success('Succès !', 'La catégorie a été supprimée avec succès');

        return redirect()->back();
    }
}
