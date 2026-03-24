<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use setasign\Fpdi\Fpdi;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|admin|DIOF|DEC|DPP|Operateur|Demandeur|courrier|a-courrier|Ingenieur']);
        $this->middleware("permission:file-update", ["only" => ["update"]]);
    }

    public function index()
    {
        $files = File::latest()->limit(500)->get(); // Limite à 500 fichiers
        $users = User::limit(500)->get(); // Limite à 500 utilisateurs

        return view('files.index', compact('files', 'users'));
    }

    /* public function update(Request $request, User $user)
    {

        $this->validate($request, [
            'legende' => 'required |string',
            'file'    => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:1024',
        ]);

        $user = User::findOrFail($request->idUser);

        // ✅ Créer une nouvelle entrée si aucune n'existe pour l'utilisateur
        $existing = File::where('id', $request->input('legende'))
            ->where('users_id', $user->id)
            ->first();

        if (! $existing) {
            // On récupère une version générique du fichier (sans user)
            $generic = File::where('id', $request->input('legende'))
                ->whereNull('users_id')
                ->first();

            if ($generic) {
                // Créer une copie personnalisée pour l'utilisateur
                $file = File::create([
                    'legende'    => $generic->legende,
                    'sigle'      => $generic->sigle,
                    'users_id'   => $user->id,
                    'uuid'       => Str::uuid(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {

            // ✅ Récupérer le fichier à mettre à jour
            $file = File::where('id', $request->legende)
                ->where('users_id', $user->id)
                ->firstOrFail();
        }

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
            $file->update([
                'statut' => 'Nouveau',
                'file'   => $filePath,
            ]);

            // Message de succès
            Alert::success('Succès !', 'Fichier téléchargé avec succès');

            return redirect()->back();
        }

        // Return error response
        Alert::warning('erreur !', 'Échec du téléchargement du fichier');

        return redirect()->back();
    } */

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'legende' => 'required|string',
            'file'    => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
        ]);

        $user = User::findOrFail($request->idUser);

        // Récupérer la légende du fichier de base
        $generic = File::where('id', $request->input('legende'))
            ->whereNull('users_id')
            ->firstOrFail(); // doit exister

        // Vérifier si une version personnalisée existe déjà
        $file = File::where('legende', $generic->legende)
            ->where('users_id', $user->id)
            ->first();

        // Si elle n'existe pas, on la crée
        if (! $file) {
            $file = File::create([
                'legende'    => $generic->legende,
                'sigle'      => $generic->sigle,
                'users_id'   => $user->id,
                'uuid'       => Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request->hasFile('file')) {
            if (! empty($file->file)) {
                /* Storage::disk('public')->delete($file->file); */
                Alert::warning('Fichier existant', 'Veuillez d’abord supprimer le fichier actuel avant d’en téléverser un nouveau.');
                return redirect()->back();
            }

            $uploadedFile = $request->file('file');

            $legendeClean = preg_replace("/[^A-Za-z0-9]/", '', $file->legende);
            $originalName = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename     = time() . '_' . $legendeClean . '-' . $originalName . '.' . $uploadedFile->getClientOriginalExtension();

            $filePath = $uploadedFile->storeAs('uploads', $filename, 'public');

            $file->update([
                'statut' => 'Nouveau',
                'file'   => $filePath,
            ]);

            Alert::success('Succès !', 'Fichier téléchargé avec succès');
            return redirect()->back();
        }

        Alert::warning('Erreur !', 'Échec du téléchargement du fichier');
        return redirect()->back();
    }

    public function fileDestroy(Request $request)
    {
        $file = File::findOrFail($request->idFile);

        Storage::disk('public')->delete($file->file);

        $file->update([
            'file' => null,
        ]);

        /* Alert::success('Succès !', 'le fichier ' . $file->legende . ' a été retiré avec succès');

        return redirect()->back(); */

        $status = "Succès " . $file->legende . " a été retiré avec succès";
        return redirect()->back()->with('status', $status);
    }

    public function fileValidate(Request $request)
    {
        $file = File::findOrFail($request->idFile);

        $file->update([
            'statut' => 'Validé',
        ]);

        /* Alert::success('Succès !', 'le fichier ' . $file->legende . ' a été validé avec succès');
        return redirect()->back(); */

        $status = "Succès " . $file->legende . " a été validé avec succès";
        return redirect()->back()->with('status', $status);
    }

    public function fileInvalide(Request $request)
    {
        $file = File::findOrFail($request->idFile);

        $file->update([
            'statut' => 'Rejeté',
        ]);

        /* Alert::success('Succès !', 'le fichier ' . $file->legende . ' a été réjeté avec succès');
        return redirect()->back(); */

        $status = "Succès " . $file->legende . " a été validé avec succès";
        return redirect()->back()->with('status', $status);
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
        /* $this->validate($request, [
            'legende' => 'required|string',
            'user'    => 'required|string',
        ]); */
        $this->validate($request, [
            'legende' => 'required|string',
            'sigle'    => 'required|string',
        ]);

        /* $file = File::where('legende', $request?->legende)?->first();

        $sigle = $file?->sigle;

        $file = File::create([
            'legende'  => $request?->legende,
            'sigle'    => $sigle,
            'users_id' => $request?->user,
        ]); */

        File::create([
            'legende'  => $request?->legende,
            'sigle'    => $request?->sigle,
        ]);

        Alert::success('Succès !', 'fichier ajouté avec succès');
        return redirect()->back();
    }

    public function mergeFiles($id)
    {
        $files = File::where('users_id', $id)
            ->whereNotNull('file')
            ->get();

        if ($files->isEmpty()) {
            abort(404, 'Aucun fichier disponible');
        }

        $pdf = new Fpdi();
        $tempFiles = [];

        foreach ($files as $file) {
            $path = public_path($file->getFichier());
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            // --------------------------
            // PDF → utilisé directement
            // --------------------------
            if ($extension === 'pdf') {
                $tempFiles[] = $path;
            }

            // --------------------------
            // Images → convertir en PDF temporaire
            // --------------------------
            elseif (in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $tempPdf = storage_path('app/temp_' . uniqid() . '.pdf');

                $pdfImg = new \FPDF();
                $pdfImg->AddPage();
                $pdfImg->Image($path, 10, 10, 190); // ajuster largeur si nécessaire
                $pdfImg->Output($tempPdf, 'F');

                $tempFiles[] = $tempPdf;
            }
        }

        // --------------------------
        // Fusion finale
        // --------------------------
        foreach ($tempFiles as $filePath) {
            $pageCount = $pdf->setSourceFile($filePath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
        }

        // --------------------------
        // Nettoyage fichiers temporaires
        // --------------------------
        foreach ($tempFiles as $temp) {
            if (str_contains($temp, 'temp_') && file_exists($temp)) {
                unlink($temp);
            }
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="documents.pdf"');
    }
}
