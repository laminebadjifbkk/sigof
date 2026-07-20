<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\FormationsTraducteur;
use App\Models\LanguesSpecialisation;

class RapportController extends Controller
{
    public function index()
    {
        // KPIs globaux
        $totalCandidatures = Candidature::count();
        $totalValidees = Candidature::whereIn('statut', ['validee', 'conforme'])->count();
        $totalRejetees = Candidature::whereIn('statut', ['rejetee', 'non_conforme'])->count();
        $totalEnAttente = Candidature::whereIn('statut', ['nouvelle', 'nouveau', 'en_attente'])->count();
        $tauxValidation = $totalCandidatures > 0 ? round(($totalValidees / $totalCandidatures) * 100, 1) : 0;

        // Répartition par statut
        $parStatut = Candidature::selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut');

        // Répartition par langue (candidatures vs postes disponibles)
        $parLangue = LanguesSpecialisation::withCount('candidatures')
            ->orderByDesc('candidatures_count')
            ->get();

        // Répartition par zone
        $parZone = Candidature::selectRaw('zone, count(*) as total')
            ->groupBy('zone')
            ->pluck('total', 'zone');

        // Suivi des formations
        $parStatutFormation = FormationsTraducteur::selectRaw('statut_formation, count(*) as total')
            ->groupBy('statut_formation')
            ->pluck('total', 'statut_formation');

        $totalFormations = FormationsTraducteur::count();
        $totalReussis = FormationsTraducteur::where('resultat_evaluation', 'reussi')->count();
        $totalEchoues = FormationsTraducteur::where('resultat_evaluation', 'echoue')->count();
        $totalRattrapage = FormationsTraducteur::where('resultat_evaluation', 'rattrapage')->count();

        // Candidatures des 30 derniers jours (tendance)
        $candidaturesRecentes = Candidature::where('created_at', '>=', now()->subDays(30))->count();

        return view('candidatures.rapports', compact(
            'totalCandidatures',
            'totalValidees',
            'totalRejetees',
            'totalEnAttente',
            'tauxValidation',
            'parStatut',
            'parLangue',
            'parZone',
            'parStatutFormation',
            'totalFormations',
            'totalReussis',
            'totalEchoues',
            'totalRattrapage',
            'candidaturesRecentes'
        ));
    }
}
