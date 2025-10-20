<?php
namespace App\Mail;

use App\Models\Formation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormationStartNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $formation;
    public $label;
    public $dateDebut;
    public $dateFin;
    public $periode; // Optionnel : texte déjà formaté

    public function __construct(Formation $formation, $label)
    {
        $this->formation = $formation;
        $this->label     = $label;

        // 🔹 Traitement des dates
        $this->dateDebut = $formation?->date_debut
            ? Carbon::parse($formation->date_debut)->format('d/m/Y')
            : null;

        $this->dateFin = $formation?->date_fin
            ? Carbon::parse($formation->date_fin)->format('d/m/Y')
            : null;

        // 🔹 Génération automatique du texte de période
        if ($this->dateDebut && $this->dateFin) {
            $this->periode = "Du {$this->dateDebut} au {$this->dateFin}";
        } elseif ($this->dateDebut && ! $this->dateFin) {
            $this->periode = "À partir du {$this->dateDebut}";
        } elseif (! $this->dateDebut && $this->dateFin) {
            $this->periode = "Jusqu’au {$this->dateFin}";
        } else {
            $this->periode = "Non définie";
        }
    }

    /* public function build()
    {
        return $this->subject('📢 Démarrage de votre formation : ' . $this->formation->intitule)
            ->view('emails.formation_start');
    } */

    public function build()
    {
        /* return $this->subject("Démarrage formation {$this->label}") */
        return $this->subject("Démarrage formation : " . $this->formation->intitule)
            ->view('emails.formation_start')
            ->with([
                'formation' => $this->formation,
                'label'     => $this->label,
                'dateDebut' => $this->dateDebut,
                'dateFin'   => $this->dateFin,
                'periode'   => $this->periode,
            ])
        /* ->attach(public_path('onfp.png'), [
                'as'   => 'logo.png',
                'mime' => 'image/png',
            ]) */;
    }
}
