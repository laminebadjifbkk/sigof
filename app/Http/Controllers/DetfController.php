<?php

namespace App\Http\Controllers;

use App\Models\Detf;
use App\Models\Ingenieur;
use App\Models\Operateur;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

class DetfController extends Controller
{
    public function create()
    {
        $operateurs = Operateur::where('statut_agrement', 'agréé')->get();
        $ingenieurs = Ingenieur::get();
        $referentiels = Referentiel::get();

        return view('detfs.create', compact('operateurs', 'ingenieurs', 'referentiels'));
    }

    public function index(Request $request)
    {
        $query = Detf::query();

        $etat = $request->query('etat');

        // Filtre etat
        if ($etat) {
            $query->where('etat', $etat);
        }

        // Charger Detf filtrées, avec count employés (pour bouton delete)
        $detfs = $query->latest()->get();

        // Pour les cards : grouper toutes les Detf par statut
        $allDetf = Detf::latest()->get();
        $groupes = $allDetf->groupBy(fn($item) => $item->etat ?? 'Aucun');

        // Calcul des pourcentages par statut
        $total = $allDetf->count();
        $statutPourcentages = $groupes->mapWithKeys(function ($items, $statutKey) use ($total) {
            return [$statutKey => ['percent' => $total ? round($items->count() * 100 / $total, 1) : 0]];
        });

        // Vérifier si un statut est passé
        $labels = [
            'planifiee' => 'Planifiées',
            'en_cours' => 'En cours',
            'terminee' => 'Terminées',
            'annulee' => 'Annulées',
        ];

        return view('detfs.index', compact(
            'detfs',
            'groupes',
            'statutPourcentages',
            'total',
            'labels',
            'etat'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre1' => 'required|string',
            'titre2' => 'required|string',
            'operateurs_id' => 'required|exists:operateurs,id',
            'ingenieurs_id' => 'required|exists:ingenieurs,id',
            'lieu_formation' => 'required|string',
            'periode_formation' => 'required|string',
            'date_pv' => 'required|date',
            'niveau_qualification' => 'required|string',
        ]);

        $numero = 'DETF-' . date('Y') . '-' . str_pad(Detf::count() + 1, 3, '0', STR_PAD_LEFT);

        /* Detf::create($data); */

        Detf::create([
            'numero' => $numero,
            'titre1' => $request->titre1,
            'titre2' => $request->titre2,
            'pvchoixoperateur'  => $request->pv_commission,
            'lieu_de_formation'  => $request->lieu_formation,
            'periode_de_formation'  => $request->periode_formation,
            'etat'  => 'Nouveau',
            'operateurs_id'  => $request->operateurs_id,
            'ingenieurs_id'  => $request->ingenieurs_id,
            'date1'  => $request->date_pv,
            'niveau_qualification'  => $request->niveau_qualification,
        ]);

        return redirect()->route('detfs.create')->with('success', 'DETF créée avec succès !');
    }

    public function edit(Detf $detf)
    {
        $operateurs = Operateur::all();
        $ingenieurs = Ingenieur::all();
        $referentiels = Referentiel::all();

        return view('detfs.update', compact('detf', 'operateurs', 'ingenieurs', 'referentiels'));
    }

    public function update(Request $request, Detf $detf)
    {
        $request->validate([
            'titre1' => 'required|string',
            'titre2' => 'required|string',
            'operateurs_id' => 'required|exists:operateurs,id',
            'ingenieurs_id' => 'required|exists:ingenieurs,id',
            'lieu_formation' => 'required|string',
            'periode_formation' => 'required|string',
            'date_pv' => 'required|date',
            'niveau_qualification' => 'required|string',
        ]);

        /* $detf->update($request->all()); */
        $detf->update([
            'titre1' => $request->titre1,
            'titre2' => $request->titre2,
            'pvchoixoperateur'  => $request->pv_commission,
            'lieu_de_formation'  => $request->lieu_formation,
            'periode_de_formation'  => $request->periode_formation,
            'etat'  => 'Nouveau',
            'operateurs_id'  => $request->operateurs_id,
            'ingenieurs_id'  => $request->ingenieurs_id,
            'date1'  => $request->date_pv,
            'niveau_qualification'  => $request->niveau_qualification,
        ]);

        return redirect()->back()
            ->with('success', 'DETF modifié avec succès.');
    }

    public function destroy(Detf $detf)
    {
        $detf->delete();

        return back()->with('success', 'DETF supprimé avec succès.');
    }

    public function show(Request $request, $id)
    {
        $detf = Detf::findOrFail($id);

        // Filtrer les budgetItems de type 'budget'
        $budgetItems = $detf->budgetItems->filter(function ($item) {
            return $item?->label?->type === 'budget';
        });

        return view('detfs.show', compact('detf', 'budgetItems'));
    }

    public function exportWord($id)
    {
        $detf = Detf::with('budgetItems.label')->findOrFail($id);

        // Regrouper par type (fournitures, honoraires, logistique)
        $grouped = $detf->budgetItems->groupBy(function ($item) {
            return $item->label->type ?? 'Autres';
        });

        $phpWord = new PhpWord();

        // SECTION 1 : En-tête et tableau principal
        $section1 = $phpWord->addSection([
            'marginTop' => 1200,
            'marginBottom' => 1200,
            'marginLeft' => 1000,
            'marginRight' => 1000
        ]);

        // Header de la première page
        $headerFirst = $section1->addHeader();
        $headerFirst->firstPage(); // première page

        /* // Footer pour cette section
        $footer1 = $section1->addFooter();
        $footer1->firstPage(); // appliquer aussi sur première page
        $footer1->addText("ONFP - Document confidentiel", ['italic' => true, 'size' => 10], ['align' => Jc::CENTER]);
        $footer1->addPreserveText('Page {PAGE} / {NUMPAGES}', ['size' => 10], ['align' => Jc::CENTER]); */
        // Footer pour la première section
        $footer1 = $section1->addFooter();
        $footer1->firstPage();

        // Tableau pour texte + page avec bordure en haut
        $table = $footer1->addTable([
            'alignment' => Jc::CENTER,
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
            'spaceBefore' => 0,
            'spaceAfter' => 0
        ]);

        $row = $table->addRow(1); // hauteur minimale

        // Cellule texte centrée avec bordure supérieure (simule la ligne)
        $row->addCell(8000, [
            'valign' => 'top',
            'borderTopSize' => 15,        // épaisseur de la "ligne"
            'borderTopColor' => '000000' // couleur noire
        ])->addText(
            "Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN\nTel: 33 827 92 51 - Fax: 33 827 92 55\nBP: 21013 Dakar-Ponty - Email: onfp@onfp.sn",
            ['size' => 10],
            ['align' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 0]
        );

        // Cellule numéro de page à droite, aligné en haut, avec même bordure
        $row->addCell(2000, [
            'valign' => 'top',
            'borderTopSize' => 15,
            'borderTopColor' => '000000'
        ])->addPreserveText(
            'Page {PAGE} / {NUMPAGES}',
            ['size' => 10],
            ['align' => Jc::END, 'spaceBefore' => 0, 'spaceAfter' => 0]
        );

        // Style sans espace entre lignes
        $noSpacing = [
            'alignment' => Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0
        ];

        // Ligne 1
        $headerFirst->addText(
            "REPUBLIQUE DU SENEGAL",
            ['bold' => true, 'size' => 11],
            $noSpacing
        );

        // Ligne 2
        $headerFirst->addText(
            "Un Peuple - Un But - Une Foi",
            ['size' => 10],
            $noSpacing
        );

        // Ligne 3
        $headerFirst->addText(
            "MINISTERE DE L'EMPLOI ET DE LA FORMATION PROFESSIONNELLE ET TECHNIQUE",
            ['bold' => true, 'size' => 10],
            $noSpacing
        );

        // Petit espace contrôlé
        $headerFirst->addTextBreak(1);

        // Logo réduit
        $headerFirst->addImage(
            public_path('assets/img/logo-onfp.jpg'),
            [
                'width' => 250,   // plus petit
                'alignment' => Jc::CENTER
            ]
        );

        // Petit espace contrôlé
        $headerFirst->addTextBreak(1);
        // Style compact
        $centerNoSpace = [
            'alignment' => Jc::CENTER,
            'spaceAfter' => 0,
            'spaceBefore' => 0
        ];

        // Petit espace après l'entête
        $section1->addTextBreak(0);

        $section1->addText(
            "DIRECTION DE L’INGENIERIE ET DES OPERATIONS DE",
            ['bold' => true, 'size' => 14],
            $centerNoSpace
        );

        $section1->addText(
            "FORMATION",
            ['bold' => true, 'size' => 14],
            $centerNoSpace
        );

        // ==============================
        // TITRE ENCADRÉ DETF
        // ==============================

        // Petit espace avant
        $section1->addTextBreak(1);

        // Création tableau pour encadré
        $table = $section1->addTable([
            'alignment' => Jc::CENTER,
            'borderSize' => 12,
            'borderColor' => '000000',
            'cellMarginTop' => 150,    // un peu plus haut que 100
            'cellMarginBottom' => 150,
            'cellMarginLeft' => 50,
            'cellMarginRight' => 50,
            'cantSplit' => true,
            'bgColor' => null           // pas de couleur de fond pour le tableau
        ]);

        // Ajout ligne avec hauteur un peu plus grande
        $table->addRow(500); // 500 = hauteur ligne (ajuste selon besoin)

        // Cellule sans fond
        $table->addCell(9000, [
            'valign' => 'center',      // texte centré verticalement
        ])->addText(
            "DOCUMENT D’EXECUTION TECHNIQUE DE FORMATION (DETF)",
            [
                'bold' => true,
                'size' => 12
            ],
            [
                'alignment' => Jc::CENTER,
                'spaceAfter' => 0,
                'spaceBefore' => 0
            ]
        );

        $section1->addTextBreak(1);

        // Créer le tableau principal avec marge interne
        $table = $section1->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'alignment' => Jc::START,
            'cellMarginTop' => 80,
            'cellMarginBottom' => 80,
            'cellMarginLeft' => 100,   // <-- ajoute un petit retrait à gauche
            'cellMarginRight' => 100,  // optionnel pour symétrie
        ]);

        // Fonction pour une ligne simple avec marge
        function addRow($table, $label, $value)
        {
            $row = $table->addRow();

            // Cellule label avec marge
            $row->addCell(5000, ['cellMarginLeft' => 200])->addText(
                $label,
                ['bold' => true],
                ['spaceAfter' => 0]  // supprime l'espace vertical après la ligne
            );

            // Cellule valeur avec marge à gauche et interligne supprimé
            $row->addCell(9000, ['valign' => 'top', 'cellMarginLeft' => 200])
                ->addText(
                    $value,
                    [],
                    [
                        'preserveWhiteSpace' => true,
                        'spaceAfter' => 0  // supprime l'espace vertical
                    ]
                );
        }

        // Fonction pour lignes fusionnées (Opérateur) avec retrait
        function addRowWithLinesMerged($table, $label, $lines)
        {
            foreach ($lines as $index => $line) {
                $row = $table->addRow();

                if ($index === 0) {
                    $cellLabel = $row->addCell(5000, [
                        'vMerge' => 'restart',
                        'valign' => 'center',
                        'cellMarginLeft' => 200
                    ]);
                    $cellLabel->addText($label, ['bold' => true], ['align' => 'center']);
                } else {
                    $row->addCell(5000, ['vMerge' => 'continue', 'cellMarginLeft' => 200]);
                }

                $row->addCell(9000, ['valign' => 'top', 'cellMarginLeft' => 200])
                    ->addText($line, [], [
                        'preserveWhiteSpace' => true,
                        'spaceAfter' => 0
                    ]);
            }
        }

        // Préparer les infos dynamiques de l'opérateur
        $operateurLines = [
            "Nom : {$detf->operateur?->user?->operateur}",
            "Agrément ONFP : {$detf->operateur?->numero_agrement}",
            "Statut : {$detf->operateur?->types_operateur?->name}",
            "Catégorie : {$detf->operateur?->user?->categorie}",
            "Adresse : {$detf->operateur?->user?->adresse}",
            "Tel : {$detf->operateur?->user?->fixe} / {$detf->operateur?->user?->telephone}",
            "Email : {$detf->operateur?->user?->email}",
            "PVCCO du {$detf->date1->format('d/m/Y')}",
        ];

        // Préparer l'ingénieur
        $ingenieurInfo = ($detf->ingenieur?->user?->firstname ?? '') . ' ' . ($detf->ingenieur?->user?->name ?? '');

        // Remplissage du tableau principal
        addRow($table, 'Intitulé de la formation', $detf->titre1 ?? '');
        addRow($table, 'Bénéficiaires à Former', $detf->titre2 ?? '');
        addRow($table, 'Niveau ou Titre de qualification visé', $detf->niveauQualificationAffichage());
        addRowWithLinesMerged($table, 'Opérateur', $operateurLines);
        addRow($table, 'Lieu', $detf->lieu_de_formation ?? '');
        addRow($table, 'Période de la formation', $detf->periode_de_formation ?? '');
        addRow($table, 'Responsable', $ingenieurInfo);

        $totalGeneral = 0;

        $section2 = $phpWord->addSection([
            'breakType' => 'nextPage', // force le saut de page
            'marginTop' => 1200,
            'marginBottom' => 1200,
            'marginLeft' => 1000,
            'marginRight' => 1000
        ]);

        /* $footer2 = $section2->addFooter();

        // Ligne horizontale en haut du footer, sans espace après
        $footer2->addLine([
            'weight' => 1,       // épaisseur
            'width' => 500,      // largeur de la ligne
            'height' => 0,
            'align' => Jc::CENTER,
            'color' => '000000',
            'spaceBefore' => 0,
            'spaceAfter' => 0   // pas d'espace après la ligne
        ]);

        // Créer un tableau pour texte + page
        $table = $footer2->addTable([
            'alignment' => Jc::CENTER,
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
            'spaceBefore' => 0,
            'spaceAfter' => 0
        ]);

        $row = $table->addRow(0); // hauteur automatique
        // Cellule texte centré
        $row->addCell(8000)->addText(
            "Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN\n" .
                "Tel: 33 827 92 51 - Fax: 33 827 92 55\n" .
                "BP: 21013 Dakar-Ponty - Email: onfp@onfp.sn",
            ['size' => 10],
            ['align' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 0]
        );

        // Cellule numéro de page aligné à droite
        $row->addCell(2000)->addPreserveText(
            'Page {PAGE} / {NUMPAGES}',
            ['size' => 10],
            ['align' => Jc::END, 'spaceBefore' => 0, 'spaceAfter' => 0]
        ); */

        $footer2 = $section2->addFooter();

        // Tableau pour texte + page avec bordure en haut
        $table = $footer2->addTable([
            'alignment' => Jc::CENTER,
            'cellMarginTop' => 0,
            'cellMarginBottom' => 0,
            'spaceBefore' => 0,
            'spaceAfter' => 0
        ]);

        $row = $table->addRow(1); // hauteur minimale

        // Cellule texte centrée avec bordure supérieure (simule la ligne)
        $row->addCell(8000, [
            'valign' => 'top',
            'borderTopSize' => 15,        // épaisseur de la "ligne"
            'borderTopColor' => '000000' // couleur noire
        ])->addText(
            "Cité Sipres 1, Lot 2 - 2 voies liberté 6 extension VDN\nTel: 33 827 92 51 - Fax: 33 827 92 55\nBP: 21013 Dakar-Ponty - Email: onfp@onfp.sn",
            ['size' => 10],
            ['align' => Jc::CENTER, 'spaceBefore' => 0, 'spaceAfter' => 0]
        );

        // Cellule numéro de page à droite, aligné en haut, avec même bordure
        $row->addCell(2000, [
            'valign' => 'top',
            'borderTopSize' => 15,
            'borderTopColor' => '000000'
        ])->addPreserveText(
            'Page {PAGE} / {NUMPAGES}',
            ['size' => 10],
            ['align' => Jc::END, 'spaceBefore' => 0, 'spaceAfter' => 0]
        );

        // ==============================
        // BOUCLE PAR TYPE (4 TABLEAUX)
        // ==============================
        /*  $totalTypes = count($grouped);
        $sousTotalIndex = 1;

        foreach ($grouped as $type => $items) {

            $section2->addTitle(strtoupper($type), 2);

            $table = $section2->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'alignment' => Jc::START,
                'cellMarginTop' => 0,
                'cellMarginBottom' => 0,
                'cellMarginLeft' => 50,
                'cellMarginRight' => 50,
            ]);

            $textFont = [
                'size' => 11
            ];

            $textStyle = [
                'spaceBefore' => 0,
                'spaceAfter' => 0,
                'lineHeight' => 1
            ];

            // Entête
            $table->addRow();
            $table->addCell(3000, ['vAlign' => 'center'])->addText('Libellé', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(1500, ['vAlign' => 'center'])->addText('Unité', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(1500, ['vAlign' => 'center'])->addText('Quantité', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(2000, ['vAlign' => 'center'])->addText('Prix Unitaire', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(2000, ['vAlign' => 'center'])->addText('Montant', ['bold' => true, 'size' => 11], $textStyle);

            $sousTotal = 0;

            foreach ($items as $item) {

                $table->addRow();
                $table->addCell(3000, ['vAlign' => 'center'])
                    ->addText($item->label->libelle, $textFont, $textStyle);

                $table->addCell(1500, ['vAlign' => 'center'])
                    ->addText($item->unite, $textFont, $textStyle);

                $table->addCell(1500, ['vAlign' => 'center'])
                    ->addText($item->quantite, $textFont, $textStyle);

                $table->addCell(2000, ['vAlign' => 'center'])
                    ->addText(number_format($item->prix_unitaire, 0, ',', ' '), $textFont, $textStyle);

                $table->addCell(2000, ['vAlign' => 'center'])
                    ->addText(number_format($item->montant, 0, ',', ' '), $textFont, $textStyle);

                $sousTotal += $item->montant;
            }

            // Déterminer le label
            $label = ($sousTotalIndex == $totalTypes)
                ? 'TOTAL GENERAL'
                : 'Sous-total ' . $sousTotalIndex;

            // Ligne Sous-total / Total général
            $table->addRow();

            $table->addCell(8000, [
                'gridSpan' => 4,
                'vAlign' => 'center'
            ])->addText($label, ['bold' => true, 'size' => 11], $textStyle);

            $table->addCell(2000, ['vAlign' => 'center'])
                ->addText(number_format($sousTotal, 0, ',', ' '), ['bold' => true, 'size' => 11], $textStyle);

            $section2->addTextBreak(1);

            // $totalGeneral += $sousTotal; 

            $sousTotalIndex++;
        } */

        // ==============================
        // TOTAL GENERAL
        // ==============================
        /* $section2->addText(
            "TOTAL GENERAL : " . number_format($totalGeneral, 0, ',', ' ') . " FCFA",
            ['bold' => true, 'size' => 14]
        ); */


        $totalTypes = count($grouped);
        $sousTotalIndex = 1;

        foreach ($grouped as $type => $items) {

            $isLastGroup = ($sousTotalIndex == $totalTypes);

            $section2->addTitle(strtoupper($type), 2);

            $table = $section2->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'alignment' => Jc::START,
                'cellMarginTop' => 0,
                'cellMarginBottom' => 0,
                'cellMarginLeft' => 50,
                'cellMarginRight' => 50,
            ]);

            $textFont = ['size' => 11];
            $textStyle = ['spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1];

            // Entête
            $table->addRow();
            if ($isLastGroup) {
                $table->addCell(2000, ['vAlign' => 'center'])->addText('Rubriques', ['bold' => true, 'size' => 11], $textStyle);
            }
            $table->addCell(3000, ['vAlign' => 'center'])->addText('Libellé', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(1500, ['vAlign' => 'center'])->addText('Unité', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(1500, ['vAlign' => 'center'])->addText('Quantité', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(2000, ['vAlign' => 'center'])->addText('Prix Unitaire', ['bold' => true, 'size' => 11], $textStyle);
            $table->addCell(2000, ['vAlign' => 'center'])->addText('Montant', ['bold' => true, 'size' => 11], $textStyle);

            $sousTotal = 0;

            foreach ($items as $item) {
                $table->addRow();
                if ($isLastGroup) {
                    $table->addCell(2000, ['vAlign' => 'center'])->addText($item->rubrique ?? '-', $textFont, $textStyle);
                }
                $table->addCell(3000, ['vAlign' => 'center'])->addText($item->label->libelle, $textFont, $textStyle);
                $table->addCell(1500, ['vAlign' => 'center'])->addText($item->unite, $textFont, $textStyle);
                $table->addCell(1500, ['vAlign' => 'center'])->addText($item->quantite, $textFont, $textStyle);
                $table->addCell(2000, ['vAlign' => 'center'])->addText(number_format($item->prix_unitaire, 0, ',', ' '), $textFont, $textStyle);
                $table->addCell(2000, ['vAlign' => 'center'])->addText(number_format($item->montant, 0, ',', ' '), $textFont, $textStyle);

                $sousTotal += $item->montant;
            }

            // Ligne Sous-total / TOTAL GENERAL
            $table->addRow();

            // Ajuster le gridSpan pour TOTAL GENERAL pour qu’il corresponde au nombre de colonnes
            $gridSpan = $isLastGroup ? 5 : 4; // Si dernière table, Rubriques déjà comptée dans entête et items
            $table->addCell(8000, ['gridSpan' => $gridSpan, 'vAlign' => 'center'])
                ->addText($isLastGroup ? 'TOTAL GENERAL' : 'Sous-total ' . $sousTotalIndex, ['bold' => true, 'size' => 11], $textStyle);

            $table->addCell(2000, ['vAlign' => 'center'])
                ->addText(number_format($sousTotal, 0, ',', ' '), ['bold' => true, 'size' => 11], $textStyle);

            // Ne PAS ajouter de cellule supplémentaire pour Rubriques sur TOTAL GENERAL
            $section2->addTextBreak(1);

            $sousTotalIndex++;
        }

        // ==============================
        // TELECHARGEMENT
        // ==============================
        $fileName = "DETF_{$detf->numero}.docx";

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Expires: 0');

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save("php://output");
        exit;
    }
}
