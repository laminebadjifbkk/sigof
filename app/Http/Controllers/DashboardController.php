<?php

namespace App\Http\Controllers;

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
}
