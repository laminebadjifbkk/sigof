<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();
        Alert::success('✅ Vérification envoyée', "Merci ! Nous vous avons envoyé un e-mail de vérification 📩.\nN'oubliez pas de consulter votre boîte de réception, vos spams ou vos courriers indésirables.");
        return redirect()->back();

        /* return back()->with('status', 'verification-link-sent'); */
    }
}
