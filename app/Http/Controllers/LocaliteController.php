<?php
namespace App\Http\Controllers;

use App\Models\Departement;

class LocaliteController extends Controller
{
    public function __construct()
    {
        // examples:
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin']);
        $this->middleware("permission:localite-view", ["only" => ["index"]]);
    }
    public function index()
    {
        $localites = Departement::orderBy("created_at", "desc")->get();
        return view("localites.index", compact("localites"));
    }

    /*   public function show($id)
    {
        $localite = Region::findOrFail($id);

        return view("localites.show", compact("localite"));
    } */

    public function show($id)
    {                                // On ne sélectionne que les colonnes utiles pour alléger la requête
        $localite = Departement::select('id', 'nom', 'created_at') // adapte selon ton modèle
            ->findOrFail($id);

        return view("localites.show", compact("localite"));
    }

}
