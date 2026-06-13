<?php

// app/Jobs/GenererAttestationsReussiteJob.php

namespace App\Jobs;

use App\Models\Direction;
use App\Models\Formation;
use App\Models\Validationcollective;
use App\Models\Validationindividuelle;
use Dompdf\Dompdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class GenererAttestationsReussiteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;
    public int $tries   = 1;

    public function __construct(
        public int $formationId,
        public int $userId,        // Auth n'est pas disponible dans un job
    ) {}

    public function handle(): void
    {
        ini_set('memory_limit', '512M');

        $formation = Formation::findOrFail($this->formationId);

        $type = $formation->types_formation->name;

        $direction = Direction::where('sigle', 'DG')->first();
        $nameDG    = $direction?->chef?->user?->civilite . ' '
            . $direction?->chef?->user?->firstname . ' '
            . $direction?->chef?->user?->name;

        $now        = \Carbon\Carbon::now();
        $title      = 'Attestations de Réussite ' . $formation->name;
        $moduleName = $formation?->module?->name
            ?? $formation?->collectivemodule?->module
            ?? null;

        /* $items = $type === 'collective'
            ? $formation->collective->listecollectives
            : $formation->individuelles; */

        $items = $type === 'collective'
            ? $formation->collective->listecollectives->where('formations_id', $formation->id)
            : $formation->individuelles;

        $tmpDir = storage_path('app/tmp/att_' . $this->formationId . '_' . uniqid());
        mkdir($tmpDir, 0755, true);
        $pdfPaths = [];

        try {
            foreach ($items as $item) {

                // Logs
                if ($type === 'collective') {
                    Validationcollective::create([
                        'validated_id'   => $this->userId,
                        'action'         => 'Attestation ou titre généré',
                        'motif'          => 'Votre attestation/titre a été généré',
                        'collectives_id' => $item->collective->id,
                    ]);
                } elseif ($type === 'individuelle') {
                    Validationindividuelle::create([
                        'validated_id'    => $this->userId,
                        'action'          => 'Attestation ou titre généré',
                        'motif'           => 'Votre attestation/titre a été généré',
                        'individuelles_id' => $item->id,
                    ]);
                } else {
                    dd('aucun');
                }
                $item->update(['attestation' => 'generer']);

                // QR Code
                $userId  = $type === 'collective'
                    ? $item->collective->user->id
                    : $item->user->id;

                $payload   = implode('|', [
                    $formation->id,
                    $item->id,
                    $userId,
                    $formation->date_fin?->format('Y-m-d'),
                    'reussite',
                ]);
                $token        = base64_encode($payload . '::' . hash_hmac('sha256', $payload, config('app.attestation_secret')));
                $routeName    = $type === 'collective' ? 'attestationCollective.verifier' : 'attestation.verifier';
                $qrContent    = route($routeName, ['token' => $token]);
                $qrCodeBase64 = base64_encode((new PngWriter())->write(QrCode::create($qrContent)->setSize(150))->getString());

                // Vue
                $viewName = $type === 'collective'
                    ? 'formations.collectives.attestation_reussite'
                    : 'formations.individuelles.attestation_reussite';

                $varName = $type === 'collective' ? 'listecollective' : 'individuelle';

                $html = View::make($viewName, array_merge(compact(
                    'formation',
                    'title',
                    'moduleName',
                    'nameDG',
                    'now',
                    'qrCodeBase64'
                ), [$varName => $item]))->render();

                // PDF individuel
                $dompdf = new Dompdf();
                $opts   = $dompdf->getOptions();
                $opts->setDefaultFont('DejaVu Sans');
                $dompdf->setOptions($opts);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();

                $pdfPath    = $tmpDir . '/' . $item->id . '.pdf';
                file_put_contents($pdfPath, $dompdf->output());
                $pdfPaths[] = $pdfPath;

                unset($dompdf, $html, $qrCodeBase64);
                gc_collect_cycles();
            }

            // Fusion FPDI
            $merger = new \setasign\Fpdi\Fpdi();
            $merger->SetAutoPageBreak(false);

            foreach ($pdfPaths as $path) {
                $pageCount = $merger->setSourceFile($path);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tpl = $merger->importPage($i);
                    $sz  = $merger->getTemplateSize($tpl);
                    $merger->AddPage($sz['width'] > $sz['height'] ? 'L' : 'P', [$sz['width'], $sz['height']]);
                    $merger->useTemplate($tpl);
                }
            }

            // Stockage final dans storage/app/public/attestations/
            $finalName = 'attestations_' . $this->formationId . '_' . Str::slug($formation->name) . '.pdf';
            $finalPath = storage_path('app/public/attestations/' . $finalName);

            if (!is_dir(dirname($finalPath))) {
                mkdir(dirname($finalPath), 0755, true);
            }

            $merger->Output($finalPath, 'F');
            unset($merger);

            // Marquer comme prêt en base
            $formation->update(['pdf_attestations_path' => 'attestations/' . $finalName]);
        } finally {
            foreach ($pdfPaths as $path) {
                if (file_exists($path)) unlink($path);
            }
            if (is_dir($tmpDir)) @rmdir($tmpDir);
        }
    }
}
