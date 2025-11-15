<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['index', 'create', 'destroy', 'store']);
    }

    public function index()
    {
        $manuels = Book::orderBy('created_at', 'desc')->get();

        return view('manuels.index', compact('manuels'));
    }

    public function create()
    {
        return view('manuels.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'auteur'      => 'required',
            'description' => 'required',
            'file'        => 'required|mimes:pdf|max:81920', // Max 80MB
        ]);

        $file     = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();

        $file->storeAs('manuels', $filename, 'public'); // Stocke dans storage/app/manuels/

        Book::create([
            'title'       => $request->title,
            'author'      => $request->auteur,
            'description' => $request->description,
            'filename'    => $filename,
        ]);

        Alert::success("Succès !", "Manuel ajouté avec succès.");

        return redirect()->back();
    }

    public function edit($id)
    {
        // Trouver le livre par son ID
        $manuel = Book::findOrFail($id);

        return view('manuels.update', compact('manuel'));
    }

    public function update(Request $request, $id)
    {
        // Validation des données
        $request->validate([
            'title'       => 'required',
            'auteur'      => 'required',
            'description' => 'required',
            'file'        => 'nullable|mimes:pdf|max:81920', // Max 80MB
        ]);

        // Trouver le livre par son ID
        $manuel = Book::findOrFail($id);

        // Mettre à jour les informations textuelles
        $manuel->title       = $request->title;
        $manuel->author      = $request->auteur;
        $manuel->description = $request->description;

        // Si un nouveau fichier est téléchargé
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            Storage::disk('public')->delete('manuels/' . $manuel->filename);

            // Enregistrer le nouveau fichier
            $file     = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('manuels', $filename);

            // Mettre à jour le nom du fichier dans la base de données
            $manuel->filename = $filename;
        }

        // Sauvegarder les changements dans la base de données
        $manuel->save();

        Alert::success("Succès !", "Manuel mis à jour avec succès.");

        return redirect()->back();
    }

    public function show($filename)
    {

        /* dd($filename); */

        /*  if (! $this->fileExists($filename)) {
            abort(404, "Fichier introuvable.");
        }

        Log::info("Lecture du livre: {$filename}"); */
                                                                     // Récupérer les informations du livre à partir de la base de données
        $manuel = Book::where('filename', $filename)->firstOrFail(); // Recherche du livre par son nom de fichier

        $path = storage_path("app/manuels/{$filename}");

        $manuels = Book::orderBy('created_at', 'desc')->get();
        // Passer les données au fichier Blade
        return view('manuels.show', compact('manuel', 'filename', 'path', 'manuels'));
    }

    private function fileExists($filename)
    {
        $path = storage_path("app/manuels/{$filename}");
        return file_exists($path);
    }

    public function showDefault()
    {
        // Récupérer le premier livre
        $manuel = Book::first();

        if (! $manuel) {
            Alert::error("Erreur", "Aucun livre disponible.");
            return redirect()->back();
        }

        // Rediriger vers la vue avec le premier livre
        return redirect()->route('manuel.view', $manuel->filename);
    }

    public function destroy($id)
    {
        // Trouver le livre par son ID ou échouer si non trouvé
        $manuel = Book::findOrFail($id);

        // Vérifier si un fichier est associé au livre et le supprimer
        if (! empty($manuel->filename)) {
            // Vérifier si le fichier existe avant de le supprimer
            $filePath = storage_path('app/public/' . $manuel->filename);

            if (file_exists($filePath)) {
                // Supprimer le fichier du stockage
                /* $deleted = Storage::disk('public')->delete($manuel->filename); */
                unlink($filePath);

                // Vérifier si la suppression du fichier a échoué
                if (! $deleted) {
                    Alert::error("Erreur", "Impossible de supprimer le fichier associé.");
                    return redirect()->back();
                }
            }
        }

        // Suppression du livre
        $manuel->delete();

        // Affichage de l'alerte de succès
        Alert::success("Succès !", "Manuel supprimé avec succès!");

        // Rediriger l'utilisateur vers la page précédente ou vers une autre page
        return redirect()->route('manuels.index');
    }

}
