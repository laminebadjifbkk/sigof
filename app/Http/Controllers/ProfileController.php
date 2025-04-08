<?php
namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\File;
use App\Models\Individuelle;
use App\Models\Projet;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{

    /**
     * Display the user's profile show.
     */
    public function profilePage(Request $request): View
    {
        $user    = Auth::user();
        $projets = Projet::where('statut', 'ouvert')
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

        $user_files = File::where('users_id', $user?->id)
            ->whereNull('file')
            ->whereNotIn('sigle', ['AC', 'Arrêté', 'Ninea/RC'])
            ->distinct()
            ->get();

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

        /* $individuelleformation = Auth::user()->individuelles()
            ->join('formations', 'formations.id', '=', 'individuelles.formations_id')
            ->where('formations.statut', 'Nouvelle')
            ->select('individuelles.*')
            ->get(); */

        $collectives = Collective::where('users_id', $user->id)
            ->get();

        /* $count_courriers            = Auth::user()?->employee?->arrives?->count(); */
        $courriers_auj = Auth::user()?->employee
            ->arrives()
            ->whereDate('arrives.jour_imputation', Carbon::today()) // Filtre sur 'jour_imputation' dans la table 'arrives'
            ->count();
        $count_ingenieur_formations = Auth::user()?->employee?->arrives?->count();

        foreach (Auth::user()->roles as $role) {
            if ($role->name == 'Operateur') {

                // Récupérer les fichiers associés à l'utilisateur
                $files = File::where('users_id', $user->id)
                    ->whereNotNull('file')
                    ->distinct()
                    ->get();

                $user_files = File::where('users_id', $user?->id)
                    ->whereNull('file')
                    ->whereNotIn('sigle', ['CIN', 'DAC', 'DP', 'CR', 'AD', 'Bulletins'])
                    ->distinct()
                    ->get();

                $usercin = File::where('users_id', $user->id)
                    ->where('file', '!=', null)
                    ->where('sigle', 'AC')
                    ->count();

                if (! empty($usercin) && $usercin > '0') {
                    $user_cin = $usercin;
                } else {
                    $user_cin = null;
                }

                return view('profile.profile-operateur-page', [
                    'user'                     => $request->user(),
                    'projets'                  => $projets,
                    'count_projets'            => $count_projets,
                    'nouvelle_formation_count' => $nouvelle_formation_count,
                    'files'                    => $files,
                    'user_files'               => $user_files,
                    'user_cin'                 => $user_cin,
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
        ]);
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
    public function update(Request $request, $id)
    {
        /* $request->user()->fill($request->validated()); */

        $this->validate($request, [
            'cin'                       => [
                'required',
                'string',
                'min:16',
                'max:17',
                Rule::unique(User::class)->ignore($id ?? null)->whereNull('deleted_at'),
            ],
            /* 'username'                  => ['required', 'string'], */
            'username'                  => [
                'required',
                'string',
                'min:3',
                'max:25',
                Rule::unique('users')->ignore($id ?? null)->whereNull('deleted_at'),
            ],
            'civilite'                  => ['required', 'string', 'max:8'],
            'firstname'                 => ['required', 'string', 'max:150'],
            'name'                      => ['required', 'string', 'max:25'],
            'date_naissance'            => ['nullable', 'date_format:d/m/Y'],
            'lieu_naissance'            => ['required', 'string'],
            'image'                     => ['sometimes', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024'],
            'email'                     => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($id ?? null)->whereNull('deleted_at'),
            ],
            'telephone'                 => ['nullable', 'string', 'size:12'],
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

        $user       = User::findOrFail($id);
        $dateString = $request->input('date_naissance');
        $date       = Carbon::createFromFormat('d/m/Y', $dateString);

        $user->update([
            'cin'                       => $request->input('cin'),
            'username'                  => substr(str_replace(' ', '', $request->username), 0, 10),
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

            /* dd($fileNameToStore); */

            $image = Image::make(public_path("/storage/{$imagePath}"))->fit(800, 800);

            $image->save();

            $request->user()->update([
                'image' => $imagePath,
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

}
