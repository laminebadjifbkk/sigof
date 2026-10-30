<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationInscription;
use App\Models\Inscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\Services\BrevoMailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
            "Présidence, Primature et Gouvernement" => [
                "Présidence de la République",
                "Primature",
                "Gouvernement",
                "Ministère de l’Emploi et de la Formation professionnelle et Technique",
                "Ministère de l’Economie des Finances et du Plan",
                "Ministère de l’Intégration Africaine, des Affaires étrangères et des sénégalais de l’extérieur",
                "Ministère du Travail et de la Fonction publique",
                "Ministère de la jeunesse et des sports",
                "Ministère de l’Agriculture de la Souveraineté alimentaire et de l’Elevage",
                "Ministère de l’Energie et du Pétrole",
                "Ministère de l’Urbanisme, du logement et de l’hygiène publique",
                "Ministère des Forces Armées",
                "Ministère de l’Intérieur et de la Sécurité publique",
                "Ministère de la Justice, Garde des Sceaux",
                "Ministère de la Famille, de l’Action sociale et des Solidarités",
                "Ministère de l’Enseignement supérieur, de la Recherche et de l’Innovation",
                "Ministère de l’Industrie et du Commerce",
                "Ministère de l’Hydraulique et de l’Assainissement",
                "Ministère de l’Éducation Nationale",
                "Ministère des Infrastructures",
                "Ministère de l’Urbanisme, des Collectivités Territoriales et de l’Aménagement des Territoires",
                "Ministère de la Communication et des Relations avec les Institutions",
                "Ministère de la Microfinance et de l’Economie Sociale et Solidaire",
                "Ministère des Télécommunications et du Numérique",
                "Ministère de la Fonction Publique, du Travail et de la Réforme du Service Public",
                "Ministère de la Culture, de l’Artisanat et du Tourisme",
                "Ministère de l’Environnement et de la Transition Ecologique",
                "Ministère des Transports terrestres et aériens",
                "Ministère des Pêches et de l’Economie maritime",
                "Ministère de la Santé et de l’Hygiène Publique",
            ],
            "Corps diplomatique" => [
                "Ambassade de la Côte d’Ivoire",
                "Ambassade de la République de Guinée Conakry",
                "Ambassade de la Guinée Bissau",
                "Ambassade du Mali",
                "Ambassade de la République Islamique de la Mauritanie",
                "Ambassade de France",
                "Ambassade d’Espagne",
                "Ambassade de la Belgique",
                "Ambassade d’Allemagne",
                "Ambassade du Canada",
                "Ambassade du Maroc",
                "Ambassade des Etats Unis d’Amérique",
                "Délégation de l’Union Africaine",
                "Délégation de l’UEMOA",
                "Ambassade des Émirats arabes unis à Dakar",
                "Ambassade du Qatar à Dakar",
                "Ambassade du Koweït à Dakar",
                "Délégation à l’Union Européenne au Sénégal",
                "Délégation générale de la Wallonie Bruxelles",
            ],
            "Partenaires au développement" => [
                "Agence Française de Développement (AFD)",
                "Direction générale des relations avec l’Afrique (Îles Canaries)",
                "Chambre officielle de Commerce d’Espagne à Dakar",
                "Agence de coopération Belge (ENABEL)",
                "Agence de coopération Allemande (KFW)",
                "Agence de coopération Allemande (GIZ)",
                "Agence de coopération Luxembourgeoise (Luxdév)",
                "Agence Andalouse de Coopération Internationale pour le Développement (AACID)",
                "Agence de coopération Turque (TIKA)",
                "Agence Italienne pour la Coopération au Développement (AICS)",
                "Agence japonaise de coopération Internationale (JICA)",
                "Centre d’études et de recherches sur les qualifications (CEREQ)",
                "Centre canadien de Coopération Internationale (CECI)",
                "Bureau International du Travail (BIT)",
            ],
            "Institutions publiques" => [
                "Agence de Promotion et de l’Investissement et des Grands Travaux (APIX-SA)",
                "Fonds de Financement de la Formation professionnelle et Technique (3FPT)",
                "Direction du Financement et du Partenariat avec les Organisations/Ministère de l’Agriculture, de la Souveraineté Alimentaire et de l’Elevage (MASAE)",
                "Direction générale du Cadre de vie et de l’Hygiène publique/Ministère de l’Urbanisme, du Logement et de l’Hygiène Publique (MULHP)",
                "Direction générale de la Coopération, des Financements Extérieurs et du Développement du Secteur Privé (DGCFEDSP)",
                "Direction générale de la Formation professionnelle et technique (DGFPT)",
                "Comité d’organisation des Jeux Olympiques de la Jeunesse (COJOJ 2026)",
                "Agence d’Exécution des Travaux Routiers (AGEROUTE)",
                "Société Africaine de Raffinage (SAR)",
                "Loterie nationale Sénégalaise (LONASE)",
                "Société Nationale de Gestion Intégrée des Déchets (SONAGED)",
                "Projet de Promotion de la Gestion Intégrée et de l’Economie des Déchets solides au Sénégal (PROMOGED)",
                "Agence nationale pour la Promotion de l’Emploi des Jeunes (ANPEJ)",
                "Centre National des Qualifications Professionnelles (CNQP)",
                "Programme de Formation Ecole-Entreprise (PF2E)",
                "Agence nationale de la Maison de l’Outil (ANAMO)",
                "Agence de Développement et d’Encadrement des Petites et Moyennes Entreprises (ADEPME)",
                "Caisse des Dépôts et de Consignations (CDC)",
                "Agence Sénégalaise d’Electrification Rurale (ASER)",
                "Agence pour l’Economie et la Maîtrise de l’Energie (AEME)",
                "Fonds de Développement des Transports Terrestres (FDTT)",
                "Fonds d’entretien routier autonome (FERA)",
                "Fonds de promotion de l’industrie cinématographique et audiovisuelle (FOPICA)",
                "Délégation Générale à l’Entreprenariat Rapide des Femmes et des Jeunes (DER/FJ)",
            ],
            "Projets et Programmes" => [
                "Projet d’Appui à l’Insertion des Jeunes Ruraux Agri-preneurs (Agri-Jeunes Tekki Ndawñi)",
                "Projet Formation, dignité, inclusion et innovation : les clés d’une croissance économique durable au Sénégal (VIS)",
                "Projet Emplois Verts DELTA, Saloum",
                "Programme des Domaines Agricoles Communautaires (PRODAC)",
                "Fonds d’appui à la Stabilisation (FONSTAB)",
                "Projet PAPSEN/PAIS",
            ],
            "Patronat, Entreprises et Chambres consulaires" => [
                "Banque pour le Commerce et l’industrie (BCI)",
                "Sonatel Orange",
                "Sénégal Gold Opération (SGO)",
                "SEN BOTO SA",
                "SOCOCIM",
                "Ciments de l’Afrique (CIMAF)",
                "Compagnie Sucrière Sénégalaise (CSS)",
                "Ciments du Sahel",
                "Confédération Nationale des Employeurs (CNES)",
                "Conseil National du Patronat (CNP)",
                "Union nationale des Chambres de Commerce, d’Industrie et d’Agriculture (UNCCIAS)",
                "Union nationale des Chambres de Métiers du Sénégal (UNCM)",
                "Chambre de Commerce, d’Industrie et d’Agriculture de Dakar (CCIAD)",
                "Chambre de Commerce, d’Industrie et d’Agriculture de Kaolack (CCIAK)",
            ],
            "Anciens DG et anciens collègues" => [
                "Abdou Aziz DIA",
                "Momar GUEYE",
                "Sanoussi DIAKITE",
                "Souleymane SOUMARE",
                "Mamadou Mounirou LY",
                "Mouhamadou Lamine Bara LO",
                "Aissatou DIAKHOUMPA",
                "Lissa DIOP BA",
                "Birago NDIAYE",
                "Ibrahima NDIAYE",
                "Virginie CORREA",
                "Fatou MAR",
                "Abdoulaye Ba",
                "Maguette Seck",
                "Mariétou Thiam",
                "Ousmane Sène",
                "Birago Diop",
                "Adama Dramé",
                "Mme Diagne",
                "Abdou DIOUF",
                "Sékou Badji",
                "Daouda SECK",
                "Bassirou KANE",
                "Karfa DIOUF",
                "Ousmane Coumba Woumy DIOP",
                "Samba FAYE",
                "Dior Mademba TOURE",
                "Henry FRANCISCO",
                "Oumar BA",
                "Ibrahima LO",
                "Gorgui LO",
                "Mamadou NDIAYE",
                "Souleymane LO",
                "Ouly TOURE",
                "Sidou SANE",
                "Ndeye Soukeyna FALL",
            ],
            "Opérateurs ONFP et autres Structures de Formation" => [
                "Cadre des opérateurs de l’ONFP",
                "Ecole Supérieure d’Economie Appliquée (ESEA)",
                "Institut Supérieur de formation à Distance (ISFAD)",
                "Institut Supérieur de Développement Local (ISDL)",
                "Institut Africain de Management (IAM)",
                "Institut Supérieur de Management (ISM)",
                "GALIMATECH",
                "SIMPLON",
                "SENEDITION",
                "KEBE MOURIDOULAHI",
                "DIABONG Corp",
                "DOT Home Institut",
                "Performe",
            ],
            "Invités spéciaux du DG" => [
                "Ministre Conseiller Aldiouma SOW",
                "FAISE - Khoureychi THIAM",
                "FONGIP - Ndeye Fatou Guédel MBODJ",
            ],
        ];

        return view('inscriptioncontact.inscription-40ans', compact('structures'));
    }

    /* public function store(Request $request)
    {
        $validated = $request->validate([
            'structure' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $structure = trim(mb_strtolower($value));

                    $multiAllowed = [
                        mb_strtolower("Ministère de l'Emploi et de la Formation professionnelle et Technique (MEFPT)"),
                        mb_strtolower("Député"),
                        mb_strtolower("Société Nationale des Eaux du Sénégal (SONES)"),
                        mb_strtolower("PAMECAS"),
                    ];

                    $count = Inscription::whereRaw('LOWER(structure) = ?', [$structure])->count();

                    if (in_array($structure, $multiAllowed)) {
                        if ($count >= 3) {
                            $fail("La structure « $value » a déjà atteint la limite maximale de 3 inscriptions.");
                        }
                    } else {
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

        $inscription = Inscription::create($validated);
        Mail::to($inscription->email)->send(new ConfirmationInscription($inscription));
        return redirect()->route('inscriptioncontact.merci');
    } */

/*
public function store(Request $request, BrevoMailer $brevo)
{
    $validated = $request->validate([
        // Structure en saisie libre : on ne peut plus se fier à une liste de noms exacts.
        'structure'   => 'required|string|max:255',
        'nom'         => 'required|string|max:255',
        'fonction'    => 'required|string|max:255',
        'telephone'   => 'required|string|max:50',
        'email'       => 'required|email|max:255',
        'commentaire' => 'nullable|string|max:255',
    ]);

    $inscription = Inscription::create($validated);

    try {
        $html = (new ConfirmationInscription($inscription))->render();

        $brevo->sendEmail(
            ['email' => $inscription->email, 'name' => $inscription->nom],
            "Confirmation de votre inscription – 40 ans de l'ONFP",
            $html
        );
    } catch (\Throwable $e) {
        Log::warning('Confirmation 40 ans non envoyée', [
            'inscription_id' => $inscription->id,
            'error'          => $e->getMessage(),
        ]);
    }

    return redirect()->route('inscriptioncontact.merci');
} */


public function store(Request $request, BrevoMailer $brevo)
{
    $validated = $request->validate([
        // Structure en saisie libre : on ne peut plus se fier à une liste de noms exacts.
        'structure'       => 'required|string|max:255',
        'nom'             => 'required|string|max:255',
        'fonction'        => 'required|string|max:255',
        /* 'telephone'   => 'required|string|max:50|unique:inscriptions,telephone',
        'email'       => 'required|email|max:255|unique:inscriptions,email', */
        'telephone'       => 'required|string|max:50',
        'email'           => 'required|email|max:255',
        'commentaire'     => 'nullable|string|max:255',
        // Jeton généré par le formulaire au moment de l'envoi
        'recaptcha_token' => 'required|string',
    ], [
        'recaptcha_token.required' => 'La vérification de sécurité a échoué. Veuillez réessayer.',
    ]);

    // Vérification reCAPTCHA côté serveur
    if (! $this->recaptchaPasses($validated['recaptcha_token'], $request->ip())) {
        return back()
            ->withInput()
            ->withErrors(['recaptcha_token' => 'La vérification de sécurité a échoué. Veuillez réessayer.']);
    }

    // Le jeton n'est pas une colonne de la table : on le retire avant l'enregistrement
    unset($validated['recaptcha_token']);

    // Enregistrer en base
    $inscription = Inscription::create($validated);

    // Envoi de la confirmation via votre service Brevo existant
    try {
        $html = (new ConfirmationInscription($inscription))->render();

        $brevo->sendEmail(
            ['email' => $inscription->email, 'name' => $inscription->nom],
            "Confirmation de votre inscription – 40 ans de l'ONFP",
            $html
        );
    } catch (\Throwable $e) {
        // L'inscription est déjà enregistrée : un échec d'envoi ne doit pas la bloquer.
        // (BrevoMailer a déjà journalisé l'erreur de l'API.)
        Log::warning('Confirmation 40 ans non envoyée', [
            'inscription_id' => $inscription->id,
            'error'          => $e->getMessage(),
        ]);
    }

    return redirect()->route('inscriptioncontact.merci');
}

/**
 * Vérifie le jeton reCAPTCHA v3 auprès de Google.
 * Si Google est injoignable, on laisse passer (et on journalise) pour ne pas bloquer les inscriptions.
 */
private function recaptchaPasses(string $token, ?string $ip): bool
{
    try {
        $result = Http::asForm()
            ->timeout(5)
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ])
            ->json();
    } catch (\Throwable $e) {
        Log::warning('reCAPTCHA injoignable, inscription acceptée', ['error' => $e->getMessage()]);
        return true;
    }

    return ($result['success'] ?? false)
        && ($result['score'] ?? 0) >= 0.5
        && ($result['action'] ?? '') === 'submit';
}

    public function merci()
    {
        /* return view('inscriptioncontact.merci'); */
        return view('inscriptioncontact.merci-40ans');
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
                "Delphy",
                "Institut de Formation pratique aux Métiers (IFPM)",
                "Projet de Formation professionnelle et d’Insertion (PFPI)",
                "Centre national de formation de la fonction publique locale (CNFPL)",
                "PAMECAS",
                "Ministère de l'Education nationale (MEN)",
                "Rencontre Insertion Orientation sur la Formation Professionnelle et Technique (RIO FPT)",
                "Ministère de l’Energie du Pétrole et des Mines (MEPM)",
                "Groupe IAM",
                "WINZ SÉNÉGAL",
                "ONG TOSTAN",
                "TNT Groupe",
                "SMD Équipements",
                "Dynamic conseils",
                "Conseil d’Administration de l’ONFP",
                "Couture excellence"
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
            /* 'structure' => 'required|string', */
            'email'     => 'required|email',
            'telephone' => 'required|string',
        ]);

        $email = $request->email;
        $telephone = $request->telephone;

        /* $exists = Inscription::where('structure', $request->structure)
            ->where('email', $email)
            ->where('telephone', $telephone)
            ->first(); */

        $exists = Inscription::where('email', $email)
            ->where('telephone', $telephone)
            ->first();

        if ($exists) {
    Alert::success('Succès', 'Vous avez déjà confirmé votre participation.');
    return redirect()->route('inscriptioncontact.merci');
}

Alert::error('Erreur', 'Aucune inscription trouvée pour ces informations.');
return redirect()->back();
    }

    public function confirmation($id)
    {
        $inscription = Inscription::findOrFail($id);

        return view('inscriptioncontact.confirmation', compact('inscription'));
    }
}
