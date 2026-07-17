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

class CandidatureController extends Controller
{
    public function create()
    {
        $languesSpecialisations = LanguesSpecialisation::orderBy('nom')->get();
        return view('candidatures.inscription', compact('languesSpecialisations'));
    }

    public function store(StoreCandidatureRequest $request)
    {
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
}
