<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Antenne;
use App\Models\Collective;
use App\Models\Contact;
use App\Models\Individuelle;
use App\Models\Module;
use App\Models\Operateur;
use App\Models\Poste;
use App\Models\Projet;
use App\Models\Referentiel;
use App\Models\Service;
use App\Models\Une;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function accueil(): View
    {
        /* return view('auth.login'); */

        /* $une      = Une::where("status", "!=", null)->first();
        $projets  = Projet::where("image", "!=", null)->get();
        $contacts = Contact::limit(5)->orderBy("created_at", "desc")->where("statut", "!=", null)->get();
        $today    = date('Y-m-d');

        $annee      = date('Y');
        $anciennete = date('Y')-'1987';

        $services = Service::get();
        $posts    = Poste::orderBy("created_at", "desc")->limit(4)->get();

        $posts_count = count($posts);

        if (! empty($posts_count)) {
            foreach ($posts as $key => $postNotEmpty) {
            }
        } else {
            $postNotEmpty = null;
        }

        $count_today         = Module::distinct()->count();
        $count_individuelles = Individuelle::count();
        $count_collectives   = Collective::count();
        $referentiels        = Referentiel::count();
        $count_demandeurs    = $count_individuelles + $count_collectives;
        $count_projets       = Projet::count();
        $antennes            = Antenne::get();

        $count_operateurs = Operateur::where('statut_agrement', 'agréer')->count();

        if ($count_today <= "0") {
            $title = "module de formation";
        } else {
            $title = "modules de formation";
        } */

        $une      = Une::whereNotNull("status")->first();
        $projets  = Projet::whereNotNull("image")->get();
        $contacts = Contact::whereNotNull("statut")->latest()->limit(5)->get();
        $today    = now()->toDateString();

        $annee      = date('Y');
        $anciennete = $annee - 1987;

        $services    = Service::all();
        $posts       = Poste::latest()->limit(4)->get();
        $posts_count = $posts->count();

        $count_today         = Module::distinct()->count();
        $count_individuelles = Individuelle::count();
        $count_collectives   = Collective::count();
        $referentiels        = Referentiel::count();
        $count_demandeurs    = $count_individuelles + $count_collectives;
        $count_projets       = Projet::count();
        $antennes            = Antenne::all();

        $count_operateurs = Operateur::where('statut_agrement', 'agréer')->count();

        $title = $count_today <= 0 ? "module de formation" : "modules de formation";

        return view(
            'accueil',
            compact(
                'une',
                'count_today',
                'title',
                'projets',
                'contacts',
                'count_individuelles',
                'count_projets',
                'count_operateurs',
                'count_collectives',
                'count_demandeurs',
                'referentiels',
                'anciennete',
                'antennes',
                'services',
                'posts_count',
                'posts',
            )
        );
    }

    public function create(): View
    {
        /* return view('auth.login'); */
        return view('user.login-page');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        /* Alert::success('Bienvenue, ' . Auth::user()->firstname . ' ' . Auth::user()->name . '!', 'Connexion réussie.'); */

        alert()->html(
            '<i>Connexion réussie !</i>',
            "Bienvenue, <br> Votre connexion a été établie avec succès.<br>
        Nous vous souhaitons une excellente expérience sur notre plateforme !",
            'success'
        );

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        /* Alert::success('Déconnexion réussie', 'Merci d’avoir utilisé notre application! Nous espérons vous revoir bientôt. À très bientôt !'); */

        alert()->html('<i>Déconnexion réussie !</i>', "Merci d'avoir utilisé notre application ! <br>
        Nous espérons vous revoir bientôt !", 'success');

        return redirect('/');
    }
}
