<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class CategorieController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DRH|ADRH|DG|SG']);
    }

    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view("employes.categories.index", compact('categories'));
    }

    public function create()
    {
        return view("employes.categories.create");
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'categories.*.name' => 'required|unique:categories,name',
        ]);

        /* dd($request->categories); */

        foreach ($request->categories as $key => $value) {
            Category::create($value);
        }

        /* Category::create([
            "name" => $request->name
        ]); */

        Alert::success('Succès !', 'La categorie a été ajoutée avec succès');
        return redirect()->back();
    }

    public function edit($id)
    {
        $categorie = Category::find($id);
        return view("employes.categories.update", compact('categorie'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', Rule::unique(Category::class)->ignore($id)],
        ]);

        Category::find($id)->update([
            'name' => $request->name,
        ]);

        Alert::success('Succès !', 'La categorie a été modifiée avec succès');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $categorie = Category::find($id);

        $categorie->delete();

        Alert::success('Succès !', 'La catégorie a été supprimée avec succès');

        return redirect()->back();
    }
}
