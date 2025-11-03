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
    ];

    protected $casts = [
        'montant_inscription' => 'decimal:2',
        'montant_mensualite'  => 'decimal:2',
        'montant_unique'      => 'decimal:2',
        'date_naissance'      => 'date',
    ];
}
