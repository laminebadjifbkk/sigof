<?php
namespace App\Notifications;

use App\Models\Formation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EvaluationReminderDEC extends Notification
{
    use Queueable;

    public $formation;
    public $rappelNiveau;

    public function __construct(Formation $formation, $rappelNiveau = null)
    {
        $this->formation    = $formation;
        $this->rappelNiveau = $rappelNiveau;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {

        // Vérifie le nom du module
        $moduleFormation = $this->formation->module->name ?? ($this->formation->operateurmodule->module->name ?? 'Nom du module non défini');

        // Nom de l'ingénieur (associé à la formation)
        $ingenieur = $this->formation?->ingenieur?->name ?? 'Non défini';

        // Nom de l'opérateur d'exécution
        $operateur = $this->formation->operateur?->user?->operateur ?? 'Non défini';

        return (new MailMessage)
            ->subject("Rappel ({$this->rappelNiveau}) : Évaluation de la formation")
            ->line("Bonjour DEC,")
            ->line("📅 **{$this->rappelNiveau}** : L'évaluation de la formation en \"{$moduleFormation}\" est prévue le " . $this->formation->date_fin->format('d/m/Y') . " à " . $this->formation->lieu . ".")
            ->line("👤 Ingénieur responsable : {$ingenieur}")
            ->line("🏢 Opérateur d’exécution : {$operateur}")
            ->action('Voir la formation', route('formations.show', $this->formation))
            ->line('Merci de prendre les dispositions nécessaires.');
    }
}
