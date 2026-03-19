<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    public function googlepage()
    {
        /* return Socialite::driver('google')->redirect(); */
        return Socialite::driver('google')
            ->with([
                'prompt' => 'select_account consent',
                'access_type' => 'offline',
            ])
            ->redirect();
    }

    public function googlecallback()
    {
        try {

            /* $googleUser = Socialite::driver('google')->user(); */
            $googleUser = Socialite::driver('google')->stateless()->user();

            // 1️⃣ Chercher par google_id
            $user = User::where('google_id', $googleUser->id)->first();

            // 2️⃣ Si pas trouvé, chercher par email
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Mettre à jour google_id si l'utilisateur existe déjà
                    $user->update([
                        'google_id' => $googleUser->id
                    ]);
                } else {

                    // 3️⃣ Créer nouvel utilisateur
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password' => bcrypt(str()->random(16)),
                    ]);

                    // Attribution du rôle
                    $role = Role::where('name', 'Google')->first();

                    if ($role) {
                        $user->assignRole($role);
                    }
                }
            }

            Auth::login($user);

            if (auth()->check() && auth()->user()->hasanyrole('Google')) {
                return redirect()->route('profil.choisir');
            }

            return redirect()->route('profil');

            /* return redirect()->intended('dashboard'); */
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
