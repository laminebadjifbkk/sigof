<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationInscription;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class InscriptioncontactController extends Controller
{
    /* public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\App\Forms\InscriptioncontactForm::class, [
            'method' => 'POST',
            'url'    => route('inscriptioncontact.store'),
        ]);

        return view('inscriptioncontact.create', compact('form'));
    } */

    public function create()
    {
        $structures = [
            "Ministères et Directions"                  => [
                "Ministère de l'Emploi et de la Formation professionnelle et Technique (MEFPT)",
                "Direction du Financement et du Partenariat avec les Organisations (MASAE)",
                "Direction générale du Cadre de vie et de l’Hygiène publique (MULHP)",
                "DGCFEDSP / Ministère de l’Economie du Plan et de la Coopération (MEPC)",
                "Direction de la Planification, des Etudes et du Suivi-Evaluation (MEPM)",
                "Direction générale de la Formation professionnelle et technique (DGFPT)",
                "Office national de Formation professionnelle (ONFP)",
            ],
            "Ambassades et Représentations étrangères"  => [
                "Ambassade des Émirats arabes unis à Dakar",
                "Ambassade du Qatar à Dakar",
                "Ambassade du Koweït à Dakar",
                "Délégation à l’Union Européenne au Sénégal (EEAS)",
                "Ambassade du Canada",
                "Ambassade du Maroc",
                "Délégation générale des Îles Canaries",
                "Chambre officielle de Commerce d’Espagne à Dakar",
                "Délégation générale de la Wallonie Bruxelles",
            ],
            "Agences de coopération internationale"     => [
                "Agence Française de Développement (AFD)",
                "Agence de coopération Belge (ENABEL)",
                "Agence de coopération Allemande (KFW)",
                "Agence de coopération Allemande (GIZ)",
                "Agence de coopération Luxembourgeoise (LuxDev)",
                "Agence Andalouse de Coopération Internationale pour le Développement (AACID)",
                "Agence de coopération Turque (TIKA)",
                "Agence Italienne pour la Coopération au Développement (AICS)",
                "Agence japonaise de coopération Internationale (JICA)",
            ],
            "Agences et Fonds nationaux"                => [
                "Fonds de Financement de la Formation professionnelle et Technique (3FPT)",
                "Expertise Sénégal pour les Systèmes de Formation professionnelle",
                "Agence régionale de Développement (ARD) Dakar",
                "Agence nationale pour la Promotion de l’Emploi des Jeunes (ANPEJ)",
                "Centre National des Qualifications Professionnelles (CNQP)",
                "Programme de Formation Ecole-Entreprise (PF2E)",
                "Agence nationale de la Maison de l’Outil (ANAMO)",
                "Agence de Développement et d'Encadrement des PME (ADEPME)",
                "Agence de Promotion des Investissements et des Grands Travaux (APIX-SA)",
                "Caisse des Dépôts et de Consignations (CDC)",
                "Agence Sénégalaise d’Electrification Rurale (ASER)",
                "Agence pour l’Economie et la Maitrise de l’Energie (AEME)",
                "Fonds de Développement des Transports Terrestres (FDTT)",
                "Fonds d'entretien routier autonome (FERA)",
                "Fonds de promotion de l’industrie cinématographique et audiovisuelle (FOPICA)",
                "Délégation Générale à l'Entreprenariat Rapide des Femmes et des Jeunes (DER/FJ)",
                "Port Autonome de Dakar (PAD)",
                "Dubai Port (DP World) Sénégal",
                "Conseil sénégalais des Chargeurs (COSEC)",
                "Société Africaine de Raffinage (SAR)",
                "Groupe SONATEL Orange",
                "Société Nationale des Eaux du Sénégal (SONES)",
                "Sénégal Numérique (SENUM-SA)",
                "Conseil exécutif des Transports urbains durables (CETUD)",
                "Office des Forages Ruraux (OFOR)",
                "Société Nationale de Gestion Intégrée des Déchets (SONAGED)",
                "Télédiffusion Sénégal (TDS)",
                "Agence d'Exécution des Travaux Routiers (AGEROUTE)",
                "Agence Sénégalaise de Promotion Touristique (ASPT)",
                "Office national de l’Assainissement du Sénégal (ONAS)",
            ],
            "Projets et Programmes"                     => [
                "Agri-Jeunes Tekki Ndawñi",
                "Projet Formation, dignité, inclusion et innovation (VIS)",
                "Projet Emplois Verts DELTA, Saloum",
                "Comité d’organisation des Jeux Olympiques de la Jeunesse (JOJ 2026)",
                "Programme des Domaines Agricoles Communautaires (PRODAC)",
                "Fonds d’appui à la Stabilisation (FONSTAB)",
                "Projet PAPSEN/PAIS",
            ],
            "Entreprises privées"                       => [
                "Sénégal Gold Opération (SGO)",
                "SEN BOTO SA",
                "SOCOCIM",
                "Ciments de l’Afrique (CIMAF)",
                "Compagnie Sucrière Sénégalaise (CSS)",
                "Ciments du Sahel",
                "Dangote Cement",
                "Axa Assurances Sénégal",
            ],
            "Collectivités et Organisations nationales" => [
                "Ville de Dakar",
                "Commune de Khombole",
                "Commune de Sandiara",
                "Confédération nationale des Employeurs du Sénégal (CNES)",
                "Conseil national du Patronat (CNP)",
                "Union nationale des Chambres de Commerce, d’Industrie et d’Agriculture (UNCCIAS)",
                "Union nationale des Chambres de Métiers du Sénégal (UNCM)",
                "Union des Elus locaux du Sénégal",
                "Cadre des opérateurs de formation ONFP",
                "Diaspora / Bonnes volontés",
            ],
            "Organisations, ONG et Institutions"        => [
                "Club des Investisseurs Sénégalais",
                "Complexe Cheikh Ahmadoul Khadim pour l'Education et la Formation",
                "Fondation Lonase",
                "Table ronde des Établissements de Formation",
                "ONG Pratical Action",
                "Nouvelles Editions Numériques Africaines (NENA)",
                "Institut Supérieur de formation à Distance (ISFAD)",
                "Bureau International du Travail (BIT)",
                "Associates in Research And Education For Developement (ARED)",
                "Centre d'études et de recherches sur les qualifications (CEREQ)",
                "Centre canadien de Coopération Internationale (CECI)",
                "Ecole Supérieure d’Economie Appliquée (ESEA)",
            ],
            "AUTRES"        => [
                "Association des Commerçants et Industriels du Sénégal(ACIS)",
                "Fédération nationale des Professionnels de l’Habillement (FENAPH)",
                "Le représentant du contrôle financier de la présidence de la République, Membre du Conseil d’Administration de l’ONFP",
                "Le représentant du Ministère des Finances et du Budget au Conseil d’Administration de l’ONFP",
                "Fond International de Développement Agricole (FIDA)",
                "City Banque",
                "Commune Golf Sud",
                "Député",
                "Delphy"
            ],
        ];
        // Pas besoin de FormBuilder, on affiche directement la vue
        return view('inscriptioncontact.create', compact('structures'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            /* 'structure'   => 'required|string|max:255|unique:inscriptions,structure', */
            /* 'structure' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $count = Inscription::where('structure', $value)->count();
                    if ($count >= 3) {
                        $fail("La structure « $value » a déjà atteint la limite maximale de 3 inscriptions.");
                    }
                },
            ], */
            'structure' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Normalisation pour éviter les erreurs de casse
                    $structure = trim(mb_strtolower($value));

                    // Structures autorisées à avoir jusqu'à 3 inscriptions
                    $multiAllowed = [
                        mb_strtolower("Ministère de l'Emploi et de la Formation professionnelle et Technique (MEFPT)"),
                        mb_strtolower("Député"),
                    ];

                    $count = Inscription::whereRaw('LOWER(structure) = ?', [$structure])->count();

                    if (in_array($structure, $multiAllowed)) {
                        // Ces structures peuvent avoir jusqu'à 3 inscriptions
                        if ($count >= 3) {
                            $fail("La structure « $value » a déjà atteint la limite maximale de 3 inscriptions.");
                        }
                    } else {
                        // Toutes les autres : uniquement une inscription autorisée
                        if ($count >= 1) {
                            $fail("La structure « $value » ne peut avoir qu’une seule inscription.");
                        }
                    }
                },
            ],
            'nom'         => 'required|string|max:255',
            'fonction'    => 'required|string|max:255',
            'telephone'   => 'required|string|max:50|unique:inscriptions,telephone',
            'email'       => 'required|email|max:255|unique:inscriptions,email',
            'commentaire' => 'nullable|string|max:255',
        ]);

        // Enregistrer en base
        $inscription = Inscription::create($validated);

        // Envoyer l'email
        Mail::to($inscription->email)->send(new ConfirmationInscription($inscription));

        // Si tu ne stockes pas, tu peux par exemple juste afficher un message :
        /* return back()->with('success', 'Votre confirmation a été enregistrée. Merci !'); */

        // Ici tu peux envoyer un mail ou traiter les données si nécessaire
        return redirect()->route('inscriptioncontact.merci');
    }

    public function merci()
    {
        return view('inscriptioncontact.merci');
    }

    public function index()
    {
        $inscriptions = Inscription::orderBy('created_at', 'desc')->get();
        $structures   = [
            "Ministères et Directions"                  => [
                "Ministère de l'Emploi et de la Formation professionnelle et Technique (MEFPT)",
                "Direction du Financement et du Partenariat avec les Organisations (MASAE)",
                "Direction générale du Cadre de vie et de l’Hygiène publique (MULHP)",
                "DGCFEDSP / Ministère de l’Economie du Plan et de la Coopération (MEPC)",
                "Direction de la Planification, des Etudes et du Suivi-Evaluation (MEPM)",
                "Direction générale de la Formation professionnelle et technique (DGFPT)",
                "Office national de Formation professionnelle (ONFP)",
            ],
            "Ambassades et Représentations étrangères"  => [
                "Ambassade des Émirats arabes unis à Dakar",
                "Ambassade du Qatar à Dakar",
                "Ambassade du Koweït à Dakar",
                "Délégation à l’Union Européenne au Sénégal (EEAS)",
                "Ambassade du Canada",
                "Ambassade du Maroc",
                "Délégation générale des Îles Canaries",
                "Chambre officielle de Commerce d’Espagne à Dakar",
                "Délégation générale de la Wallonie Bruxelles",
            ],
            "Agences de coopération internationale"     => [
                "Agence Française de Développement (AFD)",
                "Agence de coopération Belge (ENABEL)",
                "Agence de coopération Allemande (KFW)",
                "Agence de coopération Allemande (GIZ)",
                "Agence de coopération Luxembourgeoise (LuxDev)",
                "Agence Andalouse de Coopération Internationale pour le Développement (AACID)",
                "Agence de coopération Turque (TIKA)",
                "Agence Italienne pour la Coopération au Développement (AICS)",
                "Agence japonaise de coopération Internationale (JICA)",
            ],
            "Agences et Fonds nationaux"                => [
                "Fonds de Financement de la Formation professionnelle et Technique (3FPT)",
                "Expertise Sénégal pour les Systèmes de Formation professionnelle",
                "Agence régionale de Développement (ARD) Dakar",
                "Agence nationale pour la Promotion de l’Emploi des Jeunes (ANPEJ)",
                "Centre National des Qualifications Professionnelles (CNQP)",
                "Programme de Formation Ecole-Entreprise (PF2E)",
                "Agence nationale de la Maison de l’Outil (ANAMO)",
                "Agence de Développement et d'Encadrement des PME (ADEPME)",
                "Agence de Promotion des Investissements et des Grands Travaux (APIX-SA)",
                "Caisse des Dépôts et de Consignations (CDC)",
                "Agence Sénégalaise d’Electrification Rurale (ASER)",
                "Agence pour l’Economie et la Maitrise de l’Energie (AEME)",
                "Fonds de Développement des Transports Terrestres (FDTT)",
                "Fonds d'entretien routier autonome (FERA)",
                "Fonds de promotion de l’industrie cinématographique et audiovisuelle (FOPICA)",
                "Délégation Générale à l'Entreprenariat Rapide des Femmes et des Jeunes (DER/FJ)",
                "Port Autonome de Dakar (PAD)",
                "Dubai Port (DP World) Sénégal",
                "Conseil sénégalais des Chargeurs (COSEC)",
                "Société Africaine de Raffinage (SAR)",
                "Groupe SONATEL Orange",
                "Société Nationale des Eaux du Sénégal (SONES)",
                "Sénégal Numérique (SENUM-SA)",
                "Conseil exécutif des Transports urbains durables (CETUD)",
                "Office des Forages Ruraux (OFOR)",
                "Société Nationale de Gestion Intégrée des Déchets (SONAGED)",
                "Télédiffusion Sénégal (TDS)",
                "Agence d'Exécution des Travaux Routiers (AGEROUTE)",
                "Agence Sénégalaise de Promotion Touristique (ASPT)",
                "Office national de l’Assainissement du Sénégal (ONAS)",
            ],
            "Projets et Programmes"                     => [
                "Agri-Jeunes Tekki Ndawñi",
                "Projet Formation, dignité, inclusion et innovation (VIS)",
                "Projet Emplois Verts DELTA, Saloum",
                "Comité d’organisation des Jeux Olympiques de la Jeunesse (JOJ 2026)",
                "Programme des Domaines Agricoles Communautaires (PRODAC)",
                "Fonds d’appui à la Stabilisation (FONSTAB)",
                "Projet PAPSEN/PAIS",
            ],
            "Entreprises privées"                       => [
                "Sénégal Gold Opération (SGO)",
                "SEN BOTO SA",
                "SOCOCIM",
                "Ciments de l’Afrique (CIMAF)",
                "Compagnie Sucrière Sénégalaise (CSS)",
                "Ciments du Sahel",
                "Dangote Cement",
                "Axa Assurances Sénégal",
            ],
            "Collectivités et Organisations nationales" => [
                "Ville de Dakar",
                "Commune de Khombole",
                "Commune de Sandiara",
                "Confédération nationale des Employeurs du Sénégal (CNES)",
                "Conseil national du Patronat (CNP)",
                "Union nationale des Chambres de Commerce, d’Industrie et d’Agriculture (UNCCIAS)",
                "Union nationale des Chambres de Métiers du Sénégal (UNCM)",
                "Union des Elus locaux du Sénégal",
                "Cadre des opérateurs de formation ONFP",
                "Diaspora / Bonnes volontés",
            ],
            "Organisations, ONG et Institutions"        => [
                "Club des Investisseurs Sénégalais",
                "Complexe Cheikh Ahmadoul Khadim pour l'Education et la Formation",
                "Fondation Lonase",
                "Table ronde des Établissements de Formation",
                "ONG Pratical Action",
                "Nouvelles Editions Numériques Africaines (NENA)",
                "Institut Supérieur de formation à Distance (ISFAD)",
                "Bureau International du Travail (BIT)",
                "Associates in Research And Education For Developement (ARED)",
                "Centre d'études et de recherches sur les qualifications (CEREQ)",
                "Centre canadien de Coopération Internationale (CECI)",
                "Ecole Supérieure d’Economie Appliquée (ESEA)",
            ],
            "AUTRES"        => [
                "Association des Commerçants et Industriels du Sénégal(ACIS)",
                "Fédération nationale des Professionnels de l’Habillement (FENAPH)",
                "Le représentant du contrôle financier de la présidence de la République, Membre du Conseil d’Administration de l’ONFP",
                "Le représentant du Ministère des Finances et du Budget au Conseil d’Administration de l’ONFP",
                "Fond International de Développement Agricole (FIDA)",
                "City Banque",
                "Commune Golf Sud",
                "Député",
                "Delphy"
            ],
        ];
        return view('inscriptioncontact.index', compact('inscriptions', 'structures'));
    }

    public function show(Inscription $inscription)
    {
        return view('inscriptioncontact.show', compact('inscription'));
    }

    public function showAjax($id)
    {
        try {
            $inscription = Inscription::find($id);

            if (! $inscription) {
                return response()->json(['error' => 'Inscription non trouvée'], 404);
            }

            return response()->json([
                'structure'   => $inscription->structure ?? '',
                'nom'         => $inscription->nom ?? '',
                'telephone'   => $inscription->telephone ?? '',
                'email'       => $inscription->email ?? '',
                'fonction'    => $inscription->fonction ?? '',
                'commentaire' => $inscription->commentaire ?? '',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur : ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $inscription = Inscription::findOrFail($id);

        $request->validate([
            'structure'   => 'required|string|max:255',
            'nom'         => 'required|string|max:255',
            'fonction'    => 'required|string|max:255',
            'telephone'   => 'required|string|max:50',
            'email'       => 'required|email|max:255',
            'commentaire' => 'nullable|string',
        ]);

        $inscription->update($request->all());

        Alert::success('Succès', 'Les informations ont été mises à jour avec succès.');
        return redirect()->back();
    }

    public function destroy($id)
    {

        $inscription = Inscription::findOrFail($id);

        $inscription->delete();

        Alert::success('Succès', 'Inscription supprimée avec succès.');

        return redirect()->route('inscriptioncontacts.index');
    }

    public function check(Request $request)
    {
        $request->validate([
            'structure' => 'required|string',
            'email'     => 'required|email',
            'telephone' => 'required|string',
        ]);

        $email = $request->email;

        $exists = Inscription::where('structure', $request->structure)
            ->where('email', $request->email)
            ->where('telephone', $request->telephone)
            ->first();

        if ($exists) {
            Alert::success('Succès', 'Vous avez déjà confirmé votre participation.');
            return redirect()->route('inscription.confirmation', $exists->id);
        } else {
            Alert::error('Erreur', 'Aucune inscription trouvée pour ces informations.');
            return redirect()->back();
        }
    }

    public function confirmation($id)
    {
        $inscription = Inscription::findOrFail($id);

        return view('inscriptioncontact.confirmation', compact('inscription'));
    }
}
