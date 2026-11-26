@extends('support.stocks.layouts.pdf')

@section('titre', $titre)

@section('content')
    @php
        $libelles = [
            'RECEPTION' => 'BON DE RÉCEPTION',
            'SORTIE' => 'BON DE SORTIE',
            'RETOUR' => 'BON DE RETOUR',
            'AJUSTEMENT_POSITIF' => 'BON D’AJUSTEMENT POSITIF',
            'AJUSTEMENT_NEGATIF' => 'BON D’AJUSTEMENT NÉGATIF',
        ];

        $statuts = [
            'BROUILLON' => 'BROUILLON',
            'EN_ATTENTE_VALIDATION' => 'EN ATTENTE DE VALIDATION',
            'VALIDEE' => 'VALIDÉ',
            'REJETEE' => 'REJETÉ',
            'ANNULEE' => 'ANNULÉ',
        ];

        $nomEmploye = $document->employe
            ? trim(
                ($document->employe->user->firstname ?? '') . ' ' .
                ($document->employe->user->name ?? '')
            )
            : null;

        $destinataire = null;

        if ($document->employe) {
            $destinataire = $nomEmploye ?: (
                $document->employe->matricule ?? 'Employé'
            );
        } elseif ($document->direction) {
            $destinataire = 'Direction : ' . $document->direction->name;
        } elseif ($document->antenne) {
            $destinataire = 'Antenne : ' . (
                $document->antenne->name ?? $document->antenne->nom ?? ''
            );
        }

        $totalQuantite = $document->lignes->sum('quantite');
        $totalValeur = $document->lignes->sum('valeur_ligne');
    @endphp

    <div class="pdf-titre">
        {{ $libelles[$document->type_document] ?? $document->type_document }}
    </div>

    <div style="text-align:center; margin-bottom:12px;">
        <strong>Référence : {{ $document->reference }}</strong>
    </div>

    <div class="pdf-infos">
        <table style="margin-top:0;">
            <tr>
                <td width="50%">
                    <strong>Date du document :</strong>
                    {{ $document->date_document?->format('d/m/Y') ?? '—' }}
                </td>
                <td width="50%">
                    <strong>Statut :</strong>
                    {{ $statuts[$document->statut] ?? $document->statut }}
                </td>
            </tr>

            @if($document->fournisseur)
                <tr>
                    <td colspan="2">
                        <strong>Fournisseur :</strong>
                        {{ $document->fournisseur->raison_sociale }}
                    </td>
                </tr>
            @endif

            @if($document->type_document === 'SORTIE' && $destinataire)
                <tr>
                    <td colspan="2">
                        <strong>Destinataire :</strong>
                        {{ $destinataire }}
                    </td>
                </tr>
            @endif

            @if($document->ticket)
                <tr>
                    <td colspan="2">
                        <strong>Ticket associé :</strong>
                        {{ $document->ticket->reference ?? ('Ticket #' . $document->ticket->id) }}
                        @if($document->ticket->objet ?? false)
                            — {{ $document->ticket->objet }}
                        @endif
                    </td>
                </tr>
            @endif

            @if($document->motif)
                <tr>
                    <td colspan="2">
                        <strong>Motif :</strong>
                        {{ $document->motif }}
                    </td>
                </tr>
            @endif

            @if($document->observations)
                <tr>
                    <td colspan="2">
                        <strong>Observations :</strong>
                        {{ $document->observations }}
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div style="font-weight:bold; margin:12px 0 5px;">
        DÉTAIL DES ARTICLES
    </div>

    <table>
        <thead>
            <tr>
                <th width="7%">N°</th>
                <th width="27%">Article</th>
                <th width="12%">Référence</th>
                <th width="10%">Quantité</th>
                <th width="9%">Unité</th>
                <th width="15%">Coût unitaire</th>
                <th width="20%">Valeur totale</th>
            </tr>
        </thead>
        <tbody>
            @forelse($document->lignes as $index => $ligne)
                <tr>
                    <td class="pdf-texte-centre">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $ligne->article->designation ?? 'Article supprimé' }}</strong>

                        @if($ligne->observations)
                            <br>
                            <span style="font-size:8px;">
                                {{ $ligne->observations }}
                            </span>
                        @endif

                        @if($document->type_document === 'RETOUR' && $ligne->ligneOrigine)
                            <br>
                            <span style="font-size:8px;">
                                Sortie d'origine :
                                {{ $ligne->ligneOrigine->document->reference ?? '—' }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $ligne->article->reference ?? '—' }}</td>
                    <td class="pdf-texte-droite">
                        {{ number_format((float) $ligne->quantite, 3, ',', ' ') }}
                    </td>
                    <td class="pdf-texte-centre">
                        {{ $ligne->article->unite->symbole ?? '—' }}
                    </td>
                    <td class="pdf-texte-droite">
                        {{ number_format((float) $ligne->cout_unitaire, 4, ',', ' ') }}
                    </td>
                    <td class="pdf-texte-droite">
                        {{ number_format((float) $ligne->valeur_ligne, 2, ',', ' ') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="pdf-texte-centre">
                        Aucune ligne enregistrée.
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if($document->lignes->isNotEmpty())
            <tfoot>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <th class="pdf-texte-droite">
                        {{ number_format((float) $totalQuantite, 3, ',', ' ') }}
                    </th>
                    <th></th>
                    <th></th>
                    <th class="pdf-texte-droite">
                        {{ number_format((float) $totalValeur, 2, ',', ' ') }} FCFA
                    </th>
                </tr>
            </tfoot>
        @endif
    </table>

    @if($document->motif_rejet)
        <div class="pdf-infos" style="margin-top:12px;">
            <strong>Motif du rejet :</strong><br>
            {{ $document->motif_rejet }}
        </div>
    @endif

    <table class="pdf-signatures">
        <tr>
            <td>
                <strong>Établi par</strong>
                <br>
                {{ trim(($document->createur->firstname ?? '') . ' ' . ($document->createur->name ?? '')) ?: '—' }}
                <br><br><br>
                __________________________
            </td>
            <td>
                <strong>Validé par</strong>
                <br>
                {{ trim(($document->validateur->firstname ?? '') . ' ' . ($document->validateur->name ?? '')) ?: '—' }}
                <br><br><br>
                __________________________
            </td>
        </tr>
    </table>

    @if($document->date_validation)
        <div class="pdf-texte-centre" style="font-size:8px; margin-top:5px;">
            Date de validation :
            {{ $document->date_validation->format('d/m/Y à H:i') }}
        </div>
    @endif
@endsection
