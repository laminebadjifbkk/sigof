<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Individuelle;

class DashboardController extends Controller
{
    public function index()
    {
        // Exemple de données à remplacer par vos vraies requêtes (Eloquent).
        $kpis = [
            'total' => 412,
            'validees' => 248,
            'attente' => 126,
            'mobilises' => 97,
        ];

        return view('dashboard.index', compact('kpis'));
    }


    public function candidatures()
    {

        $candidatures = Individuelle::whereHas('user', function ($query) {
            $query->whereNotNull('firstname');
        })->get();

        return view('dashboard.candidatures', compact('candidatures'));
    }
}
