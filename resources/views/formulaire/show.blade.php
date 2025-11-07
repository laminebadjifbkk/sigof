@extends('layout.user-layout')

@section('title', 'ONFP | Détail de l’inscription')

@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">

                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Détails de l’inscription</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $labels = [
                                    'cin' => 'Numéro CIN',
                                    'civilite' => 'Civilité',
                                    'prenom' => 'Prénom',
                                    'nom' => 'Nom',
                                    'date_naissance' => 'Date naissance',
                                    'lieu_naissance' => 'Lieu naissance',
                                    'email' => 'Adresse e-mail',
                                    'telephone' => 'Téléphone',
                                    'telephone_secondaire' => 'Téléphone secondaire',
                                    'adresse' => 'Adresse',
                                    'dernier_diplome' => 'Dernier diplôme obtenu',
                                    'nom_etablissement' => 'Établissement',
                                    'region' => 'Région',
                                    'formation' => 'Formation sollicitée',
                                    'diplome_vise' => 'Diplôme visé',
                                    'montant_inscription' => 'Montant inscription',
                                    'montant_mensualite' => 'Montant mensualité',
                                    'montant_unique' => 'Montant unique',
                                    'duree' => 'Durée (en années)',
                                    'handicape' => 'Situation de handicap',
                                    'type_handicap' => 'Type de handicap',
                                    'orphelin' => 'Orphelin',
                                    'type_orphelin' => 'Type d’orphelinat',
                                    'cin_file' => 'Copie CIN',
                                    'facture_file' => 'Facture',
                                    'cv' => 'CV',
                                    'diplome' => 'Diplôme',
                                    'statut' => 'Statut',
                                ];

                                $fileFields = ['cin_file', 'facture_file', 'cv', 'diplome'];
                            @endphp

                            <div class="row g-3">
                                @foreach ($labels as $field => $label)
                                    <div class="col-md-4">
                                        <strong>{{ $label }} :</strong><br>

                                        @if (in_array($field, $fileFields))
                                            @if (!empty($formulaire->$field))
                                                <a href="{{ asset('storage/' . $formulaire->$field) }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm mt-1">
                                                    <i class="bi bi-file-earmark-arrow-down"></i> Ouvrir
                                                </a>
                                            @else
                                                <span class="text-muted">Aucun fichier</span>
                                            @endif
                                        @elseif ($field === 'date_naissance' && $formulaire->$field)
                                            {{ \Carbon\Carbon::parse($formulaire->$field)->format('d/m/Y') }}
                                        @else
                                            {{ $formulaire->$field ?? '-' }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Boutons --}}
                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('formulaires.index') }}" class="btn btn-secondary btn-sm">
                                    Retour à la liste
                                </a>
                                <a href="{{ route('formulaires.edit', $formulaire->id) }}"
                                    class="btn btn-warning btn-sm">
                                    Modifier
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endcan
@endsection
