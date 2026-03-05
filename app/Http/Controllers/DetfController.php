<?php

namespace App\Http\Controllers;

use App\Models\Detf;
use App\Models\Ingenieur;
use App\Models\Operateur;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

class DetfController extends Controller
{
    public function create()
    {
        $operateurs = Operateur::where('statut_agrement', 'agréé')->get();
        $ingenieurs = Ingenieur::get();
        return view('detfs.create', compact('operateurs', 'ingenieurs'));
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
        $data = $request->validate([
            'titre1' => 'nullable|string',
            'titre2' => 'nullable|string',
            'date1' => 'nullable|date',
            'operateurs_id' => 'nullable|integer',
        ]);

        $numero = 'DETF-' . date('Y') . '-' . str_pad(Detf::count() + 1, 3, '0', STR_PAD_LEFT);

        /* Detf::create($data); */

        Detf::create([
            'numero' => $numero,
            'titre1' => $request->titre1,
            'titre2' => $request->titre2,
            'date1'  => $request->date1,
            'etat'  => 'Nouveau',
            'operateurs_id'  => $request->operateurs_id,
            'ingenieurs_id'  => $request->ingenieurs_id,
        ]);

        return redirect()->route('detfs.create')->with('success', 'DETF créée avec succès !');
    }

    public function edit(Detf $detf)
    {
        $operateurs = Operateur::all();
        $ingenieurs = Ingenieur::all();

        return view('detfs.update', compact('detf', 'operateurs', 'ingenieurs'));
    }

    public function update(Request $request, Detf $detf)
    {
        $detf->update($request->all());

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

        return view('detfs.show', compact('detf'));
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

        // Footer pour cette section
        $footer1 = $section1->addFooter();
        $footer1->firstPage(); // appliquer aussi sur première page
        $footer1->addText("SIGOF - Document confidentiel", ['italic' => true, 'size' => 10], ['align' => Jc::CENTER]);
        $footer1->addPreserveText('Page {PAGE} / {NUMPAGES}', ['size' => 10], ['align' => Jc::CENTER]);

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
            "PVCCO du ",
        ];

        // Préparer l'ingénieur
        $ingenieurInfo = ($detf->ingenieur?->user?->firstname ?? '') . ' ' . ($detf->ingenieur?->user?->name ?? '');

        // Remplissage du tableau principal
        addRow($table, 'Intitulé de la formation', $detf->titre1 ?? '');
        addRow($table, 'Bénéficiaires à former', $detf->titre2 ?? '');
        addRow($table, 'Niveau ou Titre de qualification visé', $detf->titre2 ?? '');
        addRowWithLinesMerged($table, 'Opérateur', $operateurLines);
        addRow($table, 'Lieu', $detf->lieu ?? '');
        addRow($table, 'Période de la formation', $detf->periode ?? '');
        addRow($table, 'Responsable', $ingenieurInfo);

        $totalGeneral = 0;

        $section2 = $phpWord->addSection([
            'breakType' => 'nextPage', // force le saut de page
            'marginTop' => 1200,
            'marginBottom' => 1200,
            'marginLeft' => 1000,
            'marginRight' => 1000
        ]);

        // Footer pour cette nouvelle section
        $footer2 = $section2->addFooter();
        $footer2->addText("SIGOF - Document confidentiel", ['italic' => true, 'size' => 10], ['align' => Jc::CENTER]);
        $footer2->addPreserveText('Page {PAGE} / {NUMPAGES}', ['size' => 10], ['align' => Jc::CENTER]);

        // ==============================
        // BOUCLE PAR TYPE (3 TABLEAUX)
        // ==============================
        foreach ($grouped as $type => $items) {

            $section2->addTitle(strtoupper($type), 2);

            $table = $section2->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'alignment' => Jc::START,
                'cellMarginTop' => 15,     // réduire l'espace en haut
                'cellMarginBottom' => 15,  // réduire l'espace en bas
                'cellMarginLeft' => 100,   // petite marge à gauche
                'cellMarginRight' => 100,  // optionnel, pour symétrie
            ]);

            // Entête
            $table->addRow();
            $table->addCell(3000)->addText('Libellé', ['bold' => true]);
            $table->addCell(1500)->addText('Unité', ['bold' => true]);
            $table->addCell(1500)->addText('Quantité', ['bold' => true]);
            $table->addCell(2000)->addText('Prix Unitaire', ['bold' => true]);
            $table->addCell(2000)->addText('Montant', ['bold' => true]);

            $sousTotal = 0;

            foreach ($items as $item) {

                $table->addRow();
                $table->addCell(3000)->addText($item->label->libelle);
                $table->addCell(1500)->addText($item->unite);
                $table->addCell(1500)->addText($item->quantite);
                $table->addCell(2000)->addText(number_format($item->prix_unitaire, 0, ',', ' '));
                $table->addCell(2000)->addText(number_format($item->montant, 0, ',', ' '));

                $sousTotal += $item->montant;
            }

            // Sous-total par type
            $table->addRow();
            $table->addCell(8000, ['gridSpan' => 4])
                ->addText('Sous-total', ['bold' => true]);
            $table->addCell(2000)
                ->addText(number_format($sousTotal, 0, ',', ' '), ['bold' => true]);

            $section2->addTextBreak(1);

            $totalGeneral += $sousTotal;
        }

        // ==============================
        // TOTAL GENERAL
        // ==============================
        $section2->addText(
            "TOTAL GENERAL : " . number_format($totalGeneral, 0, ',', ' ') . " FCFA",
            ['bold' => true, 'size' => 14]
        );

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
