<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\File;
use App\Models\Formulaire;
use App\Models\Individuelle;
use App\Models\Projet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    /* public function profilePage(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        if (auth()->check() && (auth()->user()->hasAnyRole('Google') || auth()->user()->roles->isEmpty())) {
            return redirect()->route('profil.choisir');
        }

        $email = $user->email;

        // Récupérer le formulaire lié à l'utilisateur
        $formulaire = Formulaire::where('email', $email)->first();

        // Préparer les variables pour la vue
        $statusMessage         = null;
        $showCard              = false;
        $showChangeCertificat  = false;

        if ($formulaire) {
            if ($formulaire->statut === 'Sélectionné') {
                $showCard      = true;
                $statusMessage = "Félicitations, votre demande de prise en charge a été retenue !";
            } else {
                $statusMessage = "Votre demande de prise en charge n'a pas été retenue.";
            }

            // 🔹 Vérifier si certificat existe et statut_certificat est Nouveau ou Rejeté
            if ($formulaire->certificat_file && in_array($formulaire->statut_certificat, ['Nouveau', 'Rejeté'])) {
                $showChangeCertificat = true;
            }
        } else {
            $statusMessage = "Vous n'avez pas de demande de prise en charge à votre nom.";
        }


        // 🔹 Définir les bornes de temps
        $start = Carbon::create(2025, 12, 8, 13, 30);   // 9 décembre 2025 à 08h30
        $end   = Carbon::create(2025, 12, 17, 17, 0);  // 15 décembre 2025 à 17h00
        $now   = Carbon::now();
        // 🔹 Vérifier si on est dans l'intervalle
        $showButton = $now->between($start, $end);

        $projets = Projet::where('statut', 'ouvert')
            ->orderBy('created_at', 'desc')
            ->get();

        $usercin = File::where('users_id', $user->id)
            ->where('file', '!=', null)
            ->where('sigle', 'CIN')
            ->count();

        if (! empty($usercin) && $usercin > '0') {
            $user_cin = $usercin;
        } else {
            $user_cin = null;
        }

        $files = File::where('users_id', $user->id)
            ->whereNotNull('file') // Utilisation de whereNotNull pour plus de clarté
            ->distinct()
            ->get();

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC', 'Titre', 'Contrat', 'Convention', 'Organigramme', 'Quitus', 'Carte', 'Casier', 'Assurance', 'Lettre', 'Bail'])
            ->orderBy('sigle', 'asc')
            ->get()
            ->unique('sigle') // Évite les doublons sur le champ "sigle"
            ->values();       // Réindexe proprement la collection (0, 1, 2, ...)

        $count_projets = Individuelle::join('projets', 'projets.id', 'individuelles.projets_id')
            ->select('projets.*')
            ->where('individuelles.users_id', $user->id)
            ->where('individuelles.projets_id', '!=', null)
            ->where('projets.statut', 'ouvert')
            ->orwhere('projets.statut', 'fermer')
            ->distinct()
            ->get();

        $individuelles = Individuelle::where('users_id', $user->id)
            ->where('projets_id', null)
            ->get();

        $formations = $individuelles->where('formations_id', '!=', null)->count();

        $nouvelle_formation_count = Auth::user()->individuelles()
            ->join('formations', 'formations.id', 'individuelles.formations_id')
            ->select('individuelles.*')
            ->where('individuelles.users_id', $user->id)
            ->where('formations.statut', 'Nouvelle')->count();

        $collectives = Collective::where('users_id', $user->id)
            ->get();

        $employee = Auth::user()?->employee;

        $courriers_auj = 0;

        if ($employee) {
            $courriers_auj = $employee->arrives()
                ->whereDate('arrives.jour_imputation', Carbon::today())
                ->count();
        }

        $count_ingenieur_formations = Auth::user()?->employee?->arrives?->count();

        $projet = Projet::where("statut", "ouvert")->first();

        if ($projet) {
            $date_ouverture = Carbon::parse($projet->date_ouverture)->setTime(8, 0, 0);  // 08:00
            $date_fermeture = Carbon::parse($projet->date_fermeture)->setTime(17, 0, 0); // 17:00
        } else {
            $date_ouverture = null;
            $date_fermeture = null;
        }

        foreach (Auth::user()->roles as $role) {
            if ($role->name == 'Operateur') {

                // Récupérer les fichiers associés à l'utilisateur
                $files = File::where('users_id', $user->id)
                    ->whereNotNull('file')
                    ->distinct()
                    ->get();

                $user_files = File::whereNull('file')
                    ->whereNull('users_id')
                    ->whereNotIn('sigle', ['CIN', 'DAC', 'DP', 'CR', 'AD', 'Bulletins', 'Titre', 'Contrat', 'Convention', 'Organigramme', 'Quitus', 'Carte', 'Casier', 'Assurance', 'Lettre', 'Bail', 'RIB', 'Domicile', 'Justificatif'])
                    ->orderBy('sigle', 'asc')
                    ->get()
                    ->unique('sigle') // Évite les doublons sur le champ "sigle"
                    ->values();       // Réindexe proprement la collection (0, 1, 2, ...)

                $usercin = File::where('users_id', $user->id)
                    ->where('file', '!=', null)
                    ->where('sigle', 'AC')
                    ->count();

                if (! empty($usercin) && $usercin > '0') {
                    $user_cin = $usercin;
                } else {
                    $user_cin = null;
                }

                $user = Auth::user();

                $isComplete =
                    !empty($user?->operateur) &&
                    !empty($user?->ninea) &&
                    !empty($user?->fonction_responsable) &&
                    !empty($user?->email);

                return view('profile.profile-operateur-page', [
                    'user'                     => $request->user(),
                    'projets'                  => $projets,
                    'count_projets'            => $count_projets,
                    'nouvelle_formation_count' => $nouvelle_formation_count,
                    'files'                    => $files,
                    'user_files'               => $user_files,
                    'user_cin'                 => $user_cin,
                    'date_ouverture'           => $date_ouverture,
                    'date_fermeture'           => $date_fermeture,
                    'showCard'           => $showCard,
                    'statusMessage'           => $statusMessage,
                    'formulaire'           => $formulaire,
                    'showChangeCertificat'           => $showChangeCertificat,
                    'showButton'           => $showButton,
                    'isComplete'           => $isComplete,
                ]);
            } else {
                return view('profile.profile-page', [
                    'user'                       => $request->user(),
                    'projets'                    => $projets,
                    'count_projets'              => $count_projets,
                    'individuelles'              => $individuelles,
                    'formations'                 => $formations,
                    'nouvelle_formation_count'   => $nouvelle_formation_count,
                    'collectives'                => $collectives,
                    'files'                      => $files,
                    'user_files'                 => $user_files,
                    'user_cin'                   => $user_cin,
                    'courriers_auj'              => $courriers_auj,
                    'count_ingenieur_formations' => $count_ingenieur_formations,
                    'date_ouverture'             => $date_ouverture,
                    'date_fermeture'             => $date_fermeture,
                    'showCard'             => $showCard,
                    'statusMessage'             => $statusMessage,
                    'formulaire'             => $formulaire,
                    'showChangeCertificat'           => $showChangeCertificat,
                    'showButton'           => $showButton,
                    'isComplete'           => $isComplete,
                ]);
            }
        }

        return view('profile.profile-page', [
            'user'                     => $request->user(),
            'projets'                  => $projets,
            'count_projets'            => $count_projets,
            'nouvelle_formation_count' => $nouvelle_formation_count,
            'files'                    => $files,
            'user_files'               => $user_files,
            'user_cin'                 => $user_cin,
            'date_ouverture'           => $date_ouverture,
            'date_fermeture'           => $date_fermeture,
            'showCard'           => $showCard,
            'showCard'           => $showCard,
            'statusMessage'           => $statusMessage,
            'statusMessage'           => $statusMessage,
            'formulaire'           => $formulaire,
            'showChangeCertificat'           => $showChangeCertificat,
            'showButton'           => $showButton,
        ]);
    } */

    public function profilePage(Request $request): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user && ($user->hasAnyRole('Google') || $user->roles->isEmpty())) {
            return redirect()->route('profil.choisir');
        }

        $email = $user->email;

        // Formulaire
        $formulaire = Formulaire::where('email', $email)->first();

        $statusMessage = null;
        $showCard = false;
        $showChangeCertificat = false;

        if ($formulaire) {
            if ($formulaire->statut === 'Sélectionné') {
                $showCard = true;
                $statusMessage = "Félicitations, votre demande de prise en charge a été retenue !";
            } else {
                $statusMessage = "Votre demande de prise en charge n'a pas été retenue.";
            }

            if ($formulaire->certificat_file && in_array($formulaire->statut_certificat, ['Nouveau', 'Rejeté'])) {
                $showChangeCertificat = true;
            }
        } else {
            $statusMessage = "Vous n'avez pas de demande de prise en charge à votre nom.";
        }

        // Dates
        $start = Carbon::create(2025, 12, 8, 13, 30);
        $end   = Carbon::create(2025, 12, 17, 17, 0);
        $showButton = Carbon::now()->between($start, $end);

        // Projets
        $projets = Projet::where('statut', 'ouvert')
            ->latest()
            ->get();

        $count_projets = Individuelle::join('projets', 'projets.id', 'individuelles.projets_id')
            ->where('individuelles.users_id', $user->id)
            ->whereNotNull('individuelles.projets_id')
            ->where(function ($q) {
                $q->where('projets.statut', 'ouvert')
                    ->orWhere('projets.statut', 'fermer');
            })
            ->distinct()
            ->get();

        // Fichiers
        $files = File::where('users_id', $user->id)
            ->whereNotNull('file')
            ->distinct()
            ->get();

        $user_files = File::whereNull('file')
            ->whereNull('users_id')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC', 'Titre', 'Contrat', 'Convention', 'Organigramme', 'Quitus', 'Carte', 'Casier', 'Assurance', 'Lettre', 'Bail'])
            ->orderBy('sigle')
            ->get()
            ->unique('sigle')
            ->values();

        $user_cin = File::where('users_id', $user->id)
            ->whereNotNull('file')
            ->where('sigle', 'CIN')
            ->count() ?: null;

        // Individuelles
        $individuelles = Individuelle::where('users_id', $user->id)
            ->whereNull('projets_id')
            ->get();

        $formations = $individuelles->whereNotNull('formations_id')->count();

        $nouvelle_formation_count = $user->individuelles()
            ->join('formations', 'formations.id', 'individuelles.formations_id')
            ->where('formations.statut', 'Nouvelle')
            ->count();

        $collectives = Collective::where('users_id', $user->id)->get();

        // Employee
        $employee = $user->employee;

        $courriers_auj = $employee
            ? $employee->arrives()->whereDate('jour_imputation', Carbon::today())->count()
            : 0;

        $count_ingenieur_formations = $employee?->arrives?->count();

        // Projet actif
        $projet = Projet::where("statut", "ouvert")->first();

        $date_ouverture = $projet ? Carbon::parse($projet->date_ouverture)->setTime(8, 0) : null;
        $date_fermeture = $projet ? Carbon::parse($projet->date_fermeture)->setTime(17, 0) : null;

        // Profil complet
        $isComplete =
            !empty($user?->operateur) &&
            !empty($user?->ninea) &&
            !empty($user?->fonction_responsable) &&
            !empty($user?->email);

        // 🔥 Gestion rôle (simplifiée)
        if ($user->hasRole('Operateur')) {

            $user_files = File::whereNull('file')
                ->whereNull('users_id')
                ->whereNotIn('sigle', ['CIN', 'DAC', 'DP', 'CR', 'AD', 'Bulletins', 'Titre', 'Contrat', 'Convention', 'Organigramme', 'Quitus', 'Carte', 'Casier', 'Assurance', 'Lettre', 'Bail', 'RIB', 'Domicile', 'Justificatif'])
                ->orderBy('sigle')
                ->get()
                ->unique('sigle')
                ->values();

            $user_cin = File::where('users_id', $user->id)
                ->whereNotNull('file')
                ->where('sigle', 'AC')
                ->count() ?: null;

            return view('profile.profile-operateur-page', compact(
                'user',
                'projets',
                'count_projets',
                'nouvelle_formation_count',
                'files',
                'user_files',
                'user_cin',
                'date_ouverture',
                'date_fermeture',
                'showCard',
                'statusMessage',
                'formulaire',
                'showChangeCertificat',
                'showButton',
                'isComplete'
            ));
        }

        return view('profile.profile-page', compact(
            'user',
            'projets',
            'count_projets',
            'individuelles',
            'formations',
            'nouvelle_formation_count',
            'collectives',
            'files',
            'user_files',
            'user_cin',
            'courriers_auj',
            'count_ingenieur_formations',
            'date_ouverture',
            'date_fermeture',
            'showCard',
            'statusMessage',
            'formulaire',
            'showChangeCertificat',
            'showButton',
            'isComplete'
        ));
    }

    /**
     * Display the user's lin.
     */
    public function loginPage(Request $request): View
    {
        return view('user.login-page');
    }
    /**
     * Display the user's register.
     */
    public function registerPage(Request $request): View
    {
        return view('user.register-page');
    }
    public function registerOperateur(Request $request): View
    {
        return view('user.register-operateur');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    /* public function update(ProfileUpdateRequest $request, $id): RedirectResponse */
    public function update(Request $request, User $user)
    {
        /* $request->user()->fill($request->validated()); */

        $user = User::findOrFail($request->idUser);

        $this->validate($request, [
            'cin'                       => [
                'required',
                'string',
                'min:13',
                'max:14',
                Rule::unique(User::class)->ignore($user->id ?? null)->whereNull('deleted_at'),
            ],
            /* 'username'                  => ['required', 'string'], */
            /* 'username'                  => [
                'required',
                'string',
                'min:3',
                'max:25',
                Rule::unique('users')->ignore($user->id ?? null)->whereNull('deleted_at'),
            ], */
            'civilite'                  => ['required', 'string', 'max:8'],
            'firstname'                 => ['required', 'string', 'max:150'],
            'name'                      => ['required', 'string', 'max:25'],
            'date_naissance'            => ['required', 'date_format:d/m/Y'],
            'lieu_naissance'            => ['required', 'string'],
            'image'                     => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            'email'                     => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id ?? null)->whereNull('deleted_at'),
            ],
            'telephone'                 => ['nullable', 'string', 'size:9'],
            'adresse'                   => ['required', 'string', 'max:255'],
            'situation_familiale'       => ['required', 'string', 'max:15'],
            'situation_professionnelle' => ['required', 'string', 'max:25'],
            'twitter'                   => ['nullable', 'string', 'max:255'],
            'facebook'                  => ['nullable', 'string', 'max:255'],
            'instagram'                 => ['nullable', 'string', 'max:255'],
            'linkedin'                  => ['nullable', 'string', 'max:255'],
            'web'                       => ['nullable', 'string', 'max:255'],
            'fixe'                      => ['nullable', 'string', 'max:255'],
        ]);

        $dateString = $request?->input('date_naissance');

        $date = null;
        if (! empty($dateString)) {
            try {
                $date = Carbon::createFromFormat('d/m/Y', $dateString);
            } catch (\Exception $e) {
                // Optionnel : gérer l’erreur, par exemple logger ou retourner un message
                // Log::error('Format de date invalide : ' . $dateString);
            }
        }

        $user->update([
            'cin'                       => $request->input('cin'),
            /* 'username'                  => substr(str_replace(' ', '', $request->username), 0, 10), */
            'civilite'                  => $request->input('civilite'),
            'firstname'                 => format_proper_name($request->input('firstname')),
            'name'                      => remove_accents_uppercase($request->input('name')),
            'date_naissance'            => $date,
            'lieu_naissance'            => remove_accents_uppercase($request->input('lieu_naissance')),
            /* 'image'                     => $request->input('image'), */
            'email'                     => $request->input('email'),
            'telephone'                 => $request->input('telephone'),
            'adresse'                   => remove_accents_uppercase($request->input('adresse')),
            'situation_familiale'       => $request->input('situation_familiale'),
            'situation_professionnelle' => $request->input('situation_professionnelle'),
            'twitter'                   => $request->input('twitter'),
            'facebook'                  => $request->input('facebook'),
            'instagram'                 => $request->input('instagram'),
            'linkedin'                  => $request->input('linkedin'),
            'web'                       => $request->input('web'),
            'fixe'                      => $request->input('fixe'),
        ]);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        /*
        if (request('image')) {
            if (! empty($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $imagePath       = request('image')->store('avatars', 'public');
            $file            = $request->file('image');
            $filenameWithExt = $file->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Remove unwanted characters
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            // Get the original image extension
            $extension = $file->getClientOriginalExtension();

            // Create unique file name
            $fileNameToStore = 'avatars/' . $filename . '' . time() . '.' . $extension;

            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(800, 800);

            $image->save();

            $request->user()->update([
                'image' => $imagePath,
            ]);
        } */

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if (! empty($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            $file = $request->file('image');

            // Crée une version renommée et propre du nom
            $filename        = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename        = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename        = preg_replace("/\s+/", '-', $filename);
            $extension       = $file->getClientOriginalExtension();
            $fileNameToStore = 'avatars/' . $filename . time() . '.' . $extension;

            // Utilise Intervention sur le fichier temporaire directement
            $image = Image::make($file->getRealPath())->fit(800, 800);

            // Sauvegarde manuellement dans le disque 'public'
            Storage::disk('public')->put($fileNameToStore, (string) $image->encode());

            // Met à jour l'utilisateur
            $user->update([
                'image' => $fileNameToStore,
            ]);
        }

        $request->user()->save();

        Alert::success('Mise à jour réussie', 'Votre profil a bien été modifié.');

        /* return Redirect::route('profile.edit')->with('status', 'profile-updated'); */
        /* return Redirect::route('profil')->with('status', 'Votre profil a été modifié avec succès'); */
        /* return Redirect::route('profil'); */
        return back(); // Redirige vers la page précédente
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        Storage::disk('public')->delete($user->image);

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        /* return Redirect::to('/'); */
        return back(); // Redirige vers la page précédente
    }

    public function destroyImage()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->image) {
            // Supprimer l'image du stockage
            Storage::disk('public')->delete($user->image);

            // Mettre à jour l'utilisateur (remettre l'image par défaut ou null)
            $user->update(['image' => null]);

            Alert::success('Succès', 'Votre image de profil a été supprimée avec succès.');
        } else {
            Alert::warning('Attention', 'Aucune image de profil à supprimer.');
        }

        return back(); // Redirige vers la page précédente
    }

    public function choisir()
    {
        return view('auth.choisir-profil');
    }

    public function store(Request $request)
    {
        $request->validate([
            'profil' => 'required'
        ]);

        $user = Auth::user();

        if ($request->profil == 'demandeur') {
            $user->syncRoles('Demandeur');
        }

        if ($request->profil == 'operateur') {
            $user->syncRoles('Operateur');
        }

        return redirect()->route('profil')
            ->with('success', 'Profil activé avec succès.');
    }
}
