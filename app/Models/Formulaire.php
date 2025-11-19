<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formulaire extends Model
{
    use HasFactory;

    protected $table = 'formulaires';

    protected $fillable = [
        'cin',
        'civilite',
        'prenom',
        'nom',
        'date_naissance',
        'lieu_naissance',
        'email',
        'telephone',
        'telephone_secondaire',
        'adresse',
        'dernier_diplome',
        'nom_etablissement',
        'region',
        'formation',
        'diplome_vise',
        'montant_inscription',
        'montant_mensualite',
        'montant_unique',
        'duree',
        'handicape',
        'type_handicap',
        'orphelin',
        'type_orphelin',
        'facture_file',
        'cin_file',
        'diplome',
        'cv',
        'statut',
    ];

    protected $casts = [
        'montant_inscription' => 'decimal:2',
        'montant_mensualite'  => 'decimal:2',
        'montant_unique'      => 'decimal:2',
        'date_naissance'      => 'date',
    ];

    // Dans app/Models/Formulaire.php
    public function getFileUrl($field)
    {
        $filePath = $this->$field ?? null;
        if ($filePath) {
            return asset('storage/' . $filePath);
        }
        return null;
    }

    public function historiques()
    {
        return $this->hasMany(HistoriquePriseEnCharge::class);
    }
}
