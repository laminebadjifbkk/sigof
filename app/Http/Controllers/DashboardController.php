<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Individuelle;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // KPIs
        $total     = Candidature::count();
        $validees  = Candidature::where('statut', 'validee')->count();
        $attente   = Candidature::where('statut', 'en_attente')->count();
        $rejete   = Candidature::where('statut', 'rejetee')->count();
        $mobilises = Candidature::where('statut', 'validee')->count(); // à ajuster si "mobilisé" a une définition différente de "validée"

        $kpis = [
            'total'     => $total,
            'validees'  => $validees,
            'attente'   => $attente,
            'rejete'   => $rejete,
            'mobilises' => $mobilises,
            // Candidatures créées durant les 7 derniers jours
            'nouvelles_semaine' => Candidature::where('created_at', '>=', now()->subDays(7))->count(),
            'aujourdhui' => Candidature::where('created_at', '>=', now()->subDays(1))->count(),
        ];

        // Répartition par langue de spécialisation (top 5)
        $languageStats = Candidature::selectRaw('langue_specialisation_id, count(*) as total')
            ->with('langueSpecialisation:id,nom')
            ->groupBy('langue_specialisation_id')
            ->orderByDesc('total')
            ->take(5)
            ->get()
            ->map(function ($item) use ($total) {
                return [
                    'nom'        => $item->langueSpecialisation->nom ?? 'Inconnue',
                    'total'      => $item->total,
                    'pourcentage' => $total > 0 ? round(($item->total / $total) * 100) : 0,
                ];
            });

        // Pour dimensionner les barres du graphique (hauteur relative au max)
        $maxLangueTotal = $languageStats->max('total') ?: 1;

        // Répartition des statuts pour le donut (en pourcentage du total)
        $rejetees = Candidature::where('statut', 'rejetee')->count();

        $statutStats = [
            'validees_pct' => $total > 0 ? round(($validees / $total) * 100) : 0,
            'attente_pct'  => $total > 0 ? round(($attente / $total) * 100) : 0,
            'rejetees_pct' => $total > 0 ? round(($rejetees / $total) * 100) : 0,
        ];

        $candidatures = Candidature::with('user', 'langueSpecialisation')
            ->whereHas('user', function ($query) {
                $query->whereNotNull('firstname');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.dashboard', compact('kpis', 'languageStats', 'maxLangueTotal', 'statutStats', 'candidatures'));
    }
}
