<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteFraisLigne extends Model
{
    use SoftDeletes;

    protected $table = 'note_frais_lignes';

    protected $fillable = ['uuid', 'unite', 'qte', 'pu', 'notes_frais_id', 'rubriques_id'];

    protected static function booted()
    {
        // Le montant d'une ligne n'est jamais saisi : toujours qte * pu.
        static::saving(function (NoteFraisLigne $ligne) {
            $ligne->montant = round(($ligne->qte ?? 0) * ($ligne->pu ?? 0), 2);
        });

        // Toute modif de ligne doit recalculer les totaux de la note parente.
        static::saved(fn (NoteFraisLigne $ligne) => $ligne->noteFrais?->recalculerTotaux());
        static::deleted(fn (NoteFraisLigne $ligne) => $ligne->noteFrais?->recalculerTotaux());
    }

    public function noteFrais()
    {
        return $this->belongsTo(NoteFrais::class, 'notes_frais_id');
    }

    public function rubrique()
    {
        return $this->belongsTo(Rubrique::class, 'rubriques_id');
    }
}
