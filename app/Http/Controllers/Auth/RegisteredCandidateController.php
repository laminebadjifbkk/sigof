<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisteredCandidateController extends Controller
{
    public function create()
    {
        return view('auth.inscription');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Étape 1 — Profil
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190', 'unique:candidats,email'],
            'telephone' => ['required', 'string', 'max:30'],
            'date_naissance' => ['required', 'date', 'before_or_equal:-21 years', 'after_or_equal:-35 years'],

            // Étape 2 — Langues
            'langue_specialisation' => ['required', 'string'],
            'certification' => ['nullable', 'string', 'max:120'],
            'diplome' => ['required', 'string'],
            'langue_maternelle' => ['required', 'string'],
            'niveau_francais' => ['required', 'string'],
            'langue_vivante_2' => ['required', 'string'],

            // Étape 3 — Disponibilité
            'disponible_debut' => ['required', 'date'],
            'disponible_fin' => ['required', 'date', 'after_or_equal:disponible_debut'],
            'zone' => ['required', 'string'],
            'delegation_souhaitee' => ['nullable', 'string', 'max:190'],

            // Étape 4 — Documents
            'piece_identite' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'diplome_fichier' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'certification_fichier' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'cv' => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'attestation' => ['accepted'],
        ], [
            'date_naissance.before_or_equal' => "Vous devez avoir au moins 21 ans.",
            'date_naissance.after_or_equal' => "Vous ne devez pas dépasser 35 ans.",
            'attestation.accepted' => "Vous devez accepter la charte du programme pour continuer.",
        ]);

        // Stockage des fichiers (adapter le disque : 'local', 's3', etc.)
        foreach (['piece_identite', 'diplome_fichier', 'certification_fichier', 'cv'] as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('candidatures', 'private');
            }
        }

        // TODO : persister $validated dans votre modèle Candidat, ex :
        // Candidat::create($validated);

        return redirect()->route('connexion')
            ->with('status', 'Votre candidature a bien été envoyée. Vous recevrez un e-mail de confirmation.');
    }
}
