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
        return Socialite::driver('google')->redirect();
    }

    /*  public function googlecallback()
    {
        try {

            $user = Socialite::driver('google')->user();

            $finduser = User::where('google_id', $user->id)->first();

            if ($finduser) {

                Auth::login($finduser);

                return redirect()->intended('dashboard');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => encrypt('P@ssw0rd123')
                ]);

                Auth::login($newUser);

                // Supposons que $newUser est déjà créé
                $role = Role::where('name', 'Demandeur')->first();

                if ($role) {
                    $newUser->assignRole($role);
                }

                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    } */

    public function googlecallback()
    {
        try {

            $googleUser = Socialite::driver('google')->user();

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
                    $role = Role::where('name', 'Demandeur')->first();
                    
                    if ($role) {
                        $user->assignRole($role);
                    }
                }
            }

            Auth::login($user);

            return redirect()->intended('dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
