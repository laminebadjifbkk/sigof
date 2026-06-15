<?php

namespace App\Http\Controllers;

use App\Jobs\GenererAttestationsReussiteJob;
use App\Models\Direction;
use App\Models\Formation;
use App\Models\Individuelle;
use App\Models\Listecollective;
use App\Models\Validationcollective;
use App\Models\Validationformation;
use App\Models\Validationindividuelle;
use App\Services\NumeroAttestationService;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use NumberToWords\NumberToWords;
use RealRashid\SweetAlert\Facades\Alert;

class AttestationController extends Controller
{

    // Attestation de participation

    public function telechargerAttestationParticipation(int $formationId, int $individuelleId)
    {
        $formation = Formation::findOrFail($formationId);
        $individuelle = Individuelle::findOrFail($individuelleId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $title         = 'Attestation de participation ' . $formation->name;
        $now = \Carbon\Carbon::now();
        /* $membres_jury  = explode(";", $formation->membres_jury);
        $count_membres = count($membres_jury); */
        // ✅ Génération QR PNG sans imagick avec endroid/qr-code
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        /* $qrContent = "Formation : {$formation?->name}\n" .
            "Code : {$formation?->code}\n" .
            "Module : {$moduleName}\n" .
            "Date : " . $formation?->date_debut?->format('d/m/Y') . " au " . $formation?->date_fin?->format('d/m/Y'); */

        $nameRes = $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name;

        $validated_by = new Validationformation([
            'validated_id'  =>  Auth::user()->id,
            'action'        => "generer",
            'motif'        =>  $nameRes,
            'formations_id' => $formationId,
        ]);

        $validated_by->save();

        Validationindividuelle::create([
            'validated_id'     => Auth::user()->id,
            'action'           => 'attestation',
            'motif'           => 'Votre attestation/titre a été généré',
            'individuelles_id' => $individuelle->id,
        ]);

        $individuelle->update([
            'attestation' => 'generer', // ou la valeur souhaitée
        ]);

        // Remplacer votre bloc $qrContent par :
        $payload = implode('|', [
            $formation->id,
            $individuelle->id,
            $individuelle->user->id,
            $formation->date_fin?->format('Y-m-d'),
        ]);

        $secret    = config('app.attestation_secret');
        $signature = hash_hmac('sha256', $payload, $secret);
        $token     = base64_encode($payload . '::' . $signature);

        $qrContent = route('attestation.verifier', ['token' => $token]);
        //FIN

        $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
        $writer       = new PngWriter();
        $result       = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $html = View::make('formations.individuelles.attestation_participation', compact(
            'formation',
            'title',
            'individuelle',
            'moduleName',
            'now',
            'qrCodeBase64'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $name = 'Attestation_Particpation_' . $individuelle->user->firstname . '_' . $individuelle->user->name . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    }

    public function verifier(Request $request)
    {
        try {
            $decoded = base64_decode($request->query('token'));
            [$payload, $signature] = explode('::', $decoded, 2);

            // Vérifier la signature
            $secret   = config('app.attestation_secret');
            $expected = hash_hmac('sha256', $payload, $secret);

            if (!hash_equals($expected, $signature)) {
                return view('attestations.invalide'); // ❌ Falsifié
            }

            [$formationId, $individuelleId, $userId, $dateFin] = explode('|', $payload);

            $formation   = Formation::findOrFail($formationId);
            $individuelle = Individuelle::with('user')
                ->where('id', $individuelleId)
                ->where('formations_id', $formationId)
                ->firstOrFail();

            return view('attestations.valide', compact('formation', 'individuelle'));
            // ✅ Affiche : "Attestation authentique délivrée à Jean Dupont le ..."

        } catch (\Throwable $e) {
            return view('attestations.invalide');
        }
    }
    /* 
    public function verifier(Request $request)
    {
        try {
            $decoded = base64_decode($request->query('token'));

            if (!str_contains($decoded, '::')) {
                return view('attestations.invalide');
            }

            [$payload, $signature] = explode('::', $decoded, 2);

            $secret   = config('app.attestation_secret');
            $expected = hash_hmac('sha256', $payload, $secret);

            if (!hash_equals($expected, $signature)) {
                return view('attestations.invalide');
            }

            [$formationId, $individuelleId, $userId, $dateFin] = explode('|', $payload);

            $formation    = Formation::findOrFail((int) $formationId);
            $individuelle = Individuelle::with('user')
                ->where('id', $individuelleId)
                ->where('formation_id', $formationId)
                ->firstOrFail();

            // Déduire le moduleName comme dans telechargerAttestationParticipation
            $moduleName = null;
            if ($formation?->module?->name) {
                $moduleName = $formation->module->name;
            } elseif ($formation?->collectivemodule?->module) {
                $moduleName = $formation->collectivemodule->module;
            }

            return view('attestations.valide', compact('formation', 'individuelle', 'moduleName'));
        } catch (\Throwable $e) {
            return view('attestations.invalide');
        }
    } */

    public function telechargerAttestationReussite(int $formationId, int $individuelleId)
    {
        $formation    = Formation::findOrFail($formationId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $individuelle = Individuelle::findOrFail($individuelleId);

        // Vérification de la note ou mention avant génération
        $noteObtenue = $individuelle->note_obtenue;
        $mentionsAcceptees = ['attesté', 'attestée'];

        $noteValide = is_numeric($noteObtenue)
            ? (float) $noteObtenue >= 12
            : in_array(strtolower(trim($noteObtenue)), $mentionsAcceptees);

        if (!$noteValide) {
            Alert::warning('Action impossible !', 'Le participant n\'a pas obtenu la note ou la mention requise pour recevoir une attestation de réussite.');
            return redirect()->back();
        }

        $direction    = Direction::where('sigle', 'DG')->first();

        $nameDG = $direction?->chef?->user?->civilite . ' ' . $direction?->chef?->user?->firstname . ' ' . $direction?->chef?->user?->name;
        $nameRes = $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name;

        $validated_by = new Validationformation([
            'validated_id'  =>  Auth::user()->id,
            'action'        => "generer",
            'motif'        =>  $nameRes,
            'formations_id' => $formationId,
        ]);

        $validated_by->save();

        Validationindividuelle::create([
            'validated_id'     => Auth::user()->id,
            'action'           => 'Attestation ou titre généré',
            'motif'           => 'Votre attestation/titre a été généré',
            'individuelles_id' => $individuelle->id,
        ]);

        $numeroAttestation = NumeroAttestationService::generer($formation->date_fin?->year ?? now()->year);

        /* $individuelle->update([
            'attestation' => 'generer', // ou la valeur souhaitée
            'numero_attestation'   => $numeroAttestation,
        ]); */

        if (!$individuelle->numero_attestation) {
            $numeroAttestation = NumeroAttestationService::generer(
                $formation->date_fin?->year ?? now()->year
            );
            $individuelle->update([
                'attestation'        => 'generer',
                'numero_attestation' => $numeroAttestation,
            ]);
        } else {
            $numeroAttestation = $individuelle->numero_attestation;
            $individuelle->update(['attestation' => 'generer']);
        }

        $title = 'Attestation de Réussite ' . $formation->name;
        $now   = \Carbon\Carbon::now();

        // Résolution du nom de module (identique à la participation)
        $moduleName = null;
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        // Génération du token QR signé
        $payload = implode('|', [
            $formation->id,
            $individuelle->id,
            $individuelle->user->id,
            $formation->date_fin?->format('Y-m-d'),
            'reussite', // discriminant pour distinguer du QR participation
        ]);

        $secret    = config('app.attestation_secret');
        $signature = hash_hmac('sha256', $payload, $secret);
        $token     = base64_encode($payload . '::' . $signature);

        $qrContent = route('attestation.verifier', ['token' => $token]);

        $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
        $writer       = new PngWriter();
        $result       = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $html = View::make('formations.individuelles.attestation_reussite', compact(
            'formation',
            'title',
            'individuelle',
            'moduleName',
            'nameDG',
            'now',
            'qrCodeBase64'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $name = 'Attestation_Reussite_' . $individuelle->user->firstname . '_' . $individuelle->user->name . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    }

    /* public function telechargerAttestationReussiteBoucle(int $formationId)
    {
        $formation    = Formation::findOrFail($formationId);
        $direction    = Direction::where('sigle', 'DG')->first();


        dd($direction);

        $nameDG = $direction?->chef?->user?->civilite . ' ' . $direction?->chef?->user?->firstname . ' ' . $direction?->chef?->user?->name;
        $nameRes = $individuelle?->user?->civilite . ' ' . $individuelle?->user?->firstname . ' ' . $individuelle?->user?->name;

        $validated_by = new Validationformation([
            'validated_id'  =>  Auth::user()->id,
            'action'        => "generer",
            'motif'        =>  $nameRes,
            'formations_id' => $formationId,
        ]);

        $validated_by->save();

        Validationindividuelle::create([
            'validated_id'     => Auth::user()->id,
            'action'           => 'Attestation ou titre généré',
            'motif'           => 'Votre attestation/titre a été généré',
            'individuelles_id' => $individuelle->id,
        ]);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $title = 'Attestation de Réussite ' . $formation->name;
        $now   = \Carbon\Carbon::now();

        // Résolution du nom de module (identique à la participation)
        $moduleName = null;
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        // Génération du token QR signé
        $payload = implode('|', [
            $formation->id,
            $individuelle->id,
            $individuelle->user->id,
            $formation->date_fin?->format('Y-m-d'),
            'reussite', // discriminant pour distinguer du QR participation
        ]);

        $secret    = config('app.attestation_secret');
        $signature = hash_hmac('sha256', $payload, $secret);
        $token     = base64_encode($payload . '::' . $signature);

        $qrContent = route('attestation.verifier', ['token' => $token]);

        $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
        $writer       = new PngWriter();
        $result       = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $html = View::make('formations.individuelles.attestation_reussite', compact(
            'formation',
            'title',
            'individuelle',
            'moduleName',
            'nameDG',
            'now',
            'qrCodeBase64'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $name = 'Attestation_Reussite_' . $individuelle->user->firstname . '_' . $individuelle->user->name . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    } */
    /* 

    public function telechargerToutesAttestationsReussite(int $formationId)
    {
        $formation = Formation::findOrFail($formationId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $direction = Direction::where('sigle', 'DG')->first();
        $nameDG = $direction?->chef?->user?->civilite . ' ' .
            $direction?->chef?->user?->firstname . ' ' .
            $direction?->chef?->user?->name;

        $now = \Carbon\Carbon::now();

        // Log de l'action globale sur la formation
        $validated_by = new Validationformation([
            'validated_id'  => Auth::user()->id,
            'action'        => "generer",
            'formations_id' => $formationId,
        ]);
        $validated_by->save();

        // Résolution du nom de module
        $moduleName = null;
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        $title = 'Attestations de Réussite ' . $formation->name;

        // Initialisation de Dompdf
        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        // Récupération de tous les bénéficiaires de la formation
        $individuelles = $formation->individuelles;

        if ($individuelles->isEmpty()) {
            Alert::warning('Aucun bénéficiaire', 'Aucun bénéficiaire n\'est rattaché à cette formation.');
            return redirect()->back();
        }

        // Construction du HTML complet (une page par bénéficiaire)
        $allHtml = '';

        foreach ($individuelles as $individuelle) {

            // Log individuel
            Validationindividuelle::create([
                'validated_id'   => Auth::user()->id,
                'action'         => 'Attestation ou titre généré',
                'motif'          => 'Votre attestation/titre a été généré',
                'individuelles_id' => $individuelle->id,
            ]);

            $individuelle->update(['attestation' => 'generer']);

            // Génération du QR code signé
            $payload = implode('|', [
                $formation->id,
                $individuelle->id,
                $individuelle->user->id,
                $formation->date_fin?->format('Y-m-d'),
                'reussite',
            ]);

            $secret    = config('app.attestation_secret');
            $signature = hash_hmac('sha256', $payload, $secret);
            $token     = base64_encode($payload . '::' . $signature);

            $qrContent    = route('attestation.verifier', ['token' => $token]);
            $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
            $writer       = new PngWriter();
            $result       = $writer->write($qrCode);
            $qrCodeBase64 = base64_encode($result->getString());

            // Rendu de la vue pour ce bénéficiaire
            $html = View::make('formations.individuelles.attestation_reussite', compact(
                'formation',
                'title',
                'individuelle',
                'moduleName',
                'nameDG',
                'now',
                'qrCodeBase64'
            ))->render();

            // Extraction du contenu du <body> pour concaténation
            // On enveloppe chaque attestation dans un div avec saut de page
            preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches);
            $bodyContent = $matches[1] ?? $html;

            $allHtml .= '<div style="page-break-after: always;">' . $bodyContent . '</div>';
        }

        // Enveloppe HTML complète avec les styles de la première attestation
        preg_match('/<head[^>]*>(.*?)<\/head>/is', $html, $headMatches);
        $headContent = $headMatches[1] ?? '';

        $fullHtml = '<!DOCTYPE html><html lang="fr"><head>' . $headContent . '</head><body>' . $allHtml . '</body></html>';

        $dompdf->loadHtml($fullHtml);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $name = 'Attestations_Reussite_' . Str::slug($formation->name) . '_' . now()->format('Ymd') . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    } */

    public function telechargerToutesAttestationsReussite(int $formationId)
    {
        // ── 0. Limites PHP ──────────────────────────────────────────────
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $formation = Formation::findOrFail($formationId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $individuelles = $formation->individuelles;

        if ($individuelles->isEmpty()) {
            Alert::warning('Aucun bénéficiaire', 'Aucun bénéficiaire n\'est rattaché à cette formation.');
            return redirect()->back();
        }

        $direction = Direction::where('sigle', 'DG')->first();
        $nameDG    = $direction?->chef?->user?->civilite . ' '
            . $direction?->chef?->user?->firstname . ' '
            . $direction?->chef?->user?->name;

        $now   = \Carbon\Carbon::now();
        $title = 'Attestations de Réussite ' . $formation->name;

        $moduleName = null;
        if ($formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule?->module) {
            $moduleName = $formation->collectivemodule->module;
        }

        // Log formation (une seule fois)
        Validationformation::create([
            'validated_id'  => Auth::user()->id,
            'action'        => 'generer',
            'formations_id' => $formationId,
        ]);

        // ── 1. Dossier temporaire ───────────────────────────────────────
        $tmpDir = storage_path('app/tmp/attestations_' . $formationId . '_' . uniqid());
        mkdir($tmpDir, 0755, true);

        $pdfPaths = [];

        try {
            foreach ($individuelles as $individuelle) {

                // Vérification de la note ou mention — on saute les non-éligibles
                $noteObtenue = $individuelle->note_obtenue;
                $mentionsAcceptees = ['attesté', 'attestée'];

                $noteValide = is_numeric($noteObtenue)
                    ? (float) $noteObtenue >= 12
                    : in_array(strtolower(trim((string) $noteObtenue)), $mentionsAcceptees);

                if (!$noteValide) {
                    continue; // on passe au suivant sans générer ni logger
                }

                // Logs individuels
                Validationindividuelle::create([
                    'validated_id'    => Auth::user()->id,
                    'action'          => 'Attestation ou titre généré',
                    'motif'           => 'Votre attestation/titre a été généré',
                    'individuelles_id' => $individuelle->id,
                ]);
                $individuelle->update(['attestation' => 'generer']);

                // QR Code
                $payload = implode('|', [
                    $formation->id,
                    $individuelle->id,
                    $individuelle->user->id,
                    $formation->date_fin?->format('Y-m-d'),
                    'reussite',
                ]);
                $secret       = config('app.attestation_secret');
                $signature    = hash_hmac('sha256', $payload, $secret);
                $token        = base64_encode($payload . '::' . $signature);
                $qrContent    = route('attestation.verifier', ['token' => $token]);
                $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
                $writer       = new PngWriter();
                $qrCodeBase64 = base64_encode($writer->write($qrCode)->getString());

                // Rendu HTML → PDF
                $html = View::make('formations.individuelles.attestation_reussite', compact(
                    'formation',
                    'title',
                    'individuelle',
                    'moduleName',
                    'nameDG',
                    'now',
                    'qrCodeBase64'
                ))->render();

                $dompdf = new Dompdf();
                $opts   = $dompdf->getOptions();
                $opts->setDefaultFont('DejaVu Sans');
                $dompdf->setOptions($opts);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();

                // Écriture sur disque
                $pdfPath    = $tmpDir . '/' . $individuelle->id . '.pdf';
                file_put_contents($pdfPath, $dompdf->output());
                $pdfPaths[] = $pdfPath;

                // Libération mémoire immédiate
                unset($dompdf, $html, $qrCodeBase64, $writer, $qrCode);
                gc_collect_cycles();
            }

            // ── 2. Fusion avec FPDI ─────────────────────────────────────
            $merger = new \setasign\Fpdi\Fpdi();
            $merger->SetAutoPageBreak(false);

            foreach ($pdfPaths as $path) {
                $pageCount = $merger->setSourceFile($path);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $merger->importPage($i);
                    $sz  = $merger->getTemplateSize($tpl);
                    $merger->AddPage(
                        $sz['width'] > $sz['height'] ? 'L' : 'P',
                        [$sz['width'], $sz['height']]
                    );
                    $merger->useTemplate($tpl);
                }
            }

            $outputPath = $tmpDir . '/merged.pdf';
            $merger->Output($outputPath, 'F');
            unset($merger);

            // ── 3. Envoi au navigateur ──────────────────────────────────
            $fileName = 'Attestations_Reussite_' . Str::slug($formation->name) . '_' . now()->format('Ymd') . '.pdf';

            return response()->download($outputPath, $fileName, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);
        } finally {
            // ── 4. Nettoyage garanti même en cas d'exception ────────────
            foreach ($pdfPaths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            if (is_dir($tmpDir)) {
                @rmdir($tmpDir);
            }
        }
    }

    // Attestation de participationcollective

    public function telechargerAttestationParticipationCollective(int $formationId, int $collectiveId)
    {
        $formation = Formation::findOrFail($formationId);
        $listecollective = Listecollective::findOrFail($collectiveId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $title         = 'Attestation de participation ' . $formation->name;
        $now = \Carbon\Carbon::now();
        /* $membres_jury  = explode(";", $formation->membres_jury);
        $count_membres = count($membres_jury); */
        // ✅ Génération QR PNG sans imagick avec endroid/qr-code
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        /* $qrContent = "Formation : {$formation?->name}\n" .
            "Code : {$formation?->code}\n" .
            "Module : {$moduleName}\n" .
            "Date : " . $formation?->date_debut?->format('d/m/Y') . " au " . $formation?->date_fin?->format('d/m/Y'); */

        $nameRes = $listecollective->civilite . ' ' . $listecollective->prenom . ' ' . $listecollective->nom;

        $validated_by = new Validationformation([
            'validated_id'  =>  Auth::user()->id,
            'action'        => "generer",
            'motif'        =>  $nameRes,
            'formations_id' => $formationId,
        ]);

        $validated_by->save();

        Validationcollective::create([
            'validated_id'     => Auth::user()->id,
            'action'           => 'attestation',
            'motif'           => 'Votre attestation/titre a été généré',
            'collectives_id' => $listecollective->collective->id,
        ]);

        $numeroAttestation = NumeroAttestationService::generer($formation->date_fin?->year ?? now()->year);

        /* $listecollective->update([
            'attestation' => 'generer', // ou la valeur souhaitée
            'numero_attestation'   => $numeroAttestation,
        ]); */

        // ✅ Généré à chaque itération
        if (!$listecollective->numero_attestation) {
            $numeroAttestation = NumeroAttestationService::generer(
                $formation->date_fin?->year ?? now()->year
            );
            $listecollective->update([
                'attestation'        => 'generer',
                'numero_attestation' => $numeroAttestation,
            ]);
        } else {
            $numeroAttestation = $listecollective->numero_attestation;
            $listecollective->update(['attestation' => 'generer']);
        }

        // Remplacer votre bloc $qrContent par :
        $payload = implode('|', [
            $formation->id,
            $listecollective->id,
            $listecollective->collective->user->id,
            $formation->date_fin?->format('Y-m-d'),
        ]);

        $secret    = config('app.attestation_secret');
        $signature = hash_hmac('sha256', $payload, $secret);
        $token     = base64_encode($payload . '::' . $signature);

        $qrContent = route('attestationCollective.verifier', ['token' => $token]);
        //FIN

        $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
        $writer       = new PngWriter();
        $result       = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);


        $html = View::make('formations.collectives.attestation_participation', compact(
            'formation',
            'title',
            'listecollective',
            'moduleName',
            'now',
            'qrCodeBase64'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $name = 'Attestation_Particpation_' . $listecollective->prenom . '_' . $listecollective->nom . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    }

    public function verifierCollective(Request $request)
    {
        try {
            // ✅ Même décodage que verifier()
            $decoded = base64_decode($request->query('token'));

            if (!str_contains($decoded, '::')) {
                return view('attestations.collectives.invalide');
            }

            [$payload, $signature] = explode('::', $decoded, 2);

            $secret   = config('app.attestation_secret');
            $expected = hash_hmac('sha256', $payload, $secret);

            if (!hash_equals($expected, $signature)) {
                return view('attestations.collectives.invalide');
            }

            [$formationId, $collectiveId, $userId, $dateFin] = explode('|', $payload);

            $formation = Formation::findOrFail($formationId);

            $listecollective = Listecollective::with('collective')
                ->where('id', $collectiveId)
                ->where('formations_id', $formationId)
                ->firstOrFail();

            return view('attestations.collectives.valide', compact('formation', 'listecollective'));
        } catch (\Throwable $e) {
            return view('attestations.collectives.invalide');
        }
    }

    public function telechargerAttestationReussiteCollective(int $formationId, int $listecollectiveId)
    {
        $formation    = Formation::findOrFail($formationId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $listecollective    = Listecollective::findOrFail($listecollectiveId);

        // Vérification de la note ou mention avant génération
        $noteObtenue = $listecollective->note_obtenue;
        $mentionsAcceptees = ['attesté', 'attestée'];

        $noteValide = is_numeric($noteObtenue)
            ? (float) $noteObtenue >= 12
            : in_array(strtolower(trim($noteObtenue)), $mentionsAcceptees);

        if (!$noteValide) {
            Alert::warning('Action impossible !', 'Le participant n\'a pas obtenu la note ou la mention requise pour recevoir une attestation de réussite.');
            return redirect()->back();
        }

        $direction    = Direction::where('sigle', 'DG')->first();

        $nameDG = $direction?->chef?->user?->civilite . ' ' . $direction?->chef?->user?->firstname . ' ' . $direction?->chef?->user?->name;

        $validated_by = new Validationformation([
            'validated_id'  =>  Auth::user()->id,
            'action'        => "generer",
            'formations_id' => $formationId,
        ]);

        $validated_by->save();

        Validationcollective::create([
            'validated_id'   => Auth::user()->id,
            'action'         => 'Attestation ou titre généré',
            'motif'          => 'Votre attestation/titre a été généré',
            'collectives_id' => $listecollective->collective->id,
        ]);

        /* $listecollective->update([
            'attestation' => 'generer', // ou la valeur souhaitée
        ]); */

        $numeroAttestation = NumeroAttestationService::generer($formation->date_fin?->year ?? now()->year);

        if (!$listecollective->numero_attestation) {
            $numeroAttestation = NumeroAttestationService::generer(
                $formation->date_fin?->year ?? now()->year
            );
            $listecollective->update([
                'attestation'        => 'generer',
                'numero_attestation' => $numeroAttestation,
            ]);
        } else {
            $numeroAttestation = $listecollective->numero_attestation;
            $listecollective->update(['attestation' => 'generer']);
        }

        $title = 'Attestation de Réussite ' . $formation->name;
        $now   = \Carbon\Carbon::now();

        // Résolution du nom de module (identique à la participation)
        $moduleName = null;
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        // Génération du token QR signé
        $payload = implode('|', [
            $formation->id,
            $listecollective->id,
            $listecollective->collective->user->id,
            $formation->date_fin?->format('Y-m-d'),
            'reussite', // discriminant pour distinguer du QR participation
        ]);

        $secret    = config('app.attestation_secret');
        $signature = hash_hmac('sha256', $payload, $secret);
        $token     = base64_encode($payload . '::' . $signature);

        $qrContent = route('attestationCollective.verifier', ['token' => $token]);

        $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
        $writer       = new PngWriter();
        $result       = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $html = View::make('formations.collectives.attestation_reussite', compact(
            'formation',
            'title',
            'listecollective',
            'moduleName',
            'nameDG',
            'now',
            'qrCodeBase64'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $name = 'Attestation_Reussite_' . $listecollective->prenom . '_' . $listecollective->nom . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    }

    /* public function telechargerAttestationReussiteCollectiveBoucle(int $formationId)
    {
        $formation    = Formation::findOrFail($formationId);
        $direction    = Direction::where('sigle', 'DG')->first();
        $listecollectives = $formation->listecollectives;

        $nameDG = $direction?->chef?->user?->civilite . ' ' . $direction?->chef?->user?->firstname . ' ' . $direction?->chef?->user?->name;

        $validated_by = new Validationformation([
            'validated_id'  =>  Auth::user()->id,
            'action'        => "generer",
            'formations_id' => $formationId,
        ]);

        $validated_by->save();

        foreach ($listecollectives as $listecollective) {
            Validationcollective::create([
                'validated_id'   => Auth::user()->id,
                'action'         => 'Attestation ou titre généré',
                'motif'          => 'Votre attestation/titre a été généré',
                'collectives_id' => $listecollective->collective->id,
            ]);
        }

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $title = 'Attestation de Réussite ' . $formation->name;
        $now   = \Carbon\Carbon::now();

        // Résolution du nom de module (identique à la participation)
        $moduleName = null;
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        // Génération du token QR signé
        $payload = implode('|', [
            $formation->id,
            $listecollective->id,
            $listecollective->collective->user->id,
            $formation->date_fin?->format('Y-m-d'),
            'reussite', // discriminant pour distinguer du QR participation
        ]);

        $secret    = config('app.attestation_secret');
        $signature = hash_hmac('sha256', $payload, $secret);
        $token     = base64_encode($payload . '::' . $signature);

        $qrContent = route('attestationCollective.verifier', ['token' => $token]);

        $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
        $writer       = new PngWriter();
        $result       = $writer->write($qrCode);
        $qrCodeBase64 = base64_encode($result->getString());

        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        $html = View::make('formations.collectives.attestation_reussite', compact(
            'formation',
            'title',
            'listecollective',
            'moduleName',
            'nameDG',
            'now',
            'qrCodeBase64'
        ))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $name = 'Attestation_Reussite_' . $listecollective->prenom . '_' . $listecollective->nom . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    } */

    /* public function telechargerToutesAttestationsReussiteCollective(int $formationId)
    {
        $formation = Formation::findOrFail($formationId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $direction = Direction::where('sigle', 'DG')->first();
        $nameDG = $direction?->chef?->user?->civilite . ' ' .
            $direction?->chef?->user?->firstname . ' ' .
            $direction?->chef?->user?->name;

        $now = \Carbon\Carbon::now();

        // Log de l'action globale sur la formation
        $validated_by = new Validationformation([
            'validated_id'  => Auth::user()->id,
            'action'        => "generer",
            'formations_id' => $formationId,
        ]);
        $validated_by->save();

        // Résolution du nom de module
        $moduleName = null;
        if ($formation?->module && $formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule && $formation?->collectivemodule?->module) {
            $moduleName = $formation?->collectivemodule?->module;
        }

        $title = 'Attestations de Réussite ' . $formation->name;

        // Initialisation de Dompdf
        $dompdf  = new Dompdf();
        $options = $dompdf->getOptions();
        $options->setDefaultFont('DejaVu Sans');
        $dompdf->setOptions($options);

        // Récupération de tous les bénéficiaires de la formation
        $listecollectives = $formation->listecollectives;

        if ($listecollectives->isEmpty()) {
            Alert::warning('Aucun bénéficiaire', 'Aucun bénéficiaire n\'est rattaché à cette formation.');
            return redirect()->back();
        }

        // Construction du HTML complet (une page par bénéficiaire)
        $allHtml = '';

        foreach ($listecollectives as $listecollective) {

            // Log individuel
            Validationcollective::create([
                'validated_id'   => Auth::user()->id,
                'action'         => 'Attestation ou titre généré',
                'motif'          => 'Votre attestation/titre a été généré',
                'collectives_id' => $listecollective->collective->id,
            ]);

            $listecollective->update(['attestation' => 'generer']);

            // Génération du QR code signé
            $payload = implode('|', [
                $formation->id,
                $listecollective->id,
                $listecollective->collective->user->id,
                $formation->date_fin?->format('Y-m-d'),
                'reussite',
            ]);

            $secret    = config('app.attestation_secret');
            $signature = hash_hmac('sha256', $payload, $secret);
            $token     = base64_encode($payload . '::' . $signature);

            $qrContent    = route('attestationCollective.verifier', ['token' => $token]);
            $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
            $writer       = new PngWriter();
            $result       = $writer->write($qrCode);
            $qrCodeBase64 = base64_encode($result->getString());

            // Rendu de la vue pour ce bénéficiaire
            $html = View::make('formations.collectives.attestation_reussite', compact(
                'formation',
                'title',
                'listecollective',
                'moduleName',
                'nameDG',
                'now',
                'qrCodeBase64'
            ))->render();

            // Extraction du contenu du <body> pour concaténation
            // On enveloppe chaque attestation dans un div avec saut de page
            preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches);
            $bodyContent = $matches[1] ?? $html;

            $allHtml .= '<div style="page-break-after: always;">' . $bodyContent . '</div>';
        }

        // Enveloppe HTML complète avec les styles de la première attestation
        preg_match('/<head[^>]*>(.*?)<\/head>/is', $html, $headMatches);
        $headContent = $headMatches[1] ?? '';

        $fullHtml = '<!DOCTYPE html><html lang="fr"><head>' . $headContent . '</head><body>' . $allHtml . '</body></html>';

        $dompdf->loadHtml($fullHtml);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $name = 'Attestations_Reussite_' . Str::slug($formation->name) . '_' . now()->format('Ymd') . '.pdf';
        return $dompdf->stream($name, ['Attachment' => true]);
    } */

    public function telechargerToutesAttestationsReussiteCollective(int $formationId)
    {
        // ── 0. Limites PHP ──────────────────────────────────────────────
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $formation = Formation::findOrFail($formationId);

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        $listecollectives = $formation->listecollectives;

        if ($listecollectives->isEmpty()) {
            Alert::warning('Aucun bénéficiaire', 'Aucun bénéficiaire n\'est rattaché à cette formation.');
            return redirect()->back();
        }

        $direction = Direction::where('sigle', 'DG')->first();
        $nameDG    = $direction?->chef?->user?->civilite . ' '
            . $direction?->chef?->user?->firstname . ' '
            . $direction?->chef?->user?->name;

        $now   = \Carbon\Carbon::now();
        $title = 'Attestations de Réussite ' . $formation->name;

        $moduleName = null;
        if ($formation?->module?->name) {
            $moduleName = $formation->module->name;
        } elseif ($formation?->collectivemodule?->module) {
            $moduleName = $formation->collectivemodule->module;
        }

        // Log formation (une seule fois)
        Validationformation::create([
            'validated_id'  => Auth::user()->id,
            'action'        => 'generer',
            'formations_id' => $formationId,
        ]);

        // ── 1. Dossier temporaire ───────────────────────────────────────
        $tmpDir = storage_path('app/tmp/attestations_' . $formationId . '_' . uniqid());
        mkdir($tmpDir, 0755, true);

        $pdfPaths = [];

        try {
            foreach ($listecollectives as $listecollective) {

                // Vérification de la note ou mention — on saute les non-éligibles
                $noteObtenue = $listecollective->note_obtenue;
                $mentionsAcceptees = ['attesté', 'attestée'];

                $noteValide = is_numeric($noteObtenue)
                    ? (float) $noteObtenue >= 12
                    : in_array(strtolower(trim((string) $noteObtenue)), $mentionsAcceptees);

                if (!$noteValide) {
                    continue; // on passe au suivant sans générer ni logger
                }

                // Logs individuels
                Validationcollective::create([
                    'validated_id'   => Auth::user()->id,
                    'action'         => 'Attestation ou titre généré',
                    'motif'          => 'Votre attestation/titre a été généré',
                    'collectives_id' => $listecollective->collective->id,
                ]);
                $listecollective->update(['attestation' => 'generer']);

                // QR Code
                $payload = implode('|', [
                    $formation->id,
                    $listecollective->id,
                    $listecollective->collective->user->id,
                    $formation->date_fin?->format('Y-m-d'),
                    'reussite',
                ]);
                $secret       = config('app.attestation_secret');
                $signature    = hash_hmac('sha256', $payload, $secret);
                $token        = base64_encode($payload . '::' . $signature);
                $qrContent    = route('attestationCollective.verifier', ['token' => $token]);
                $qrCode       = QrCode::create($qrContent)->setSize(150)->setMargin(0);
                $writer       = new PngWriter();
                $qrCodeBase64 = base64_encode($writer->write($qrCode)->getString());

                // Rendu HTML → PDF
                $html = View::make('formations.collectives.attestation_reussite', compact(
                    'formation',
                    'title',
                    'listecollective',
                    'moduleName',
                    'nameDG',
                    'now',
                    'qrCodeBase64'
                ))->render();

                $dompdf = new Dompdf();
                $opts   = $dompdf->getOptions();
                $opts->setDefaultFont('DejaVu Sans');
                $dompdf->setOptions($opts);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();

                // Écriture sur disque
                $pdfPath    = $tmpDir . '/' . $listecollective->id . '.pdf';
                file_put_contents($pdfPath, $dompdf->output());
                $pdfPaths[] = $pdfPath;

                // Libération mémoire immédiate
                unset($dompdf, $html, $qrCodeBase64, $writer, $qrCode);
                gc_collect_cycles();
            }

            // ── 2. Fusion avec FPDI ─────────────────────────────────────
            $merger = new \setasign\Fpdi\Fpdi();
            $merger->SetAutoPageBreak(false);

            foreach ($pdfPaths as $path) {
                $pageCount = $merger->setSourceFile($path);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $merger->importPage($i);
                    $sz  = $merger->getTemplateSize($tpl);
                    $merger->AddPage(
                        $sz['width'] > $sz['height'] ? 'L' : 'P',
                        [$sz['width'], $sz['height']]
                    );
                    $merger->useTemplate($tpl);
                }
            }

            $outputPath = $tmpDir . '/merged.pdf';
            $merger->Output($outputPath, 'F');
            unset($merger);

            // ── 3. Envoi au navigateur ──────────────────────────────────
            $fileName = 'Attestations_Reussite_' . Str::slug($formation->name) . '_' . now()->format('Ymd') . '.pdf';

            return response()->download($outputPath, $fileName, [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend(true);
        } finally {
            // ── 4. Nettoyage garanti même en cas d'exception ────────────
            foreach ($pdfPaths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            if (is_dir($tmpDir)) {
                @rmdir($tmpDir);
            }
        }
    }

    public function attestation(int $formationId)
    {

        $formation       = Formation::findOrFail($formationId);
        $type_formation  = $formation->types_formation?->name;

        if ($type_formation === 'collective') {
            $listecollective = Listecollective::where('formations_id', $formationId)->firstOrFail();

            return redirect()->route(
                'formations.attestations.reussite.collectives.toutes',
                $formation->id
            );
        }

        if ($type_formation === 'individuelle') {
            $individuelle = Individuelle::where('formations_id', $formationId)->firstOrFail();

            return redirect()->route(
                'formations.attestations.reussite.toutes',
                $formation->id
            );
        }

        // Type non reconnu
        abort(404, "Type de formation non reconnu : {$type_formation}");
    }

    public function attestations(int $formationId)
    {
        $formation      = Formation::findOrFail($formationId);
        $type_formation = $formation->types_formation?->name;

        if (!in_array($type_formation, ['individuelle', 'collective'])) {
            abort(404, "Type de formation non reconnu : {$type_formation}");
        }

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        // PDF déjà généré → téléchargement direct
        if (
            $formation->pdf_attestations_path &&
            $formation->pdf_attestations_path !== 'en_cours' &&
            file_exists(storage_path('app/public/' . $formation->pdf_attestations_path))
        ) {
            return redirect()->route('formations.attestations.telecharger', $formation->id);
        }

        // Pas encore lancé ou en cours → rediriger vers la page avec les boutons
        return redirect()->back();
    }


    //avec les jobs
    /*  public function attestation(int $formationId)
    {
        $formation      = Formation::findOrFail($formationId);
        $type_formation = $formation->types_formation?->name;

        if (!in_array($type_formation, ['individuelle', 'collective'])) {
            abort(404, "Type de formation non reconnu : {$type_formation}");
        }

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        // Si déjà généré → téléchargement direct
        if (
            $formation->pdf_attestations_path &&
            $formation->pdf_attestations_path !== 'en_cours' &&
            file_exists(storage_path('app/public/' . $formation->pdf_attestations_path))
        ) {
            return redirect()->route('formations.attestations.telecharger', $formation->id);
        }

        // Sinon → lancer la génération en queue
        return redirect()->route('formations.attestations.lancer', $formation->id);
    } */

    // Lance la génération en arrière-plan
    public function lancerGenerationAttestations(int $formationId)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        $formation = Formation::findOrFail($formationId);
        /* dd($formation, $type, $formation->types_formation->name); */

        if ($formation->statut != "Terminée") {
            Alert::warning('Action impossible !', 'La formation n\'est pas encore achevée.');
            return redirect()->back();
        }

        // Réinitialise le chemin pour indiquer que c'est en cours
        $formation->update(['pdf_attestations_path' => 'en_cours']);

        Validationformation::create([
            'validated_id'  => Auth::user()->id,
            'action'        => 'generer',
            'formations_id' => $formationId,
        ]);

        GenererAttestationsReussiteJob::dispatch($formationId, Auth::user()->id, $formation->types_formation->name);

        Alert::info('Génération lancée', 'Le PDF sera disponible dans quelques minutes. Revenez sur cette page pour le télécharger.');
        return redirect()->back();
    }

    // Télécharge quand le PDF est prêt
    public function telechargerAttestationsGenerees(int $formationId)
    {
        $formation = Formation::findOrFail($formationId);
        $path      = storage_path('app/public/' . $formation->pdf_attestations_path);

        if (!$formation->pdf_attestations_path || $formation->pdf_attestations_path === 'en_cours' || !file_exists($path)) {
            Alert::warning('Pas encore prêt', 'La génération est en cours, veuillez patienter.');
            return redirect()->back();
        }

        return response()->download($path, basename($path), [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
