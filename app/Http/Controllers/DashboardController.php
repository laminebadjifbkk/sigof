<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Individuelle;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Exemple de données à remplacer par vos vraies requêtes (Eloquent).
        $kpis = [
            'total' => 412,
            'validees' => 248,
            'attente' => 126,
            'mobilises' => 97,
        ];

        $candidatures = Candidature::whereHas('user', function ($query) {
            $query->whereNotNull('firstname');
        })
            ->latest() // équivaut à orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.dashboard', compact('kpis', 'candidatures'));
    }


    /* Déplacer dans CandidatureController */
 /*    public function candidatures()
    {

        $candidatures = Individuelle::whereHas('user', function ($query) {
            $query->whereNotNull('firstname');
        })->limit(100)->get();

        return view('dashboard.candidatures', compact('candidatures'));
    } */
}
