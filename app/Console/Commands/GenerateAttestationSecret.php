<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateAttestationSecret extends Command
{
    /**
     * php artisan attestation:generate-secret
     */
    protected $signature   = 'attestation:generate-secret
                                {--show : Afficher uniquement la clé, sans modifier .env}
                                {--force : Écraser la clé existante sans confirmation}';

    protected $description = 'Génère une clé secrète HMAC-SHA256 pour signer les attestations';

    public function handle(): int
    {
        $secret = bin2hex(random_bytes(32)); // 64 chars hex, 256 bits d'entropie

        if ($this->option('show')) {
            $this->line($secret);
            return self::SUCCESS;
        }

        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            $this->error('Fichier .env introuvable.');
            return self::FAILURE;
        }

        $envContent = file_get_contents($envPath);
        $key        = 'APP_ATTESTATION_SECRET';

        // Vérifier si la clé existe déjà
        if (str_contains($envContent, $key . '=')) {
            $current = $this->extractCurrentValue($envContent, $key);

            if (! empty($current) && ! $this->option('force')) {
                $this->warn("⚠  Une clé existe déjà : {$key}={$current}");
                $this->warn('   Les attestations déjà émises ne seront PLUS vérifiables si vous la changez.');

                if (! $this->confirm('Voulez-vous vraiment remplacer la clé existante ?', false)) {
                    $this->info('Opération annulée. Clé inchangée.');
                    return self::SUCCESS;
                }
            }

            // Remplacer la valeur existante
            $envContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$secret}",
                $envContent
            );
        } else {
            // Ajouter la ligne à la fin du .env
            $envContent .= PHP_EOL . "{$key}={$secret}" . PHP_EOL;
        }

        file_put_contents($envPath, $envContent);

        $this->newLine();
        $this->info('✅ Clé générée et enregistrée dans .env');
        $this->table(
            ['Clé', 'Valeur'],
            [[$key, $secret]]
        );
        $this->newLine();
        $this->warn('⚠  Important : ajoutez aussi cette variable dans votre .env.example (sans valeur) :');
        $this->line("   {$key}=");
        $this->newLine();
        $this->warn('⚠  Ne changez JAMAIS cette clé en production : toutes les attestations');
        $this->warn('   déjà émises deviendraient invérifiables.');
        $this->newLine();

        return self::SUCCESS;
    }

    private function extractCurrentValue(string $envContent, string $key): string
    {
        preg_match("/^{$key}=(.*)$/m", $envContent, $matches);
        return trim($matches[1] ?? '');
    }
}