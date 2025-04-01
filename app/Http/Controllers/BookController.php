<?php
namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only(['index', 'create', 'destroy', 'store']);
    }

    public function index()
    {
        $books = Book::all();

        return view('books.index', compact('books'));
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'auteur'      => 'required',
            'description' => 'required',
            'file'        => 'required|mimes:pdf|max:20480', // Max 20MB
        ]);

        $file     = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('books', $filename); // Stocke dans storage/app/books/

        Book::create([
            'title'       => $request->title,
            'author'      => $request->auteur,
            'description' => $request->description,
            'filename'    => $filename,
        ]);

        Alert::success("Succès !", "Manuel ajouté avec succès.");

        return redirect()->back();
    }

    public function show($filename)
    {
        if (! $this->fileExists($filename)) {
            abort(404, "Fichier introuvable.");
        }

        Log::info("Lecture du livre: {$filename}");

                                                                   // Récupérer les informations du livre à partir de la base de données
        $book = Book::where('filename', $filename)->firstOrFail(); // Recherche du livre par son nom de fichier

        $path = storage_path("app/books/{$filename}");

        $books = Book::all();
        // Passer les données au fichier Blade
        return view('books.show', compact('book', 'filename', 'path', 'books'));
    }

    private function fileExists($filename)
    {
        $path = storage_path("app/books/{$filename}");
        return file_exists($path);
    }

    public function showDefault()
    {
        // Récupérer le premier livre
        $book = Book::first();

        if (! $book) {
            Alert::error("Erreur", "Aucun livre disponible.");
            return redirect()->back();
        }

        // Rediriger vers la vue avec le premier livre
        return redirect()->route('book.view', $book->filename);
    }

    public function destroy($id)
    {
        // Trouver le livre par son ID ou échouer si non trouvé
        $book = Book::findOrFail($id);

        // Vérifier si un fichier est associé au livre et le supprimer
        if (! empty($book->filename)) {
            // Vérifier si le fichier existe avant de le supprimer
            $filePath = storage_path('app/public/' . $book->filename);

            if (file_exists($filePath)) {
                // Supprimer le fichier du stockage
                /* $deleted = Storage::disk('public')->delete($book->filename); */
                unlink($filePath);

                // Vérifier si la suppression du fichier a échoué
                if (! $deleted) {
                    Alert::error("Erreur", "Impossible de supprimer le fichier associé.");
                    return redirect()->back();
                }
            }
        }

        // Suppression du livre
        $book->delete();

        // Affichage de l'alerte de succès
        Alert::success("Succès !", "Manuel supprimé avec succès!");

        // Rediriger l'utilisateur vers la page précédente ou vers une autre page
        return redirect()->route('books.index');
    }

}
