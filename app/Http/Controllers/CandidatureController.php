<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidatureRequest;
use App\Models\Candidature;
use App\Models\LanguesSpecialisation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CandidatureController extends Controller
{
    public function create()
    {
        return view('candidatures.inscription');
    }

    public function store(StoreCandidatureRequest $request)
    {
        dd("ok");
        $validated = $request->validated();

        $langue = LanguesSpecialisation::where('code', $validated['langue_specialisation'])->firstOrFail();

        // Vérifie qu'il reste des postes disponibles pour cette langue
        if ($langue->candidatures()->count() >= $langue->postes_disponibles) {
            return back()
                ->withInput()
                ->withErrors(['langue_specialisation' => 'Il n\'y a plus de postes disponibles pour cette langue.']);
        }

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

            $piecePath = $request->file('piece_identite')->store('candidatures/pieces_identite', 'public');
            $diplomePath = $request->file('diplome_fichier')->store('candidatures/diplomes', 'public');
            $cvPath = $request->file('cv')->store('candidatures/cv', 'public');
            $certifPath = $request->hasFile('certification_fichier')
                ? $request->file('certification_fichier')->store('candidatures/certifications', 'public')
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

        return redirect()
            ->route('inscription.confirmation', $candidature->id)
            ->with('success', 'Votre candidature a bien été envoyée.');
    }

    public function confirmation(Candidature $candidature)
    {
        return view('inscription.confirmation', compact('candidature'));
    }
}
