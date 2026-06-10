<?php

namespace App\Http\Controllers;

use App\Models\Collective;
use App\Models\Direction;
use App\Models\Formation;
use App\Models\Individuelle;
use App\Models\Listecollective;
use App\Models\Validationcollective;
use App\Models\Validationformation;
use App\Models\Validationindividuelle;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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

        /* $formation->update([
            'attestation' => 'generer', // ou la valeur souhaitée
        ]); */

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

        $qrCode       = QrCode::create($qrContent)->setSize(150);
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
        return $dompdf->stream($name, ['Attachment' => false]);
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
        $individuelle = Individuelle::findOrFail($individuelleId);
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

        /* $formation->update([
            'attestation' => 'generer', // ou la valeur souhaitée
        ]); */

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

        $qrCode       = QrCode::create($qrContent)->setSize(150);
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
        return $dompdf->stream($name, ['Attachment' => false]);
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

        /* $formation->update([
            'attestation' => 'generer', // ou la valeur souhaitée
        ]); */

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

        $qrCode       = QrCode::create($qrContent)->setSize(150);
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
        return $dompdf->stream($name, ['Attachment' => false]);
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
            dd([
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'token'   => $request->query('token'),
                'decoded' => base64_decode($request->query('token')),
            ]);
        }
    }

    public function telechargerAttestationReussiteCollective(int $formationId, int $collectiveId)
    {
        $formation    = Formation::findOrFail($formationId);
        $listecollective = Listecollective::findOrFail($collectiveId);
        $direction    = Direction::where('sigle', 'DG')->first();

        $nameDG = $direction?->chef?->user?->civilite . ' ' . $direction?->chef?->user?->firstname . ' ' . $direction?->chef?->user?->name;
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
            'action'           => 'Attestation ou titre généré',
            'motif'           => 'Votre attestation/titre a été généré',
            'collectives_id' => $listecollective->collective->id,
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
            $listecollective->id,
            $listecollective->collective->user->id,
            $formation->date_fin?->format('Y-m-d'),
            'reussite', // discriminant pour distinguer du QR participation
        ]);

        $secret    = config('app.attestation_secret');
        $signature = hash_hmac('sha256', $payload, $secret);
        $token     = base64_encode($payload . '::' . $signature);

        $qrContent = route('attestation.verifier', ['token' => $token]);

        $qrCode       = QrCode::create($qrContent)->setSize(150);
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
        return $dompdf->stream($name, ['Attachment' => false]);
    }
}
