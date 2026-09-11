<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OnfpActivite;
use App\Models\OnfpActiviteDocument;
use App\Models\OnfpTache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OnfpActiviteDocumentController extends Controller
{
    /**
     * Liste des documents d'une activité.
     */
    public function index(OnfpActivite $activite)
    {
        $documents = $activite->documents()
            ->with(['employee', 'tache'])
            ->latest()
            ->paginate(15);

        return view('onfp.activites.documents.index', compact(
            'activite',
            'documents'
        ));
    }

    /**
     * Formulaire d'ajout.
     */
    public function create(OnfpActivite $activite)
    {
        $taches = $activite->taches()
            ->orderBy('titre')
            ->get();

        return view('onfp.activites.documents.create', compact(
            'activite',
            'taches'
        ));
    }

    /**
     * Enregistrement du document.
     */
    public function store(Request $request, OnfpActivite $activite)
    {
        $validated = $request->validate([
            'tache_id' => [
                'nullable',
                'integer',
                'exists:onfp_taches,id',
            ],

            'type' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'document_final' => [
                'nullable',
                'boolean',
            ],

            'document' => [
                'required',
                'file',
                'max:20480', // 20 Mo
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip',
            ],
        ]);

        /*
         * Vérifier que la tâche appartient bien à l'activité.
         */
        if (!empty($validated['tache_id'])) {
            $tacheExiste = $activite->taches()
                ->whereKey($validated['tache_id'])
                ->exists();

            if (!$tacheExiste) {
                return back()
                    ->withErrors([
                        'tache_id' => 'La tâche sélectionnée n’appartient pas à cette activité.'
                    ])
                    ->withInput();
            }
        }

        $file = $request->file('document');

        $nomOriginal = $file->getClientOriginalName();

        $nomFichier = Str::uuid() . '.' . $file->getClientOriginalExtension();

        /*
         * Organisation :
         * storage/app/public/onfp/activites/{id}/documents
         */
        $chemin = $file->storeAs(
            'onfp/activites/' . $activite->id . '/documents',
            $nomFichier,
            'public'
        );

        /*
         * Récupération de l'employé connecté.
         */
        $employeeId = Employee::where(
            'users_id',
            auth()->id()
        )->value('id');

        $activite->documents()->create([
            'tache_id'        => $validated['tache_id'] ?? null,
            'employee_id'     => $employeeId,
            'type'            => $validated['type'],
            'nom_original'    => $nomOriginal,
            'nom_fichier'     => $nomFichier,
            'disk'            => 'public',
            'chemin'          => $chemin,
            'mime_type'       => $file->getMimeType(),
            'taille'          => $file->getSize(),
            'description'     => $validated['description'] ?? null,
            'document_final'  => $request->boolean('document_final'),
        ]);

        return redirect()
            ->route('onfp.activites.documents.index', $activite)
            ->with('success', 'Document ajouté avec succès.');
    }

    /**
     * Téléchargement d'un document.
     */
    public function download(
        OnfpActivite $activite,
        OnfpActiviteDocument $document
    ) {
        $this->verifierAppartenance($activite, $document);

        if (!Storage::disk($document->disk)->exists($document->chemin)) {
            abort(404, 'Le fichier demandé est introuvable.');
        }

        return Storage::disk($document->disk)->download(
            $document->chemin,
            $document->nom_original
        );
    }

    /**
     * Suppression.
     */
    public function destroy(
        OnfpActivite $activite,
        OnfpActiviteDocument $document
    ) {
        $this->verifierAppartenance($activite, $document);

        /*
         * Suppression physique du fichier.
         */
        if (
            $document->chemin &&
            Storage::disk($document->disk)->exists($document->chemin)
        ) {
            Storage::disk($document->disk)->delete($document->chemin);
        }

        /*
         * Soft delete de l'enregistrement.
         */
        $document->delete();

        return redirect()
            ->route('onfp.activites.documents.index', $activite)
            ->with('success', 'Document supprimé avec succès.');
    }

    /**
     * Vérifie qu'un document appartient bien à l'activité.
     */
    private function verifierAppartenance(
        OnfpActivite $activite,
        OnfpActiviteDocument $document
    ): void {
        if ($document->activite_id !== $activite->id) {
            abort(404);
        }
    }
}