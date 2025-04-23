<?php
namespace App\Http\Controllers;

use App\Models\Convention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class ConventionController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|Demandeur|DIOF|ADIOF|Ingenieur|DEC']);
        $this->middleware("permission:convention-view", ["only" => ["index"]]);
        /* $this->middleware(['permission:arrive-show']); */
        // or with specific guard
        /* $this->middleware(['role_or_permission:super-admin']); */
    }
    public function index()
    {
        $conventions = Convention::get();
        return view('conventions.index', compact('conventions'));
    }
    public function store(Request $request)
    {

        $this->validate($request, [
            "name" => "required|string|unique:conventions,name,except,id",
        ]);

        $convention = Convention::create([
            'name' => $request?->name,
        ]);

        $convention?->save();

        Alert::success('La convention ', ' a été ajouté avec succès');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $convention = Convention::find($id);

        $this->validate($request, [
            "name" => ['required', 'string', 'max:250', Rule::unique(Convention::class)->ignore($id)],
        ]);

        $convention->update([
            'name' => $request?->name,
        ]);

        $convention->save();

        Alert::success('La convention ', ' a été modifié avec succès');

        return redirect()->back();
    }

    public function show($id)
    {
        $convention = Convention::find($id);

        return view('conventions.show', compact('convention'));
    }

    public function fetch(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');
            $data  = DB::table('conventions')
                ->whereNull('deleted_at') // Exclure les enregistrements supprimés
                ->where('name', 'LIKE', "%{$query}%")
                ->distinct()
                ->get();

            $output = '<ul class="dropdown-menu" style="display:block; position:relative;width:100%;">';
            foreach ($data as $row) {
                $output .= '
            <li><a class="dropdown-item" href="#">' . $row->name . '</a></li>
            ';
            }
            $output .= '</ul>';
            echo $output;
        }
    }

    public function destroy($id)
    {
        $convention = Convention::find($id);
        $convention->delete();

        Alert::success('La convention ', 'a été supprimée avec succès');

        return redirect()->back();
    }
}
