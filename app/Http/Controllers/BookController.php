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
        $this->middleware('admin')->only(['index', 'create']);
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

        // Passer les données au fichier Blade
        return view('books.show', compact('book', 'filename', 'path'));
    }

    private function fileExists($filename)
    {
        $path = storage_path("app/books/{$filename}");
        return file_exists($path);
    }

}
