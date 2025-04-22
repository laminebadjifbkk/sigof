<?php
namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DIOF|DEC|DPP|Operateur|Demandeur|courrier|a-courrier']);
        $this->middleware("permission:file-update", ["only" => ["update"]]);
    }
    public function index()
    {
        $files = File::get();
        $users = User::get();

        return view('files.index', compact('files', 'users'));
    }
    public function update(Request $request, User $user)
    {
        $user = User::findOrFail($request->idUser);

        $this->validate($request, [
            'legende' => 'required |string',
            'file'    => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:1024',
        ]);

        $file = File::where('id', $request->legende)
            ->where('users_id', $user->id)
            ->firstOrFail();

        /*    // Check if the file is valid
        if ($request->file('file')->isValid()) {
            // Store the file in the 'uploads' directory on the 'public' disk
            $filePath = $request->file('file')->store('uploads', 'public');
            if (! empty($file->file)) {
                Storage::disk('public')->delete($file->file);
            }
            // Return success response
            $file->update([
                'file' => $filePath,
            ]);

            $file->save();

            Alert::success('réussi !', 'Fichier téléchargé avec succès');

            return redirect()->back();
        } */

        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier s'il existe
            if (! empty($file->file)) {
                Storage::disk('public')->delete($file->file);
            }

            // Récupérer le fichier uploadé
            $uploadedFile = $request->file('file');

                                                                                        // Nettoyage et génération du nom de fichier
            $legende  = preg_replace("/[^A-Za-z0-9]/", '', $request->input('legende')); // Nettoyage de la légende
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = trim($legende) . '-' . $filename; // Ajout de la légende
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            // Mettre à jour le modèle en base de données
            $file->update(['file' => $filePath]);

            // Message de succès
            Alert::success('Succès !', 'Fichier téléchargé avec succès');

            return redirect()->back();
        }

        // Return error response
        Alert::warning('erreur !', 'Échec du téléchargement du fichier');

        return redirect()->back();
    }

    public function fileDestroy(Request $request)
    {
        $file = File::findOrFail($request->idFile);

        Storage::disk('public')->delete($file->file);

        $file->update([
            'file' => null,
        ]);

        Alert::success('Succès !', 'le fichier ' . $file->legende . ' a été retiré avec succès');
        return redirect()->back();
    }

    public function fileValidate(Request $request)
    {
        $file = File::findOrFail($request->idFile);

        $file->update([
            'statut' => 'Validé',
        ]);

        Alert::success('Succès !', 'le fichier ' . $file->legende . ' a été validé avec succès');
        return redirect()->back();
    }

    public function fileInvalide(Request $request)
    {
        $file = File::findOrFail($request->idFile);

        $file->update([
            'statut' => 'Rejeté',
        ]);

        Alert::success('Succès !', 'le fichier ' . $file->legende . ' a été réjeté avec succès');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $_FILES = File::find($id);
        $_FILES->delete();

        Alert::success('Fait', 'fichier supprimé avec succès');
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'legende' => 'required|string',
            'user'    => 'required|string',
        ]);

        $file = File::where('legende', $request?->legende)?->first();

        $sigle = $file?->sigle;

        $file = File::create([
            'legende'  => $request?->legende,
            'sigle'    => $sigle,
            'users_id' => $request?->user,
        ]);

        $file?->save();

        Alert::success('Félicitations !!!', 'fichier ajouté avec succès');
        return redirect()->back();
    }
}
