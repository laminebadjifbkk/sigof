<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatureRequest;
use App\Models\Candidature;
use App\Models\LanguesSpecialisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CandidatureController extends Controller
{
    public function create()
    {
        $languesSpecialisations = LanguesSpecialisation::orderBy('nom')->get();
        return view('candidatures.inscription', compact('languesSpecialisations'));
    }

    public function index()
    {
        $candidatures = Candidature::whereHas('user', function ($query) {
            $query->whereNotNull('firstname');
        })
            ->whereHas('langueSpecialisation')
            ->latest()
            ->limit(100)
            ->get();

        return view('candidatures.index', compact('candidatures'));
    }

    public function store(StoreCandidatureRequest $request)
    {
        /* return redirect()->back()
            ->with('error', 'Les candidatures ne sont pas encore ouvertes.'); */

        /* N'oublions pas d'ajouter le role JOJ ou YLP */

        $validated = $request->validated();

        $langue = LanguesSpecialisation::where('code', $validated['langue_specialisation'])->firstOrFail();

        // Vérifie qu'il reste des postes disponibles pour cette langue
        /* if ($langue->candidatures()->count() >= $langue->postes_disponibles) {
            return back()
                ->withInput()
                ->withErrors(['langue_specialisation' => 'Il n\'y a plus de postes disponibles pour cette langue.']);
        } */

        $candidature = DB::transaction(function () use ($request, $validated, $langue) {
            $user = User::create([
                'uuid'           => (string) Str::uuid(),
                'firstname'      => $validated['prenom'],
                'name'           => $validated['nom'],
                'email'          => $validated['email'],
                'telephone'      => $validated['telephone'],
                'date_naissance' => $validated['date_naissance'],
                'password'       => Hash::make(Str::random(16)), // mot de passe temporaire
            ]);

            // Attribution du rôle
            $role = Role::where('name', 'YLP')->first();

            if ($role) {
                $user->assignRole($role);
            }

            $piecePath = $this->storeUploadedFile($request->file('piece_identite'), 'candidatures/pieces_identite');
            $diplomePath = $this->storeUploadedFile($request->file('diplome_fichier'), 'candidatures/diplomes');
            $cvPath = $this->storeUploadedFile($request->file('cv'), 'candidatures/cv');
            $certifPath = $request->hasFile('certification_fichier')
                ? $this->storeUploadedFile($request->file('certification_fichier'), 'candidatures/certifications')
                : null;


            return Candidature::create([
                'users_id'                   => $user->id,
                'langue_specialisation_id'   => $langue->id,
                'certification_obtenue'      => $validated['certification'] ?? null,
                'diplome'                     => $validated['diplome'],
                'langue_maternelle'           => $validated['langue_maternelle'],
                'niveau_francais'             => $validated['niveau_francais'],
                'langue_vivante_2'            => $validated['langue_vivante_2'] ?? null,
                'disponible_debut'            => $validated['disponible_debut'],
                'disponible_fin'              => $validated['disponible_fin'],
                'zone'                         => $validated['zone'],
                'delegation_souhaitee'        => $validated['delegation_souhaitee'] ?? null,
                'piece_identite_path'         => $piecePath,
                'diplome_fichier_path'        => $diplomePath,
                'certification_fichier_path'  => $certifPath,
                'cv_path'                      => $cvPath,
                'attestation'                  => true,
                'statut'                       => 'en_attente',
            ]);
        });

        /* Ceci est pour protéger les url avec les id part 1/2 */
        /* session(['last_candidature_id' => $candidature->id]); */
        return redirect()
            ->route('inscription.confirmation', $candidature)
            ->with('success', 'Votre candidature a bien été envoyée.');
    }

    /**
     * Génère un nom de fichier lisible et unique à partir du nom original.
     * Format : nom-original-slugifie-{uuid-court}.extension
     */
    private function storeUploadedFile(UploadedFile $file, string $directory): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension    = $file->getClientOriginalExtension();

        $slug   = Str::slug($originalName); // supprime accents, espaces, caractères spéciaux
        $unique = Str::substr(Str::uuid()->toString(), 0, 8); // identifiant court mais unique

        // Sécurité : si le nom slugifié est vide (nom de fichier uniquement en caractères spéciaux)
        $slug = $slug ?: 'fichier';

        $filename = "{$slug}-{$unique}.{$extension}";

        $file->storeAs($directory, $filename, 'public');

        return "{$directory}/{$filename}";
    }

    public function confirmation(Candidature $candidature)
    {
        /* Ceci est pour protéger les url avec les id part 2/2 */
        /*  if (session('last_candidature_id') !== $candidature->id) {
            abort(403);
        } */

        $candidature->load('user', 'langueSpecialisation');
        return view('candidatures.confirmation', compact('candidature'));
    }

    public function show(Candidature $candidature)
    {
        $candidature->load('user', 'langueSpecialisation');

        $documents = [
            'Pièce d\'identité' => $candidature->piece_identite_path,
            'Diplôme'           => $candidature->diplome_fichier_path,
            'Certification'     => $candidature->certification_fichier_path,
            'CV'                => $candidature->cv_path,
        ];

        return view('candidatures.show', compact('candidature', 'documents'));
    }

    public function edit(Candidature $candidature)
    {
        $candidature->load('user', 'langueSpecialisation');
        $languesSpecialisations = LanguesSpecialisation::orderBy('nom')->get();

        $currentFiles = [
            'piece_identite'        => ['label' => 'Pièce d\'identité',      'path' => $candidature->piece_identite_path],
            'diplome_fichier'       => ['label' => 'Diplôme',                 'path' => $candidature->diplome_fichier_path],
            'certification_fichier' => ['label' => 'Certification',           'path' => $candidature->certification_fichier_path],
            'cv'                     => ['label' => 'CV',                      'path' => $candidature->cv_path],
        ];

        return view('candidatures.edit', compact('candidature', 'languesSpecialisations', 'currentFiles'));
    }

    public function update(Request $request, Candidature $candidature)
    {
        $validated = $request->validate([
            'diplome'               => ['required', 'string'],
            'langue_maternelle'     => ['required', 'string'],
            'niveau_francais'       => ['required', 'string'],
            'langue_vivante_2'      => ['nullable', 'string'],
            'disponible_debut'      => ['required', 'date'],
            'disponible_fin'        => ['required', 'date', 'after_or_equal:disponible_debut'],
            'zone'                  => ['required', 'string'],
            'delegation_souhaitee'  => ['nullable', 'string'],

            // Fichiers optionnels : uniquement validés s'ils sont envoyés
            'piece_identite'         => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'diplome_fichier'        => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'certification_fichier'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'cv'                      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        // Correspondance champ formulaire -> [colonne base, dossier de stockage]
        $fileMap = [
            'piece_identite'        => ['column' => 'piece_identite_path',        'dir' => 'candidatures/pieces_identite'],
            'diplome_fichier'       => ['column' => 'diplome_fichier_path',       'dir' => 'candidatures/diplomes'],
            'certification_fichier' => ['column' => 'certification_fichier_path', 'dir' => 'candidatures/certifications'],
            'cv'                     => ['column' => 'cv_path',                    'dir' => 'candidatures/cv'],
        ];

        foreach ($fileMap as $field => $info) {
            if ($request->hasFile($field)) {
                // Supprime l'ancien fichier avant d'écrire le nouveau
                $oldPath = $candidature->{$info['column']};
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                $validated[$info['column']] = $this->storeUploadedFile($request->file($field), $info['dir']);
            }
        }

        $candidature->update($validated);

        return redirect()
            ->route('candidatures.show', $candidature->id)
            ->with('success', 'La candidature a été mise à jour.');
    }

    public function updateStatut(Request $request, Candidature $candidature)
    {
        $validated = $request->validate([
            'statut'            => ['required', Rule::in(['en_attente', 'validee', 'rejetee'])],
            'commentaire_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $candidature->update($validated);

        return redirect()
            ->route('candidatures.show', $candidature->id)
            ->with('success', 'Le statut de la candidature a été mis à jour.');
    }

    public function destroy(Candidature $candidature)
    {
        // Supprime les fichiers physiques associés avant de supprimer l'enregistrement
        foreach (
            [
                $candidature->piece_identite_path,
                $candidature->diplome_fichier_path,
                $candidature->certification_fichier_path,
                $candidature->cv_path,
            ] as $path
        ) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $candidature->delete();

        return redirect()
            ->route('candidatures.index')
            ->with('success', 'La candidature a été supprimée.');
    }
}
