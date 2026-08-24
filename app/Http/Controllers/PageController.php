<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PageController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            return redirect('/admin/ylp');
        }

        $dateOuverture = Carbon::create(2026, 8, 17, 8, 0, 0, 'Africa/Dakar');
        $dateFermeture = Carbon::create(2026, 8, 26, 17, 0, 0, 'Africa/Dakar');
        $maintenant = Carbon::now('Africa/Dakar');

        if ($maintenant->lt($dateOuverture)) {
            $phase = 'avant';
        } elseif ($maintenant->lte($dateFermeture)) {
            $phase = 'ouvert';
        } else {
            $phase = 'ferme';
        }
        return view('pages.home', compact('dateOuverture', 'dateFermeture', 'maintenant', 'phase'));
    }
}
