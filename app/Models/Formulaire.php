<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'certificat_file',
        'responsable_etablieement',
        'adresse_etablessement',
        'telephone_etablissement',
        'annee_scolaire',
        'montant_onfp',
        'statut_certificat',
        'users_id',
        'created_by',
        'update_by',
        'autre_1',
        'autre_2',

    ];

    protected $casts = [
        'montant_inscription' => 'decimal:2',
        'montant_mensualite'  => 'decimal:2',
        'montant_unique'      => 'decimal:2',
        'date_naissance'      => 'date',
    ];

  protected static function booted()
{
    static::creating(function ($f) {
        $f->uuid ??= (string) Str::uuid();
    });

    // Répare automatiquement une ancienne ligne sans uuid lors d'une mise à jour
    static::saving(function ($f) {
        if (empty($f->uuid)) {
            $f->uuid = (string) Str::uuid();
        }
    });
}

    public function getRouteKeyName()
    {
        return 'uuid';
    }

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

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'update_by');
    }
}
