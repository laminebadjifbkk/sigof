<?php

namespace App\Http\Controllers;

use App\Models\Commissionagrement;
use App\Models\Commissionmembre;
use App\Models\Historiqueagrement;
use App\Models\Operateur;
use App\Models\Operateurmodule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CommissionagrementController extends Controller
{
    public function index()
    {
        $commissionagrements = Commissionagrement::get();
        /* $commissionmembres = Commissionmembre::get(); */
        $commissionmembres          = Commissionmembre::where('statut', 'like', 'Président%')->get();
        $commissionmembreSecretaire = Commissionmembre::where('statut', 'Secrétaire')->get();

        return view('operateurs.commissionagrements.index', compact('commissionagrements', 'commissionmembres', 'commissionmembreSecretaire'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'commission'     => 'required|string|unique:commissionagrements,commission,except,id',
            "date_agrement"  => "nullable|date|max:10|min:10|date_format:Y-m-d",
            'date_ouverture' => "required|date|size:10|date_format:Y-m-d",
            'date_fermeture' => "required|date|size:10|date_format:Y-m-d",
            'session'        => 'required|string',
            'annee'          => 'required|string',
            'description'    => 'nullable|string',
            'lieu'           => 'nullable|string',

        ]);

        if (! empty($request->input('date_agrement'))) {
            $date_agrement = $request->input('date_agrement');
        } else {
            $date_agrement = null;
        }

        $commissionagrement = new Commissionagrement([

            'commission'     => $request->input('commission'),
            'session'        => $request->input('session'),
            'description'    => $request->input('description'),
            'lieu'           => $request->input('lieu'),
            'annee'          => $request->input('annee'),
            'date_ouverture' => $request->input('date_ouverture'),
            'date_fermeture' => $request->input('date_fermeture'),
            'date'           => $date_agrement,

        ]);

        $commissionagrement->save();
        Alert::success('Effectuée !', 'Commission ajoutée avec succès');

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $commissionagrement = Commissionagrement::findOrFail($id);

        $request->validate([
            'commission' => ["required", "string", "unique:commissionagrements,commission,{$id}"],
            'session'          => 'required|string',
            /* 'date'             => "nullable|date|size:10|date_format:Y-m-d", */
            'date_ouverture'   => "required|date|size:10|date_format:Y-m-d",
            'date_fermeture'   => "required|date|size:10|date_format:Y-m-d",
            'debut_commission' => "nullable|date|size:10|date_format:Y-m-d",
            'fin_commission'   => "nullable|date|size:10|date_format:Y-m-d",
            'lieu'             => 'nullable|string',
            'statut'           => 'nullable|string',
            'annee'            => 'required|string',

        ]);

        /* if (! empty($request->input('date'))) {
            $date = $request->input('date');
        } else {
            $date = null;
        } */
        if (! empty($request->input('date_ouverture'))) {
            $date_ouverture = $request->input('date_ouverture');
        } else {
            $date_ouverture = null;
        }
        if (! empty($request->input('date_fermeture'))) {
            $date_fermeture = $request->input('date_fermeture');
        } else {
            $date_fermeture = null;
        }
        if (! empty($request->input('debut_commission'))) {
            $debut_commission = $request->input('debut_commission');
        } else {
            $debut_commission = null;
        }
        if (! empty($request->input('fin_commission'))) {
            $fin_commission = $request->input('fin_commission');
        } else {
            $fin_commission = null;
        }

        $commissionagrement->update([
            'commission'       => $request->input('commission'),
            'session'          => $request->input('session'),
            'description'      => $request->input('description'),
            'lieu'             => $request->input('lieu'),
            'statut'           => $request->input('statut'),
            'annee'            => $request->input('annee'),
            'date_ouverture'   => $date_ouverture,
            'date_fermeture'   => $date_fermeture,
            'debut_commission' => $debut_commission,
            'fin_commission'   => $fin_commission,
            'chef_id'          => $request->input('membre'),
            'secretaire_id'    => $request->input('secretaire'),
            'recommandations'  => $request->input('recommendations'),
            'date'             => $fin_commission,

        ]);

        $commissionagrement->save();

        Alert::success('Succès !', 'Commission modifiée avec succès');

        return redirect()->back();
    }

    public function show($id)
    {
        $commissionagrement = Commissionagrement::findOrFail($id);

        /* $operateurs = Operateur::where('commissionagrements_id', $id)
            ->get(); */

        $groupesStatutAgrement = $commissionagrement?->operateurs->groupBy(function ($item) {
            return $item->statut_agrement ?? 'Aucun statut agrement';
        });

        /* $operateur_count = $operateurs->count();

        if (! empty($operateur_count) && $operateur_count > 50) {
            $decoupage = ($operateur_count / 50);
        } else {
            $decoupage = null;
        } */

        /* $operateurs_agreer_count = Operateur::where('commissionagrements_id', $id)
            ->where('statut_agrement', 'agréé')
            ->count();

        $operateurs_reserve_count = Operateur::where('commissionagrements_id', $id)
            ->where('statut_agrement', 'sous réserve')
            ->count();

        $operateurs_rejeter_count = Operateur::where('commissionagrements_id', $id)
            ->where('statut_agrement', 'Rejeté')
            ->count(); */

        /*  $operateurAgrement = DB::table('operateurs')
        ->where('commissionagrements_id', $commissionagrement->id)
        ->pluck('id', 'id')
        ->all();

        $operateurAgrementCheck = DB::table('operateurs')
        ->where('commissionagrements_id', '!=', null)
        ->where('commissionagrements_id', '!=', $id)
        ->pluck('id', 'id')
        ->all(); */

        return view(
            'operateurs.commissionagrements.show',
            compact(
                'commissionagrement',
                /* 'operateurs', */
                'groupesStatutAgrement',
                /* 'decoupage',
                'operateurs_agreer_count',
                'operateurs_reserve_count',
                'operateurs_rejeter_count' */
            )
        );
    }

    public function destroy($id)
    {
        $commissionagrement = Commissionagrement::findOrFail($id);

        if ($commissionagrement->operateurs()->exists()) {
            Alert::warning('Attention !', 'Impossible de supprimer cette commission');
        } else {
            $commissionagrement->delete();
            Alert::success('Succès !', 'Commission supprimée avec succès');
        }

        return redirect()->back();
    }

    public function givecommisionagrement(Request $request, $idcommissionagrement)
    {
        $request->validate([
            'operateurs' => ['required', 'array'],
        ]);

        $operateursSelectionnes = $request->operateurs;

        // Tous les opérateurs actuellement associés à cette commission
        $commission        = Commissionagrement::findOrFail($idcommissionagrement);
        $operateursActuels = $commission->operateurs->pluck('id')->toArray();

        // ➤ Attacher ou mettre à jour ceux qui sont sélectionnés
        foreach ($operateursSelectionnes as $operateurId) {
            $operateur = Operateur::findOrFail($operateurId);

            $operateur->commissionagrements()->syncWithoutDetaching([$idcommissionagrement]);

            $historiqueExiste = Historiqueagrement::where([
                ['operateurs_id', '=', $operateur->id],
                ['commissionagrements_id', '=', $idcommissionagrement],
                ['statut', '=', 'En commission'],
            ])->exists();

            if (! $historiqueExiste) {
                Historiqueagrement::create([
                    'operateurs_id'          => $operateur->id,
                    'commissionagrements_id' => $idcommissionagrement,
                    'statut'                 => 'En commission',
                    'validated_id'           => Auth::id(),
                ]);
            }
        }

        Alert::success('Succès !', 'La liste des opérateurs de la commission a été mise à jour.');

        return redirect()->back();
    }

    public function givecommisionagrement_ex(Request $request, $idcommissionagrement)
    {
        /* $request->validate([
            'operateurs' => ['required'],
        ]); */

        $request->validate([
            'operateurs' => ['required', 'array'],
        ]);

        /* $operateur_deja_retenus = Operateur::where('commissionagrements_id', $idcommissionagrement)->get(); */

        /*   foreach ($operateur_deja_retenus as $key => $value) {

        $value->update([
        "commissionagrements_id"        =>  null,
        ]);

        $value->save();
        } */
        foreach ($request->operateurs as $operateurId) {
            $operateur = Operateur::findOrFail($operateurId);

            /* $operateur->update([
                "commissionagrements_id" => $idcommissionagrement,
                "statut_agrement"        => 'En commission',
            ]); */

            // Ajoute l'opérateur à la commission sans détacher les précédentes
            $operateur->commissionagrements()->syncWithoutDetaching([$idcommissionagrement]);

            /* $historiqueagrement = new Historiqueagrement([
                'operateurs_id'          => $operateur->id,
                'commissionagrements_id' => $idcommissionagrement,
                'statut'                 => 'En commission',
                'validated_id'           => Auth::user()->id,

            ]);

            $historiqueagrement->save(); */

            // Enregistre un historique si non existant pour éviter les doublons
            $historiqueExiste = Historiqueagrement::where([
                ['operateurs_id', '=', $operateur?->id],
                ['commissionagrements_id', '=', $idcommissionagrement],
                ['statut', '=', 'En commission'],
            ])->exists();

            if (! $historiqueExiste) {
                Historiqueagrement::create([
                    'operateurs_id'          => $operateur?->id,
                    'commissionagrements_id' => $idcommissionagrement,
                    'statut'                 => 'En commission',
                    'validated_id'           => Auth::id(),
                ]);
            }
        }

        Alert::success('Succès !', 'Opérateur(s) ajouté(s) en commission avec succès');

        return redirect()->back();
    }

    public function addopCommission($id)
    {

        $commissionagrement = Commissionagrement::findOrFail($id);

        /* $statutsVoulus = ['Conforme', 'Extension', 'Renouvellement', 'Nouveau', 'À corriger', 'agréé', 'sous réserve', 'Rejeté', 'Retiré']; */
        $statutsVoulus = ['Nouveau', 'Conforme'];

        /* $operateurs = Operateur::whereNull('commissionagrements_id')
            ->whereIn('statut_agrement', $statutsVoulus)
            ->get(); */

        $operateurs = Operateur::whereIn('statut_agrement', $statutsVoulus)
            ->get();

        /* $operateurs = Operateur::whereIn('statut_agrement', $statutsVoulus)
            ->get(); */

        $operateurAgrement = DB::table('operateurs')
            ->where('commissionagrements_id', $commissionagrement->id)
            ->pluck('id', 'id')
            ->all();

        $operateurAgrementCheck = DB::table('operateurs')
            ->where('commissionagrements_id', '!=', null)
            ->where('commissionagrements_id', '!=', $id)
            ->pluck('id', 'id')
            ->all();

        $operateursSelectionnes = $commissionagrement->operateurs->pluck('id')->toArray();

        return view(
            'operateurs.commissionagrements.add_op_commsions',
            compact(
                'commissionagrement',
                'operateurs',
                'operateurAgrement',
                'operateursSelectionnes',
                'operateurAgrementCheck'
            )
        );
    }

    public function showAgreer($id)
    {
        $commissionagrement = Commissionagrement::findOrFail($id);

        $operateurs = Operateur::where('commissionagrements_id', $commissionagrement->id)
            ->where('statut_agrement', 'agréé')
            ->get();

        $operateurmodules = Operateurmodule::join('operateurs', 'operateurs.id', 'operateurmodules.operateurs_id')
            ->select('operateurmodules.*')
            ->where('operateurs.statut_agrement', "agréé")
            ->where('operateurs.commissionagrements_id', $commissionagrement->id)
            ->where('operateurmodules.statut', "agréé")
            ->get();

        $count_operateurmodules_distinct = Operateurmodule::join('operateurs', 'operateurs.id', 'operateurmodules.operateurs_id')
            ->select('operateurmodules.*')
            ->where('operateurs.statut_agrement', "agréé")
            ->where('operateurs.commissionagrements_id', $commissionagrement->id)
            ->where('operateurmodules.statut', "agréé")
            ->distinct('module')
            ->count('module');

        return view(
            'operateurs.agrements.show_agreer',
            compact(
                'operateurs',
                'commissionagrement',
                'operateurmodules',
                'count_operateurmodules_distinct'
            )
        );
    }

    public function showReserve($id)
    {
        $commissionagrement = Commissionagrement::findOrFail($id);

        $operateurs = Operateur::where('commissionagrements_id', $commissionagrement->id)
            ->where('statut_agrement', 'sous réserve')
            ->get();

        $operateurmodules = Operateurmodule::join('operateurs', 'operateurs.id', 'operateurmodules.operateurs_id')
            ->select('operateurmodules.*')
            ->where('operateurs.statut_agrement', "sous réserve")
            ->where('operateurs.commissionagrements_id', $commissionagrement->id)
            ->where('operateurmodules.statut', "sous réserve")
            ->get();

        return view(
            'operateurs.agrements.show_reserve',
            compact(
                'operateurs',
                'commissionagrement',
                'operateurmodules'
            )
        );
    }

    public function showRejeter($id)
    {
        $commissionagrement = Commissionagrement::findOrFail($id);

        $operateurs = Operateur::where('commissionagrements_id', $commissionagrement->id)
            ->where('statut_agrement', 'Rejeté')
            ->get();

        return view(
            'operateurs.agrements.show_rejeter',
            compact(
                'operateurs',
                'commissionagrement'
            )
        );
    }

    public function jury($id)
    {
        $commissionagrement = Commissionagrement::findOrFail($id);

        $membres = Commissionmembre::get();

        $membreJury = $commissionagrement->commissionmembres->pluck('id', 'id')->all();

        return view(
            'operateurs.commissionagrements.add_membres_commsions',
            compact('commissionagrement', 'membres', 'membreJury')
        );
    }

    public function addMembreJury(Request $request, $id)
    {
        $request->validate([
            'membres' => ['required'],

        ]);

        $commissionagrement = Commissionagrement::findOrFail($id);

        $commissionagrement->commissionmembres()->sync($request->membres);

        Alert::success('Bravo !', 'Membres ajoutés avec succès');

        return redirect()->back();
    }

    /*     public function exportPV(Commissionagrement $commissionagrement)
    {
        // Ton code PDF ici

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $dompdf->loadHtml(view('operateurs.commissionagrements.pvCommission', compact(
            'commissionagrement',
        )));

        // (Optional) Setup the paper size and orientation (portrait ou landscape)
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        $name = 'PV commission ' . $commissionagrement->commission . '.pdf';

        // Output the generated PDF to Browser
        $dompdf->stream($name, ['Attachment' => false]);
        return redirect()->back();
    } */

    public function exportPV(string $statut, Commissionagrement $commissionagrement)
    {
        $operateurs = $commissionagrement->operateurs()
            ->get();

        $operateurs_renouvellements = $commissionagrement->operateurs()
            ->where('operateurs.type_demande', 'Renouvellement')
            ->get();

        $operateurs_renouvellements_agreer = $commissionagrement->operateurs()
            ->where('operateurs.statut_agrement', 'agréé')
            ->where('operateurs.type_demande', 'Renouvellement')
            ->get();

        $operateurs_renouvellements_sr = $commissionagrement->operateurs()
            ->where('operateurs.statut_agrement', 'sous réserve')
            ->where('operateurs.type_demande', 'Renouvellement')
            ->get();

        $operateurs_renouvellements_rejet = $commissionagrement->operateurs()
            ->where('operateurs.statut_agrement', 'rejeté')
            ->where('operateurs.type_demande', 'Renouvellement')
            ->get();

        $operateurs_nouvelles_agreer = $commissionagrement->operateurs()
            ->where('operateurs.statut_agrement', 'agréé')
            ->whereIn('type_demande', ['Nouvelle', 'Nouveau'])
            ->get();

        $operateurs_nouvelles_sr = $commissionagrement->operateurs()
            ->where('operateurs.statut_agrement', 'sous réserve')
            ->whereIn('type_demande', ['Nouvelle', 'Nouveau'])
            ->get();

        $operateurs_nouvelles_rejet = $commissionagrement->operateurs()
            ->where('operateurs.statut_agrement', 'rejeté')
            ->whereIn('type_demande', ['Nouvelle', 'Nouveau'])
            ->get();

        $countOperateurs             = $operateurs->count();
        $countNouvelles_agreer       = $operateurs_nouvelles_agreer->count();
        $countNouvelles_sr           = $operateurs_nouvelles_sr->count();
        $countNouvelles_rejet        = $operateurs_nouvelles_rejet->count();
        $countRenouvellements        = $operateurs_renouvellements->count();
        $countRenouvellements_agreer = $operateurs_renouvellements_agreer->count();
        $countRenouvellements_sr     = $operateurs_renouvellements_sr->count();
        $countRenouvellements_rejet  = $operateurs_renouvellements_rejet->count();

        // Chemin du logo
        $logoPath = public_path('assets/img/logo-onfp.jpg');

        // Vérifie si le fichier existe et encode en base64
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;

        if ($commissionagrement?->debut_commission && $commissionagrement?->fin_commission) {
            $start = $commissionagrement->debut_commission->copy();
            $end   = $commissionagrement->fin_commission->copy();

            // Générer tous les jours entre les deux dates inclusivement
            $joursCollection = collect();
            while ($start->lte($end)) {
                $joursCollection->push($start->format('d'));
                $start->addDay();
            }

            if ($joursCollection->count() > 1) {
                $lastDay = $joursCollection->pop();
                $jours   = $joursCollection->implode(', ') . ' et ' . $lastDay;
            } else {
                $jours = $joursCollection->first(); // un seul jour
            }

            // Mois et année selon la date de début (ou fin)
            $moisAnnee = $commissionagrement->debut_commission->translatedFormat('F Y');
        } else {
            $jours     = '...................';
            $moisAnnee = '............' . now()->format('Y');
        }

        if (empty($commissionagrement->chef->prenom)) {

            Alert::error('Erreur !', 'Des informations manquent sur la commission.');

            return redirect()->back();
        }

        $pdf = Pdf::loadView('operateurs.commissionagrements.pvCommission', [
            'commissionagrement'          => $commissionagrement,
            'operateurs'                  => $operateurs,
            'statut'                      => $statut,
            'logoBase64'                  => $logoBase64,
            'jours'                       => $jours,
            'moisAnnee'                   => $moisAnnee,
            'countOperateurs'             => $countOperateurs,
            'countRenouvellements'        => $countRenouvellements,
            'countRenouvellements_agreer' => $countRenouvellements_agreer,
            'operateurs_renouvellements'  => $operateurs_renouvellements,
            'countNouvelles_agreer'       => $countNouvelles_agreer,
            'countRenouvellements_sr'     => $countRenouvellements_sr,
            'countNouvelles_sr'           => $countNouvelles_sr,
            'countRenouvellements_rejet'  => $countRenouvellements_rejet,
            'countNouvelles_rejet'        => $countNouvelles_rejet,
        ]);

        /* return $pdf->download('PV_' . $commissionagrement->commission . '_' . ucfirst($statut) . '.pdf'); */
        return $pdf->stream('PV_' . $commissionagrement->commission . '.pdf');
    }
}
