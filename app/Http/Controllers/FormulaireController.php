<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationInscriptionPchare;
use App\Models\Formulaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FormulaireController extends Controller
{
    // Affichage du formulaire
    public function create()
    {
        // Définir la période d'ouverture des inscriptions
        $debut = Carbon::create(2025, 11, 10, 8, 0, 0);   // 10 novembre 2025 à 08h00
        $fin   = Carbon::create(2025, 11, 12, 17, 0, 0);  // 12 novembre 2025 à 17h00

        $now = Carbon::now();

        // Vérifier si on est hors période
        if ($now->lt($debut) || $now->gt($fin)) {
            Alert::error('Désolé', 'Les inscriptions ne sont ouvertes que du 10 novembre à 08h00 au 12 novembre à 17h00.');
            return redirect()->back(); // ou une autre route sûre
        }

        // Sinon afficher le formulaire
        return view('formulaire.create');
    }

    // Enregistrement du formulaire
    /* public function store(Request $request)
    {
        $validated = $request->validate([
            'cin'                  => 'required|string|max:14|unique:formulaires,cin',
            'civilite'             => 'required|string|max:5',
            'prenom'               => 'required|string',
            'nom'                  => 'required|string',
            'date_naissance'       => 'required|date',
            'lieu_naissance'       => 'required|string',
            'email'                => 'nullable|email|unique:formulaires,email',
            'telephone'            => 'required|string',
            'telephone_secondaire' => 'nullable|string',
            'adresse'              => 'required|string',
            'dernier_diplome'      => 'nullable|string',
            'nom_etablissement'    => 'required|string',
            'region'               => 'required|string',
            'formation'            => 'required|string',
            'diplome_vise'         => 'required|string',
            'montant_inscription'  => 'required|numeric|min:0',
            'montant_mensualite'   => 'required|numeric|min:0',
            'montant_unique'       => 'nullable|numeric|min:0',
            'duree'                => 'required|integer|min:1|max:3',
            'handicape'            => 'required|string',
            'type_handicap'        => 'nullable|string',
            'orphelin'             => 'required|string',
            'type_orphelin'        => 'nullable|string',
        ]);

        // Convertir les champs numériques vides en null
        $validated['montant_unique'] = $validated['montant_unique'] === '' ? null : $validated['montant_unique'];

        Alert::error('Désolé', 'Les inscriptions n\'ont pas encore démarré.');

        return redirect()->back();

        $formulaire = Formulaire::create($validated);

        Mail::to($validated['email'])->send(new ConfirmationInscriptionPchare($formulaire));

        Alert::success('Succès', 'Inscription effectuée avec succès.');

        return redirect()->route('formulaire.merci');
    } */

    /* public function store(Request $request)
    {
        $validated = $request->validate([
            'cin'                  => 'required|string|max:14|unique:formulaires,cin',
            'civilite'             => 'required|string|max:5',
            'prenom'               => 'required|string',
            'nom'                  => 'required|string',
            'date_naissance'       => 'required|date',
            'lieu_naissance'       => 'required|string',
            'email'                => 'nullable|email|unique:formulaires,email',
            'telephone'            => 'required|string',
            'telephone_secondaire' => 'nullable|string',
            'adresse'              => 'required|string',
            'dernier_diplome'      => 'nullable|string',
            'nom_etablissement'    => 'required|string',
            'region'               => 'required|string',
            'formation'            => 'required|string',
            'diplome_vise'         => 'required|string',
            'montant_inscription'  => 'required|numeric|min:0',
            'montant_mensualite'   => 'required|numeric|min:0',
            'montant_unique'       => 'nullable|numeric|min:0',
            'duree'                => 'required|integer|min:1|max:3',
            'handicape'            => 'required|string',
            'type_handicap'        => 'nullable|string',
            'orphelin'             => 'required|string',
            'type_orphelin'        => 'nullable|string',

            // 🆕 Validation des fichiers
            'facture'              => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'cin_file'             => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        // Convertir les champs numériques vides en null
        $validated['montant_unique'] = $validated['montant_unique'] === '' ? null : $validated['montant_unique'];

        // 🧾 Sauvegarde des fichiers
        if ($request->hasFile('facture_file')) {
            $validated['facture_file'] = $request->file('facture_file')->store('factures', 'public');
        }

        if ($request->hasFile('cin_file')) {
            $validated['cin_file'] = $request->file('cin_file')->store('cins', 'public');
        }

        // Vérification si les inscriptions sont fermées
        Alert::error('Désolé', 'Les inscriptions n\'ont pas encore démarré.');
        return redirect()->back();

        // ✅ Enregistrement en base
        $formulaire = Formulaire::create($validated);

        // Envoi d’un mail de confirmation (si email renseigné)
        if (!empty($validated['email'])) {
            Mail::to($validated['email'])->send(new ConfirmationInscriptionPchare($formulaire));
        }

        Alert::success('Succès', 'Inscription effectuée avec succès.');

        return redirect()->route('formulaire.merci');
    } */


    public function store(Request $request)
    {

        // Définir la période d'ouverture des inscriptions
        $debut = Carbon::create(2025, 11, 10, 8, 0, 0);   // 10 novembre 2025 à 08h00
        $fin   = Carbon::create(2025, 11, 12, 17, 0, 0);  // 12 novembre 2025 à 17h00

        $now = Carbon::now();

        // Vérifier si on est hors période
        if ($now->lt($debut) || $now->gt($fin)) {
            Alert::error('Désolé', 'Les inscriptions ne sont ouvertes que du 10 novembre à 08h00 au 12 novembre à 17h00.');
            return redirect()->back(); // ou une autre route sûre
        }

        $validated = $request->validate([
            'cin'                  => 'required|string|max:17|unique:formulaires,cin',
            'civilite'             => 'required|string|max:5',
            'prenom'               => 'required|string',
            'nom'                  => 'required|string',
            'date_naissance'       => 'required|date',
            'lieu_naissance'       => 'required|string',
            'email'                => 'required|email|unique:formulaires,email',
            'telephone'            => 'required|string',
            'telephone_secondaire' => 'required|string',
            'adresse'              => 'required|string',
            'dernier_diplome'      => 'required|string',
            'nom_etablissement'    => 'required|string',
            'region'               => 'required|string',
            'formation'            => 'required|string',
            'diplome_vise'         => 'required|string',
            'montant_inscription'  => 'required|numeric|min:0',
            'montant_mensualite'   => 'required|numeric|min:0',
            'montant_unique'       => 'nullable|numeric|min:0',
            'duree'                => 'required|integer|min:1|max:3',
            'handicape'            => 'required|string',
            'type_handicap'        => 'nullable|string',
            'orphelin'             => 'required|string',
            'type_orphelin'        => 'nullable|string',
            'facture_file'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:256',
            'cin_file'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:256',
            'diplome'              => 'required|file|mimes:pdf,jpg,jpeg,png|max:256',
            'cv'                   => 'required|file|mimes:pdf,jpg,jpeg,png|max:256',
        ]);

        // Convertir les champs numériques vides en null
        $validated['montant_unique'] = $validated['montant_unique'] === '' ? null : $validated['montant_unique'];

        // Création du formulaire
        $formulaire = Formulaire::create($validated);

        // 📂 Upload du fichier facture
        /*   if ($request->hasFile('facture_file')) {
            $uploadedFile = $request->file('facture_file');
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            $filePath = $uploadedFile->storeAs('pvs', $filename, 'public');

            $formulaire->update([
                'facture_file' => $filePath,
            ]);
        } */
        if ($request->hasFile('facture_file')) {
            $uploadedFile = $request->file('facture_file');

            // Nettoyer le nom du fichier
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = 'Facture_' . time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Dossier cible dans le disque public
            $folder = 'factures'; // Change le nom du dossier si nécessaire

            // Créer le dossier s'il n'existe pas
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs($folder, $filename, 'public');

            // Mettre à jour le modèle
            $formulaire->update([
                'facture_file' => $filePath,
            ]);
        }

        // 📑 Upload du fichier CIN
        /*   if ($request->hasFile('cin_file')) {
            $uploadedFile = $request->file('cin_file');
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            $filePath = $uploadedFile->storeAs('pvs', $filename, 'public');

            $formulaire->update([
                'cin_file' => $filePath,
            ]);
        } */

        if ($request->hasFile('cin_file')) {
            $uploadedFile = $request->file('cin_file');

            // Nettoyer le nom du fichier
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = 'CIN_' . time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Dossier cible dans le disque public
            $folder = 'cins'; // tu peux changer 'cins' par 'pvs' ou 'diplome' etc.

            // Créer le dossier s'il n'existe pas
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs($folder, $filename, 'public');

            // Mettre à jour le modèle
            $formulaire->update([
                'cin_file' => $filePath,
            ]);
        }

        if ($request->hasFile('diplome')) {
            $uploadedFile = $request->file('diplome');

            // Nettoyer le nom du fichier
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = 'DIPLOME_' . time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Dossier cible dans le disque public
            $folder = 'diplomes'; // tu peux changer 'cins' par 'pvs' ou 'diplome' etc.

            // Créer le dossier s'il n'existe pas
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs($folder, $filename, 'public');

            // Mettre à jour le modèle
            $formulaire->update([
                'diplome' => $filePath,
            ]);
        }

        if ($request->hasFile('cv')) {
            $uploadedFile = $request->file('cv');

            // Nettoyer le nom du fichier
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = 'CV_' . time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Dossier cible dans le disque public
            $folder = 'cvs'; // tu peux changer 'cins' par 'pvs' ou 'diplome' etc.

            // Créer le dossier s'il n'existe pas
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs($folder, $filename, 'public');

            // Mettre à jour le modèle
            $formulaire->update([
                'cv' => $filePath,
            ]);
        }


        // 📧 Envoi du mail de confirmation (si email fourni)
        /*  if (!empty($validated['email'])) {
            Mail::to($validated['email'])->send(new ConfirmationInscriptionPchare($formulaire));
        }
 */
        Alert::success('Succès', 'Inscription effectuée avec succès.');
        return redirect()->route('formulaire.merci');
    }


    public function merci()
    {
        return view('formulaire.merci');
    }


    public function index()
    {
        $formulaires = Formulaire::orderBy('created_at', 'desc')->get();
        $labels = [
            'cin' => 'Numéro CIN',
            'civilite' => 'Civilité',
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'date_naissance' => 'Date naissance',
            'lieu_naissance' => 'Lieu naissance',
            /* 'email' => 'Adresse e-mail', */
            'telephone' => 'Téléphone',
            /* 'telephone_secondaire' => 'Téléphone secondaire',
            'adresse' => 'Adresse',
            'dernier_diplome' => 'Dernier diplôme obtenu',
            'nom_etablissement' => 'Établissement', */
            'region' => 'Région',
            'formation' => 'Formation sollicitée',
            /* 'diplome_vise' => 'Diplôme visé',
            'montant_inscription' => 'Montant inscription',
            'montant_mensualite' => 'Montant mensualité',
            'montant_unique' => 'Montant unique', */
            /* 'duree' => 'Durée (en années)',
            'handicape' => 'Situation de handicap',
            'type_handicap' => 'Type de handicap', */
            /* 'orphelin' => 'Orphelin',
            'type_orphelin' => 'Type d’orphelinat', */
            /* 'cin_file' => 'Copie CIN',
            'facture_file' => 'Facture',
            'cv' => 'CV',
            'diplome' => 'Diplôme' */
        ];

        return view('formulaire.index', compact('formulaires', 'labels'));
    }

    public function show($id)
    {
        // Récupérer l'inscription par ID
        $formulaire = Formulaire::findOrFail($id);

        // Vérifier les permissions (facultatif si tu utilises @can dans la vue)
        $this->authorize('formulaire-view');

        // Retourner la vue show avec les données
        return view('formulaire.show', compact('formulaire'));
    }

    public function edit($id)
    {
        // Récupérer l'inscription par ID
        $formulaire = Formulaire::findOrFail($id);

        // Vérifier les permissions (facultatif si tu utilises @can dans la vue)
        $this->authorize('formulaire-edit');

        // Retourner la vue show avec les données
        return view('formulaire.update', compact('formulaire'));
    }

    public function update(Request $request, $id)
    {
        $formulaire = Formulaire::findOrFail($id);

        /* $data = $request->validate([
            'cin' => 'required|string|max:20',
            'civilite' => 'required|string|max:10',
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'region' => 'required|string|max:100',
            'formation' => 'required|string|max:255',
            'statut' => 'required|string|max:50',
            'cin_file' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'facture_file' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:1024',
            'diplome' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
        ]); */

        $data = $request->validate([
            'cin' => 'required|string|max:20',
            'civilite' => 'required|string|max:10',
            'prenom' => 'required|string|max:100',
            'nom' => 'required|string|max:100',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'telephone' => 'required|string|max:20',
            'telephone_secondaire' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'dernier_diplome' => 'nullable|string|max:255',
            'nom_etablissement' => 'nullable|string|max:255',
            'region' => 'required|string|max:100',
            'formation' => 'required|string|max:255',
            'diplome_vise' => 'nullable|string|max:255',
            'montant_inscription' => 'nullable|numeric',
            'montant_mensualite' => 'nullable|numeric',
            'montant_unique' => 'nullable|numeric',
            'duree' => 'nullable|string|max:50',
            'handicape' => 'nullable|string|max:10',
            'type_handicap' => 'nullable|string|max:255',
            'orphelin' => 'nullable|string|max:10',
            'type_orphelin' => 'nullable|string|max:255',
            'statut' => 'required|string|max:50',

            // fichiers
            'cin_file' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'facture_file' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:1024',
            'diplome' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
        ]);


        // Gestion des fichiers uploadés
        $fileDirectories = [
            'cin_file'      => 'uploads/cins',
            'facture_file'  => 'uploads/factures',
            'cv'            => 'uploads/cvs',
            'diplome'       => 'uploads/diplomes',
        ];

        foreach ($fileDirectories as $fileField => $directory) {
            if ($request->hasFile($fileField)) {
                // Supprimer l'ancien fichier s'il existe
                if (!empty($formulaire->$fileField) && Storage::disk('public')->exists($formulaire->$fileField)) {
                    Storage::disk('public')->delete($formulaire->$fileField);
                }

                // Sauvegarder le nouveau fichier dans son dossier spécifique
                $path = $request->file($fileField)->store($directory, 'public');
                $data[$fileField] = $path;
            }
        }

        $formulaire->update($data);

        /* Alert::success('Succès', 'Les informations ont été mises à jour avec succès.'); */

        return redirect()
            ->route('formulaires.edit', $formulaire->id)
            ->with('status', 'Les informations ont été mises à jour avec succès.');
    }


    public function destroy($id)
    {
        $formulaire = Formulaire::findOrFail($id);
        // Vérifier les permissions (facultatif si tu utilises @can dans la vue)
        $this->authorize('formulaire-delete');

        // Supprimer les fichiers associés si ils existent
        foreach (['cin_file', 'facture_file', 'cv', 'diplome'] as $fileField) {
            if (!empty($formulaire->$fileField) && Storage::disk('public')->exists($formulaire->$fileField)) {
                Storage::disk('public')->delete($formulaire->$fileField);
            }
        }

        // Supprimer l'enregistrement
        $formulaire->delete();

        Alert::success('Succès', 'L’inscription a été supprimée avec succès.');

        return redirect()->route('formulaires.index');
    }
}
