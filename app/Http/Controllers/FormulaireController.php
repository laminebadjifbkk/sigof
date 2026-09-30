<?php

namespace App\Http\Controllers;

use App\Exports\ExportPrisenchargeStatut;
use App\Exports\PrisenchargeExport;
use App\Mail\ConfirmationInscriptionPchare;
use App\Models\Formulaire;
use App\Models\HistoriquePriseEnCharge;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Kris\LaravelFormBuilder\Form;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FormulaireController extends Controller
{
    /*  public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(['role:super-admin|Ingenieur|Demandeur|DIOF|ADIOF']);
        $this->middleware("permission:formulaire-view", ["only" => ["index"]]);
    } */
    // Affichage du formulaire
    public function create()
    {
        /* // Définir la période d'ouverture des inscriptions
        $debut = Carbon::create(2025, 11, 10, 8, 0, 0);   // 10 novembre 2025 à 08h00
        $fin   = Carbon::create(2025, 11, 12, 17, 0, 0);  // 12 novembre 2025 à 17h00

        $now = Carbon::now();

        // Vérifier si on est hors période
        if ($now->lt($debut) || $now->gt($fin)) {
            Alert::error('Désolé', 'Les inscriptions ne sont ouvertes que du 10 novembre à 08h00 au 12 novembre à 17h00.');
            return redirect()->back(); // ou une autre route sûre
        } */

        // Sinon afficher le formulaire
        return view('formulaire.create');
    }

    // Enregistrement du formulaire
    public function store(Request $request)
    {
        // --- Période d'ouverture des candidatures ---
        $dateOuverture = Carbon::create(2026, 9, 28, 8, 0, 0, 'Africa/Dakar');
        $dateFermeture = Carbon::create(2026, 10, 3, 17, 0, 0, 'Africa/Dakar');
        $maintenant    = Carbon::now('Africa/Dakar');

        if ($maintenant->lt($dateOuverture)) {
            return redirect()->back()->with('error', 'Les candidatures ne sont pas encore ouvertes.');
        }

        if ($maintenant->gt($dateFermeture)) {
            return redirect()->back()->with('error', 'Les candidatures sont désormais fermées.');
        }

        // --- Détection d'une inscription existante (CIN + e-mail obligatoirement identiques) ---
        $existant = null;
        $parCin   = Formulaire::where('cin', $request->input('cin'))->first();
        $parEmail = Formulaire::where('email', $request->input('email'))->first();

        if ($parCin || $parEmail) {
            if ($parCin && $parEmail && $parCin->id === $parEmail->id) {
                $existant = $parCin; // même CIN et même e-mail => mise à jour
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Une inscription existe déjà. Pour la mettre à jour, vous devez utiliser exactement le même CIN et la même adresse e-mail.');
            }
        }

        // --- Validation (unicité ignorée pour l'enregistrement existant) ---
        $validated = $request->validate([
            'cin'                  => ['required', 'string', 'max:14', Rule::unique('formulaires', 'cin')->ignore($existant?->id)],
            /* 'cin' => [
                'required',
                'string',
                'max:14',
                Rule::unique('formulaires', 'cin')
                    ->ignore($existant?->id)
                    ->whereNull('deleted_at'),
            ], */
            'civilite'             => 'required|string|max:5',
            'prenom'               => 'required|string',
            'nom'                  => 'required|string',
            'date_naissance'       => [
                'required',
                'date',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    $age = Carbon::parse($value)->age;
                    if ($age < 15 || $age > 35) {
                        $fail('La prise en charge est ouverte aux candidats âgés de 15 à 35 ans.');
                    }
                },
            ],
            'lieu_naissance'       => 'required|string',
            'email'                => ['required', 'email', Rule::unique('formulaires', 'email')->ignore($existant?->id)],
            /* 'email' => [
                'required',
                'email',
                Rule::unique('formulaires', 'email')
                    ->ignore($existant?->id)
                    ->whereNull('deleted_at'),
            ], */
            'telephone'            => 'required|string|size:9',
            'telephone_secondaire' => 'required|string|size:9',
            'adresse'              => 'required|string',
            'dernier_diplome'      => 'required|string',
            'nom_etablissement'    => 'required|string',
            'autre_2'              => 'nullable|string|max:20',
            'adresse_etablessement' => 'required|string|max:20',
            'telephone_etablissement' => 'required|string|size:9',
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
            'facture_file'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'cin_file'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'diplome'              => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
            'cv'                   => 'required|file|mimes:pdf,jpg,jpeg,png|max:1024',
        ]);

        // champ => [préfixe du nom, dossier]
        $fichiers = [
            'facture_file' => ['Facture',  'factures'],
            'cin_file'     => ['CIN',      'cins'],
            'diplome'      => ['DIPLOME',  'diplomes'],
            'cv'           => ['CV',       'cvs'],
        ];

        $donnees          = Arr::except($validated, array_keys($fichiers));
        $donnees['montant_unique'] = $donnees['montant_unique'] ?? null;
        $donnees['annee_scolaire']  = '2026-2027';
        $donnees['statut']  = 'Nouvelle';

        $nouveauxChemins  = [];
        $anciensChemins   = [];

        try {
            DB::beginTransaction();

            // Upload des nouveaux fichiers
            foreach ($fichiers as $champ => [$prefixe, $dossier]) {
                $nouveauxChemins[$champ] = $this->enregistrerFichier($request, $champ, $prefixe, $dossier);
            }

            if ($existant) {
                // On mémorise les anciens fichiers pour les supprimer après le succès
                foreach (array_keys($fichiers) as $champ) {
                    if ($existant->{$champ}) {
                        $anciensChemins[] = $existant->{$champ};
                    }
                }

                $existant->update($donnees + $nouveauxChemins);
                $formulaire = $existant;
            } else {
                $formulaire = Formulaire::create($donnees + $nouveauxChemins);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            // On nettoie les fichiers fraîchement uploadés
            Storage::disk('public')->delete(array_filter($nouveauxChemins));

            report($e);
            Alert::error('Erreur', 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.');
            return redirect()->back()->withInput();
        }

        // Suppression des anciens fichiers, uniquement après réussite de la mise à jour
        if (!empty($anciensChemins)) {
            Storage::disk('public')->delete($anciensChemins);
        }

        session()->put('formulaire_confirme', $formulaire->id);

        Alert::success(
            'Succès',
            $existant ? 'Votre inscription a été mise à jour avec succès.' : 'Inscription effectuée avec succès.'
        );

        return redirect()->route('formulaire.confirmation', $formulaire);
    }

    /**
     * Enregistre un fichier uploadé et retourne son chemin (ou null s'il n'y a pas de fichier).
     */
    private function enregistrerFichier(Request $request, string $champ, string $prefixe, string $dossier): ?string
    {
        if (!$request->hasFile($champ)) {
            return null;
        }

        $fichier = $request->file($champ);

        $nom = preg_replace('/[^A-Za-z0-9]/', '', pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME));
        $nomFinal = $prefixe . '_' . time() . '_' . $nom . '.' . $fichier->getClientOriginalExtension();

        // storeAs crée le dossier automatiquement s'il n'existe pas
        return $fichier->storeAs($dossier, $nomFinal, 'public');
    }

    /*  public function merci()
    {
        return view('formulaire.merci');
    } */


    public function confirmation(Formulaire $formulaire)
    {
        abort_unless(session('formulaire_confirme') === $formulaire->id, 403);
        return view('formulaire.confirmation', compact('formulaire'));
    }


// Résumé des demandes par année scolaire
public function index()
{
    // Une seule requête SQL : effectif par année scolaire et par statut
    $lignes = Formulaire::select('annee_scolaire', 'statut', DB::raw('COUNT(*) as total'))
        ->groupBy('annee_scolaire', 'statut')
        ->get();

    $total            = $lignes->sum('total');
    $totalFormulaires = number_format($total, 0, ',', ' ');

    $annees = $lignes
        ->groupBy(fn($l) => $l->annee_scolaire ?? 'Non définie')
        ->map(fn($groupe) => [
            'total'   => $groupe->sum('total'),
            'statuts' => $groupe
                ->groupBy(fn($l) => $l->statut ?? 'Non défini')
                ->map(fn($g) => $g->sum('total'))
                ->sortDesc(),
        ])
        ->sortKeysDesc();   // 2026-2027 avant 2025-2026

    // « Non définie » toujours en dernier
    if ($nonDefinie = $annees->pull('Non définie')) {
        $annees->put('Non définie', $nonDefinie);
    }

    return view('formulaire.index', compact('annees', 'totalFormulaires'));
}


// Contenu de l'ancienne vue index, limité à une année scolaire
public function showAnnee($annee)
{
    $valeur = ($annee === 'Non définie') ? null : $annee;

    // Seules les colonnes utiles à la vue sont chargées
    $formulaires = Formulaire::where('annee_scolaire', $valeur)
        ->get(['id', 'region', 'statut']);

    abort_if($formulaires->isEmpty(), 404);

    $totalFormulaires = number_format($formulaires->count(), 0, ',', ' ');

    // Regroupement par région
    $groupes = $formulaires->groupBy(fn($item) => $item->region ?? 'Aucune région');

    // Regroupement par statut + pourcentages
    $grouperStatut = $formulaires->groupBy(fn($item) => $item->statut ?? 'Non défini');

    $statutPourcentages = [];
    foreach ($grouperStatut as $statut => $items) {
        $statutPourcentages[$statut] = [
            'count'   => $items->count(),
            'percent' => round(($items->count() / max(1, $formulaires->count())) * 100, 2),
        ];
    }

    return view('formulaire.annee', compact(
        'annee',
        'formulaires',
        'totalFormulaires',
        'groupes',
        'grouperStatut',
        'statutPourcentages'
    ));
}
    public function show($id)
    {
        // Récupérer l'inscription par ID
        $formulaire = Formulaire::findOrFail($id);

        // Vérifier les permissions (facultatif si tu utilises @can dans la vue)
        $this->authorize('formulaire-view');

        $labels = [
            'cin' => 'Numéro CIN',
            'civilite' => 'Civilité',
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'date_naissance' => 'Date naissance',
            'lieu_naissance' => 'Lieu naissance',
            'email' => 'Adresse e-mail',
            'telephone' => 'Téléphone',
            'telephone_secondaire' => 'Téléphone secondaire',
            'adresse' => 'Adresse',
            'dernier_diplome' => 'Dernier diplôme obtenu',
            'nom_etablissement' => 'Établissement',
            'autre_2' => 'Sigle',
            'region' => 'Région',
            'formation' => 'Formation sollicitée',
            'diplome_vise' => 'Diplôme visé',
            'montant_inscription' => 'Montant inscription',
            'montant_mensualite' => 'Montant mensualité',
            'montant_unique' => 'Montant unique',
            'duree' => 'Durée (en années)',
            'handicape' => 'Situation de handicap',
            'type_handicap' => 'Type de handicap',
            'orphelin' => 'Orphelin',
            'type_orphelin' => 'Type d’orphelinat',
            'cin_file' => 'Copie CIN',
            'facture_file' => 'Facture',
            'cv' => 'CV',
            'diplome' => 'Diplôme',
            /* 'statut' => 'Statut', */
        ];

        $fileFields = ['cin_file', 'facture_file', 'cv', 'diplome'];

        // Retourner la vue show avec les données
        return view('formulaire.show', compact('formulaire', 'labels', 'fileFields'));
    }

    public function edit($id)
    {
        // Récupérer l'inscription par ID
        $formulaire = Formulaire::findOrFail($id);

        // Vérifier les permissions (facultatif si tu utilises @can dans la vue)
        $this->authorize('formulaire-edit');

        $labels = [
            'cin' => 'Numéro CIN',
            'civilite' => 'Civilité',
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'date_naissance' => 'Date naissance',
            'lieu_naissance' => 'Lieu naissance',
            'email' => 'Adresse e-mail',
            'telephone' => 'Téléphone',
            'telephone_secondaire' => 'Téléphone secondaire',
            'adresse' => 'Adresse',
            'dernier_diplome' => 'Dernier diplôme obtenu',
            /* 'nom_etablissement' => 'Établissement',
            'autre_2' => 'Sigle', */
            'region' => 'Région',
            'formation' => 'Formation sollicitée',
            'diplome_vise' => 'Diplôme visé',
            'montant_inscription' => 'Montant inscription',
            'montant_mensualite' => 'Montant mensualité',
            'montant_unique' => 'Montant unique',
            'duree' => 'Durée (en années)',
            'handicape' => 'Situation de handicap',
            'type_handicap' => 'Type de handicap',
            'orphelin' => 'Orphelin',
            'type_orphelin' => 'Type d’orphelinat',
            'cin_file' => 'Copie CIN',
            'facture_file' => 'Facture',
            'cv' => 'CV',
            'diplome' => 'Diplôme',
            /* 'autre_1' => 'Type apprenant', */
            /* 'statut' => 'Statut', */
        ];

        $fileFields = ['cin_file', 'facture_file', 'cv', 'diplome'];

        // Retourner la vue show avec les données
        return view('formulaire.update', compact('formulaire', 'labels', 'fileFields'));
    }

    public function update(Request $request, $id)
    {
        $formulaire = Formulaire::findOrFail($id);

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
            /* 'statut' => 'required|string|max:50', */ // nouveaux champs
            'responsable_etablieement' => 'nullable|string|max:255',
            'adresse_etablessement' => 'nullable|string|max:255',
            'telephone_etablissement' => 'nullable|string|max:30',
            'annee_scolaire' => 'required',
            'regex:/^\d{4}-\d{4}$/',
            'montant_onfp' => 'nullable|numeric',
            'statut_certificat' => 'nullable|string|max:50',
            'autre_1' => 'nullable|string|max:100',
            'autre_2' => 'nullable|string|max:20',

            // fichiers
            'cin_file' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'facture_file' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'cv' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'diplome' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
            'certificat_file' => 'nullable|file|mimes:pdf,jpg,png|max:1024',
        ]);


        // Gestion des fichiers uploadés
        $fileDirectories = [
            'cin_file'      => 'uploads/cins',
            'facture_file'  => 'uploads/factures',
            'cv'            => 'uploads/cvs',
            'diplome'       => 'uploads/diplomes',
            'certificat_file'       => 'uploads/certificats',
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


    public function generateReport(Request $request)
    {
        $this->validate($request, [
            'cin'       => 'nullable|string',
            'nom'      => 'nullable|string',
            'prenom' => 'nullable|string',
            'telephone' => 'nullable|string',
            'email'     => 'nullable|email',
            'lieu_naissance'     => 'nullable|string',
        ]);

        if ($request?->cin == null && $request->prenom == null && $request->telephone == null && $request->nom == null && $request->email == null && $request->lieu_naissance == null) {
            Alert::warning('Attention', 'Veuillez renseigner au moins un champ pour effectuer une recherche.');
            return redirect()->back();
        }

        $formulaires = Formulaire::where('prenom', 'LIKE', "%{$request?->prenom}%")
            ->where('nom', 'LIKE', "%{$request?->nom}%")
            ->where('cin', 'LIKE', "%{$request?->cin}%")
            ->where('telephone', 'LIKE', "%{$request?->telephone}%")
            ->where('email', 'LIKE', "%{$request?->email}%")
            ->where('lieu_naissance', 'LIKE', "%{$request?->lieu_naissance}%")
            ->distinct()
            ->get();

        $totalFormulaires = number_format($formulaires?->count(), 0, ',', ' ');

        // Regrouper par statut (y compris les null)
        $groupes = $formulaires->groupBy(function ($item) {
            return $item->region ?? 'Aucune région';
        });

        $labels = [
            'cin' => 'CIN',
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

        /* return view('formulaire.index', compact(
            'formulaires',
            'totalFormulaires',
            'groupes',
            'labels'
        )); */
        return view('formulaire.resultats', compact(
            'formulaires',
            'totalFormulaires',
            'groupes',
            'labels'
        ));
    }

    public function exporterPrisenchargeExcel($statut, $region)
    {
        $fileName = "Prises en charge - {$region} - {$statut}.xlsx";

        return Excel::download(new PrisenchargeExport($statut, $region), $fileName);
    }

    public function PrisenchargeExcel($statut)
    {
        /* $fileName = "Prises en charge - {$statut}.xlsx";

        return Excel::download(new ExportPrisenchargeStatut($statut), $fileName); */

        // === 1. Générer l'Excel ===
        /* $fileName = "Prises en charge - {$statut}.xlsx";
        $excelPath = storage_path("app/temp/{$fileName}");
        Excel::store(new ExportPrisenchargeStatut($statut), "temp/{$fileName}"); */

        // === 2. Créer un dossier temporaire pour les fichiers ===
        $tempPath = storage_path('app/temp/prisencharge_' . time());
        if (! is_dir($tempPath)) {
            mkdir($tempPath, 0777, true);
        }

        $fileName = "Prises en charge - {$statut}.xlsx";
        Excel::store(new ExportPrisenchargeStatut($statut), "temp/{$fileName}", 'local');

        $excelPath = storage_path("app/temp/{$fileName}");
        if (file_exists($excelPath)) {
            copy($excelPath, $tempPath . '/' . $fileName);
        } else {
            \Log::error("Excel non trouvé : " . $excelPath);
        }

        // Copier l’Excel dans le dossier
        copy($excelPath, $tempPath . '/' . $fileName);

        // === 3. Récupérer les dossiers concernés par lots de 100 ===
        Formulaire::where('statut', $statut)
            ->chunk(25, function ($prises) use ($tempPath) {
                foreach ($prises as $prise) {
                    // Nom du dossier par dossier
                    $dossierFolder = $tempPath . '/' . $this->sanitizeFileName(
                        ($prise?->prenom ?? '') . '_' . $prise?->nom . '_' . $prise?->id
                    );

                    if (! is_dir($dossierFolder)) {
                        mkdir($dossierFolder, 0777, true);
                    }

                    // === Fichiers spécifiques ===
                    $attachments = [
                        'cin_file'              => 'CIN',
                        'facture_file'          => 'Facture',
                        'cv'                    => 'CV',
                        'diplome'               => 'Diplome',
                        'certificat_file'       => 'Certificat',
                    ];

                    foreach ($attachments as $field => $prefix) {
                        $file = $prise->$field;
                        if (! $file || ! is_string($file)) {
                            continue;
                        }

                        $sourcePath = storage_path('app/public/' . $file);
                        if (! file_exists($sourcePath)) {
                            continue;
                        }

                        $filename = $this->sanitizeFileName($prefix . '_' . $prise->id)
                            . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);

                        $destination = $dossierFolder . '/' . $filename;
                        @copy($sourcePath, $destination);
                    }
                }
            });

        // === 4. Créer le ZIP ===
        $zipPath = storage_path("app/temp/Prises_en_charge_{$statut}.zip");
        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (! $file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempPath) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        }

        // === 5. Télécharger le ZIP ===
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    protected function sanitizeFileName(string $name, int $maxLength = 150): string
    {
        // Remplacer les espaces et ponctuation par des tirets
        $clean = Str::slug($name, '-');

        // Fallback si le slug est vide (par ex. seulement des caractères non-latin)
        if ($clean === '' || $clean === null) {
            $clean = 'fichier';
        }

        // Limiter la longueur pour éviter les soucis OS/ZIP
        if (Str::length($clean) > $maxLength) {
            $clean = Str::limit($clean, $maxLength, '');
        }

        // Retirer éventuels tirets en double
        $clean = preg_replace('/-+/', '-', $clean);

        return $clean;
    }

    public function filtrerPrisenchargeParStatut($statut, $region)
    {

        /* $formulaires = Formulaire::where('statut', $statut)->where('region', $region)->get(); */

        $formulaires = collect();

        Formulaire::where('statut', $statut)
            ->where('region', $region)
            ->orderBy('id', 'desc')
            ->chunk(300, function ($batch) use (&$formulaires) {
                $formulaires = $formulaires->merge($batch);
            });

        $formulair      = $formulaires->count();
        $totalFormulaires = number_format($formulair, 0, ',', ' ');

        /*  // Regrouper par statut (y compris les null)
        $groupes = $formulaires->groupBy(function ($item) {
            return $item->statut ?? 'Aucun statut';
        }); */

        $labels = [
            'cin' => 'CIN',
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

        return view('formulaire.prisencharge-par-statut', compact('formulaires', 'statut', 'totalFormulaires', 'labels', 'region'));
    }

    public function filtrerPrisenchargeParStatutDiplome($statut, $region, $diplome)
    {
        /* $formulaires = Formulaire::where('statut', $statut)->where('region', $region)->where('diplome_vise', $diplome)->get(); */
        $formulaires = collect();

        Formulaire::where('statut', $statut)
            ->where('region', $region)
            ->where('diplome_vise', $diplome)
            ->orderBy('id', 'desc')
            ->chunk(300, function ($batch) use (&$formulaires) {
                $formulaires = $formulaires->merge($batch);
            });

        $formulair      = $formulaires->count();
        $totalFormulaires = number_format($formulair, 0, ',', ' ');

        /*  // Regrouper par statut (y compris les null)
        $groupes = $formulaires->groupBy(function ($item) {
            return $item->statut ?? 'Aucun statut';
        }); */

        $labels = [
            /* 'cin' => 'CIN',
            'civilite' => 'Civilité', */
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'date_naissance' => 'Date naissance',
            'lieu_naissance' => 'Lieu naissance',
            /* 'email' => 'Adresse e-mail', */
            /* 'telephone' => 'Téléphone', */
            /* 'telephone_secondaire' => 'Téléphone secondaire',
            'adresse' => 'Adresse',
            'dernier_diplome' => 'Dernier diplôme obtenu',
            'nom_etablissement' => 'Établissement', */
            /* 'region' => 'Région', */
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

        return view('formulaire.prisencharge-par-statut-diplome', compact('formulaires', 'statut', 'totalFormulaires', 'labels', 'region', 'diplome'));
    }

    public function showregion($region)
    {
        // Vérifier les permissions
        $this->authorize('formulaire-view');


        $formulaireCount      = Formulaire::where('region', $region)->count();
        $formulaireCount = number_format($formulaireCount, 0, ',', ' ');

        // Récupérer les formulaires de la région
        /* $formulaires = Formulaire::where('region', $region)->get(); */
        /* $formulaires = Formulaire::where('region', $region)
            ->get(); */

        $formulaires = collect();

        Formulaire::where('region', $region)
            ->orderBy('id', 'desc')
            ->chunk(300, function ($batch) use (&$formulaires) {
                $formulaires = $formulaires->merge($batch);
            });

        $formulair      = $formulaires->count();
        $totalFormulaires = number_format($formulair, 0, ',', ' ');

        /* $formulaires = Formulaire::orderBy('created_at', 'desc')->get(); */
        $labels = [
            'cin' => 'CIN',
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
            /* 'region' => 'Région', */
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

        // Regrouper par statut (y compris les null)
        /*  $groupes = $formulaires->groupBy(function ($item) {
            return $item->statut ?? 'Aucun statut';
        }); */

        // Regrouper par diplomes (y compris les null)
        $groupes = $formulaires->groupBy(function ($item) {
            return $item->diplome_vise ?? 'Aucun diplôme visé';
        });
        // Regrouper par diplomes (y compris les null)
        $grouperegions = $formulaires->groupBy(function ($item) {
            return $item->statut ?? 'Aucun';
        });

        // Retourner la vue avec les résultats
        return view('formulaire.showregion', compact('formulaires', 'region', 'labels', 'totalFormulaires', 'groupes', 'formulaireCount', 'grouperegions'));
    }

    public function showregiondiplome($region, $diplome_vise)
    {
        // Vérifier les permissions
        $this->authorize('formulaire-view');

        $formulaireCount = Formulaire::where('region', $region)
            ->where('diplome_vise', $diplome_vise)
            ->count();

        $formulaireCount = number_format($formulaireCount, 0, ',', ' ');

        // Récupérer les formulaires de la région
        /* $formulaires = Formulaire::where('region', $region)->get(); */
        /* $formulaires = Formulaire::where('region', $region)
            ->where('diplome_vise', $diplome_vise)
            ->limit(3000) // ou ->take(2000)
            ->get(); */

        $formulaires = Formulaire::select('id', 'cin', 'prenom', 'nom', 'region', 'date_naissance', 'lieu_naissance', 'formation', 'statut')
            ->where('region', $region)
            ->where('diplome_vise', $diplome_vise)
            /* ->orderBy('id', 'desc') */
            ->lazy()
            ->collect();

        $formulair      = $formulaires->count();
        $totalFormulaires = number_format($formulair, 0, ',', ' ');

        /* $formulaires = Formulaire::orderBy('created_at', 'desc')->get(); */
        $labels = [
            /* 'cin' => 'CIN',
            'civilite' => 'Civilité', */
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'date_naissance' => 'Date nais.',
            'lieu_naissance' => 'Lieu naissance',
            /* 'email' => 'Adresse e-mail', */
            /* 'telephone' => 'Téléphone', */
            /* 'telephone_secondaire' => 'Téléphone secondaire',
            'adresse' => 'Adresse',
            'dernier_diplome' => 'Dernier diplôme obtenu',
            'nom_etablissement' => 'Établissement', */
            /* 'region' => 'Région', */
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

        // Regrouper par statut (y compris les null)
        $groupes = $formulaires->groupBy(function ($item) {
            return $item->statut ?? 'Aucun statut';
        });

        // Retourner la vue avec les résultats
        return view('formulaire.showregiondiplome', compact('formulaires', 'region', 'labels', 'totalFormulaires', 'groupes', 'formulaireCount', 'diplome_vise'));
    }

    public function validationPriseEnCharge(Request $request, $id)
    {
        $formulaire = Formulaire::findOrFail($id);

        $data = $request->validate([
            'statut' => 'required|string|max:50',
            'motif'  => 'required_unless:statut,Conforme,liste attente,Sélectionné,Validée|string|max:500',
        ]);

        $formulaire->update($data);

        HistoriquePriseEnCharge::create([
            'formulaire_id' => $formulaire->id,
            'statut' => $formulaire->statut,
            'motif' => $request->motif ?? null,
            'user_id' => auth()->id(),
        ]);


        return redirect()
            ->back()
            ->with('status', 'Le statut de la prise en charge a été mis à jour avec succès.');
    }


    public function validationsHistotiquepc(Request $request)
    {
        $formulaire = Formulaire::with(['historiques' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($request->input('id'));

        return view("formulaire.historiquepc", compact('formulaire'));
    }

    public function showByStatut($statut)
    {
        // Si le statut est "Non défini"
        $statutValue = ($statut === 'Non défini') ? null : $statut;

        // Récupérer les formulaires du statut
        $formulaires = Formulaire::where('statut', $statutValue)->get();
        // Nombre
        $total = $formulaires->count();

        $totalFormulaires = number_format($total, 0, ',', ' ');

        $labels = [
            /* 'cin' => 'CIN',
            'civilite' => 'Civilité', */
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'date_naissance' => 'Date nais.',
            'lieu_naissance' => 'Lieu nais.',
            /* 'email' => 'Adresse e-mail', */
            'telephone' => 'Téléphone',
            /* 'telephone_secondaire' => 'Téléphone secondaire',
            'adresse' => 'Adresse',
            'dernier_diplome' => 'Dernier diplôme obtenu',
            'nom_etablissement' => 'Établissement', */
            'region' => 'Région',
            'autre_2' => 'Etablissement',
            /* 'formation' => 'Formation sollicitée', */
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

        return view('formulaire.showstatut', compact('formulaires', 'statut', 'total', 'totalFormulaires', 'labels'));
    }


    public function exportercontratlettrePDF(Request $request, $statut)
    {
        try {
            /* if ($statut, ['Sélectionné', 'liste attente']) {
                Alert::error('Attention', 'Impossible de télécharger les lettres : statut invalide.');
                return redirect()->back();
            } */

            // Récupération
            $formulaires = Formulaire::where('statut', $statut)->get();

            $dompdf  = new Dompdf();
            $options = $dompdf->getOptions();
            $dompdf->setOptions($options);

            $dompdf->loadHtml(view(
                'formulaire.contrats-lettres-pdf',
                compact(
                    'formulaires',
                    'statut'
                )
            ));


            // (Optional) Setup the paper size and orientation (portrait ou landscape)
            $dompdf->setPaper('Letter', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            /* $name = 'Lettres agrément opérateurs, ' . $commissionagrement->commission . '.pdf'; */
            $name = 'Lettres_agrement_operateurs_' . $statut . '.pdf';

            // Optionnel : remplacer les caractères accentués
            $name = str_replace(
                [' ', 'é', 'è', 'ê', 'à', 'ç', ','],
                ['_', 'e', 'e', 'e', 'a', 'c', ''],
                $name
            );

            // Pour forcer le téléchargement
            /* $dompdf->stream($name, ['Attachment' => true]); */

            // Output the generated PDF to Browser
            $dompdf->stream($name, ['Attachment' => false]);
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors de la génération du PDF.');
            return redirect()->back();
        }
    }

    public function exporterlalettrePDF($id)
    {
        try {
            // Récupérer le formulaire par ID
            $formulaire = Formulaire::findOrFail($id);

            // Logique pour déterminer le titre du responsable
            $responsable = $formulaire->responsable_etablieement;
            if (Str::contains($responsable, 'général')) {
                $titre = 'Le Directeur général';
            } elseif (Str::contains($responsable, 'Directeur')) {
                $titre = 'Le Directeur';
            } elseif (Str::contains($responsable, 'Directrice')) {
                $titre = 'La Directrice';
            } elseif (Str::contains($responsable, 'Doyen')) {
                $titre = 'Le Doyen';
            } elseif (Str::contains($responsable, 'Doyenne')) {
                $titre = 'La Doyenne';
            } elseif (Str::contains($responsable, 'Proviseur')) {
                $titre = 'Le Proviseur';
            } else {
                $titre = 'Le Responsable';
            }

            // Vérifier le statut
            /* if ($formulaire->statut, ['Sélectionné', 'liste attente']) {
                Alert::error('Attention', 'Impossible de télécharger : statut invalide.');
                return redirect()->back();
            } */

            // Préparer les données pour la vue PDF
            $options = new Options();
            $options->set('isPhpEnabled', true);              // ⭐ OBLIGATOIRE
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $dompdf->loadHtml(view(
                'formulaire.lettre-pdf',
                compact('formulaire', 'titre')
            ));

            // Format du PDF
            $dompdf->setPaper('Letter', 'portrait');
            $dompdf->render();

            // Nom du fichier
            $name = 'Lettre_' . $formulaire->prenom . '_' . $formulaire->nom . '.pdf';
            $name = str_replace(
                [' ', 'é', 'è', 'ê', 'à', 'ç', ','],
                ['_', 'e', 'e', 'e', 'a', 'c', ''],
                $name
            );

            // Mettre à jour le modèle
            $formulaire->update([
                'statut_certificat' => 'Téléchargé',
                'update_by' => Auth::user()->id,
            ]);

            // Stream vers le navigateur
            return $dompdf->stream($name, ['Attachment' => true]);
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors de la génération du PDF.');
            return redirect()->back();
        }
    }

    public function exporterlecontratPDF($id)
    {
        try {
            // Récupérer le formulaire par ID
            $formulaire = Formulaire::findOrFail($id);

            $nomEtablissement = $formulaire?->nom_etablissement ?? '';
            $nomEtablissement = $formulaire?->nom_etablissement ?? '';
            // Supprimer l', à , au uniquement en début de chaîne
            $nomEtablissement = preg_replace("/^(l'|à |au )/i", '', $nomEtablissement);

            $autre2 = $formulaire?->autre_2 ?? '';
            $autre2 = str_replace(["l'", 'à ', 'au '], '', $autre2);

            // Logique pour déterminer le titre du responsable
            $responsable = $formulaire->responsable_etablieement;
            if (Str::contains($responsable, 'général')) {
                $titre = 'Le Directeur général';
            } elseif (Str::contains($responsable, 'Directeur')) {
                $titre = 'Le Directeur';
            } elseif (Str::contains($responsable, 'Directrice')) {
                $titre = 'La Directrice';
            } elseif (Str::contains($responsable, 'Doyen')) {
                $titre = 'Le Doyen';
            } elseif (Str::contains($responsable, 'Doyenne')) {
                $titre = 'La Doyenne';
            } elseif (Str::contains($responsable, 'Proviseur')) {
                $titre = 'Le Proviseur';
            } else {
                $titre = 'Le Responsable';
            }

            // Vérifier le statut
            /*  if ($formulaire->statut, ['Sélectionné', 'liste attente']) {
                Alert::error('Attention', 'Impossible de télécharger : statut invalide.');
                return redirect()->back();
            } */

            // Préparer les données pour la vue PDF
            $options = new Options();
            $options->set('isPhpEnabled', true);              // ⭐ OBLIGATOIRE
            $options->set('isHtml5ParserEnabled', true);
            $dompdf = new Dompdf($options);

            $dompdf->loadHtml(view(
                'formulaire.contrat-pdf',
                compact('formulaire', 'titre', 'nomEtablissement', 'autre2')
            ));

            // Format du PDF
            $dompdf->setPaper('Letter', 'portrait');
            $dompdf->render();

            // Nom du fichier
            $name = 'Contrat_' . $formulaire->prenom . '_' . $formulaire->nom . '.pdf';
            $name = str_replace(
                [' ', 'é', 'è', 'ê', 'à', 'ç', ','],
                ['_', 'e', 'e', 'e', 'a', 'c', ''],
                $name
            );

            // Mettre à jour le modèle
            $formulaire->update([
                'statut_certificat' => 'Téléchargé',
                'update_by' => Auth::user()->id,
            ]);

            // Stream vers le navigateur
            return $dompdf->stream($name, ['Attachment' => true]);
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors de la génération du PDF.');
            return redirect()->back();
        }
    }

    public function editCertificat($id)
    {
        $formulaire = Formulaire::findOrFail($id);
        return view('formulaire.certificat', compact('formulaire'));
    }

    public function updateCertificat(Request $request, $id)
    {
        $formulaire = Formulaire::findOrFail($id);
        // Validation
        $request->validate([
            'certificat_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // 📂 Upload du certificat
        ]);

        if ($request->hasFile('certificat_file')) {
            $uploadedFile = $request->file('certificat_file');

            // 🔹 Supprimer l'ancien fichier s'il existe
            if ($formulaire->certificat_file && Storage::disk('public')->exists($formulaire->certificat_file)) {
                Storage::disk('public')->delete($formulaire->certificat_file);
            }
            // Nettoyer le nom du fichier
            $filename = preg_replace("/[^A-Za-z0-9]/", '', pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = 'Certificat_' . time() . '_' . str_replace(' ', '-', $filename) . '.' . $uploadedFile->getClientOriginalExtension();

            // Dossier cible dans le disque public
            $folder = 'certificats'; // 👉 dossier spécifique pour les certificats

            // Créer le dossier s'il n'existe pas
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            // Stocker le fichier
            $filePath = $uploadedFile->storeAs($folder, $filename, 'public');

            // Mettre à jour le modèle
            $formulaire->update([
                'certificat_file' => $filePath,
                'statut_certificat' => 'Nouveau',
                'users_id' => Auth::user()->id,
            ]);
        }


        return redirect()->route('formulaires.certificat.edit', $formulaire->id)
            ->with('status', 'Certificat d’inscription téléversé avec succès.');
    }
}
