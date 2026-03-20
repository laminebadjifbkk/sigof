<?php
/*
namespace App\Providers;

use App\Models\ActiviteQuotidienne;
use App\Models\Antenne;
use App\Models\Formation;
use App\Models\ParcMission;
use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Direction;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));
        DB::statement("SET NAMES 'utf8mb4'");
        DB::statement("SET CHARACTER SET utf8mb4");
        View::composer('layout.page-sidebar', function ($view) {
            $antennes = Antenne::select('id', 'code', 'name')->orderBy('name')->get();
            $view->with('antennes', $antennes);
        });
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->view('auth.verify-costum', [
                    'user' => $notifiable,
                    'url'  => $url,
                ]);
        });
    }
} */

namespace App\Providers;

use App\Models\Antenne;
use App\Models\Direction;
use App\Models\Formation;
use App\Models\ParcMission;
use App\Models\Projet;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\ActiviteQuotidienne;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Définir la locale pour Carbon
        Carbon::setLocale(config('app.locale'));

        // Configuration de l'encodage de la BDD
        DB::statement("SET NAMES 'utf8mb4'");
        DB::statement("SET CHARACTER SET utf8mb4");

        // Composer pour la sidebar
        View::composer('layout.page-sidebar', function ($view) {
            /* $antennes = Antenne::select('id', 'code', 'name')->orderBy('name')->get(); */
            $antennes = Antenne::orderBy('name')->get();
            /* $directions = Direction::select('*')->orderBy('sigle')->get(); */
            $directions        = Direction::orderBy('sigle')->get();
            $projets           = Projet::where('statut', 'ouvert')->orderBy('sigle')->get();
            $formationsEnCours = Formation::where('statut', 'En cours')->count();
            $missionsEnCours = ParcMission::where('statut', 'en_cours')->count();
            $showAttestations  = Formation::where('attestation', 'Nouveau')->count();
            $retards = ActiviteQuotidienne::where('statut', 'retard')->count();

            $view->with([
                'antennes'          => $antennes,
                'directions'        => $directions,
                'projets'           => $projets,
                'formationsEnCours' => $formationsEnCours,
                'showAttestations'  => $showAttestations,
                'missionsEnCours'  => $missionsEnCours,
                'retards'  => $retards,
            ]);
        });

        // Personnalisation de l'e-mail de vérification
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)->view('auth.verify-costum', [
                'user' => $notifiable,
                'url'  => $url,
            ]);
        });
    }
}
