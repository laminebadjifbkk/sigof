<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\FormationsTraducteur;
use App\Models\LanguesSpecialisation;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class RapportCandidatureController extends Controller
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


    public function export(Request $request)
    {
        $query = Candidature::with(['user', 'langueSpecialisation']);

        // Filtres optionnels via query string
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('langue_specialisation_id')) {
            $query->where('langue_specialisation_id', $request->langue_specialisation_id);
        }

        if ($request->filled('zone')) {
            $query->where('zone', $request->zone);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        $candidatures = $query->latest()->get();

        $filename = 'candidatures_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = [
            'ID',
            'Nom complet',
            'E-mail',
            'Téléphone',
            'Langue (LV1)',
            'Niveau français',
            'Diplôme',
            'Zone',
            'Délégation souhaitée',
            'Statut',
            'Date de candidature',
        ];

        $callback = function () use ($candidatures, $columns) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 pour un affichage correct des accents dans Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, $columns, ';');

            foreach ($candidatures as $c) {
                fputcsv($file, [
                    $c->id,
                    trim($c->user->firstname . ' ' . $c->user->name),
                    $c->user->email,
                    $c->user->telephone,
                    $c->langueSpecialisation->nom ?? '—',
                    $c->niveau_francais,
                    $c->diplome,
                    $c->zone_label ?? $c->zone,
                    $c->delegation_souhaitee ?? '—',
                    $c->statut_label ?? $c->statut,
                    $c->created_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
