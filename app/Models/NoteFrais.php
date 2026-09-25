<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteFrais extends Model
{
    use SoftDeletes;

    protected $table = 'notes_frais';

    protected $fillable = [
        'uuid', 'type', 'statut', 'session_label', 'periode_debut', 'periode_fin',
        'lieu', 'beneficiaires', 'banque_rib', 'taux_acompte',
        'formations_id', 'note_acompte_id', 'valide_par_id', 'date_validation',
    ];

    public function formation()
    {
        return $this->belongsTo(\App\Models\Formation::class, 'formations_id');
    }

    // Raccourci vers l'opérateur en passant par formations, sans duplication de FK.
    public function operateur()
    {
        return $this->hasOneThrough(
            \App\Models\Operateur::class,
            \App\Models\Formation::class,
            'id',            // formations.id
            'id',            // operateurs.id
            'formations_id', // notes_frais.formations_id
            'operateurs_id'  // formations.operateurs_id
        );
    }

    public function lignes()
    {
        return $this->hasMany(NoteFraisLigne::class, 'notes_frais_id');
    }

    public function noteAcompte()
    {
        return $this->belongsTo(self::class, 'note_acompte_id');
    }

    /**
     * Recalcule sous-totaux, total, acompte et reliquat.
     * Appelé automatiquement à chaque sauvegarde/suppression de ligne
     * (voir NoteFraisLigne::booted) — jamais fait manuellement en frontend.
     */
    public function recalculerTotaux(): void
    {
        $lignes = $this->lignes()->with('rubrique')->get();

        $sousTotalPedagogique = $lignes->where('rubrique.groupe', 'PEDAGOGIQUE')->sum('montant');
        $sousTotalAdministratif = $lignes->where('rubrique.groupe', 'ADMINISTRATIF')->sum('montant');
        $total = $sousTotalPedagogique + $sousTotalAdministratif;

        $montantAcompteRecu = 0;
        if ($this->type === 'DEFINITIVE' && $this->note_acompte_id) {
            // (B) est repris depuis la note d'acompte liée, jamais ressaisi.
            $montantAcompteRecu = $this->noteAcompte?->total_frais_operateur * ($this->noteAcompte?->taux_acompte ?? 0) / 100 ?? 0;
        }

        $this->sous_total_pedagogique = $sousTotalPedagogique;
        $this->sous_total_administratif = $sousTotalAdministratif;
        $this->total_frais_operateur = $total;
        $this->montant_acompte_recu = round($montantAcompteRecu, 2);
        // (A - B), ou 100% du total si aucun acompte n'a été versé
        $this->reliquat = round($total - $this->montant_acompte_recu, 2);

        $this->saveQuietly(); // évite de redéclencher un cycle d'events
    }

    /**
     * Montant de l'acompte à afficher sur la note ACOMPTE elle-même : total * taux%.
     */
    public function getMontantAcompteDemandeAttribute(): float
    {
        return round($this->total_frais_operateur * $this->taux_acompte / 100, 2);
    }
}
