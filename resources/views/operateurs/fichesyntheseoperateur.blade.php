{{-- <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">
    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="apple-touch-icon">
    <link rel="stylesheet" href="{{ asset('css/statuts.css') }}">
    <style>
        @page {
            margin: 0cm 0cm;
        }
        .Valide {
            color: #28A745;
            /* vert */
            font-weight: 600;
            /* facultatif, juste pour mettre en gras */
        }

        .Rejete {
            color: #DC3545;
            /* rouge */
            font-weight: 600;
        }

        .Autre {
            color: #272323;
            /* cyan */
            font-weight: 600;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding-top: 0px;
            padding-bottom: 25px;
            padding-left: 25px;
            padding-right: 25px;
            border: 0px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 12px;
            line-height: 20px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            /* color: #555; */
        }

        .rtl {
            imputation: rtl;
        }

        .invoice-box table tr.heading td {
            background: rgb(255, 255, 255);
            border: 0px solid #000000;
            border-collapse: collapse;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            border-left: 1px solid rgb(0, 0, 0);
            border-right: 1px solid rgb(0, 0, 0);
            border-top: 1px solid rgb(0, 0, 0);
            border-bottom: 1px solid rgb(0, 0, 0);
            border: 1px solid;
        }

        .Oui {
            color: #198754;
            text-align: center;
        }

        .Non {
            color: #DC3545;
            padding: 4px 8px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 0.5cm;

            background-color: #ffffff;
            color: #000;
            font-size: 12px;
            font-family: Arial, sans-serif;
            text-align: center;

            z-index: 1000;
        }

        .page-number {
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            align-items: center;
            padding-bottom: 0.2cm;
        }
        .footer-line {
            width: 25cm;
            height: 2px;
            background-color: #5D4037;
            margin: 0 auto 2mm auto;
        }
        .footer-text {
            margin: 0;
            padding: 1mm 0 0 0;
            line-height: 1.4;
            max-width: 27cm;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//db.onlinewebfonts.com/c/dd79278a2e4c4a2090b763931f2ada53?family=ArialW02-Regular" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div style="text-align: center;">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/entete.png'))) }}"
            style="width: 100%; max-width: 370px" />
    </div>
    <div class="invoice-box">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="heading">
                        <td colspan="5">DOSSIER D'AGREMENT N° : <span
                                style="color: red">{{ $operateur?->numero_dossier }}</span></td>
                        <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                        <td style="text-align: right" colspan="4">COURRIER ARRIVEE N° : <span
                                style="color: red">{{ $operateur?->numero_arrive }}</span></td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="item">
                        <td><b>{{ __("DENOMINATION DE L'OPERATEUR") }}</b></td>
                        <td colspan="8">
                            {{ $operateur?->user?->display_operateur }}
                        </td>
                        <td colspan="2" width="10%" style="text-align: center;">
                            <b>{{ __('CONFORMITE') }}</b>
                        </td>
                    </tr>
                    <tr class="item">
                        <td><b>{{ __('RESPONSABLE') }}</b></td>
                        <td colspan="8">
                            @if (!empty($operateur?->user?->firstname))
                                {{ $operateur?->user?->firstname }}
                            @endif
                            @if (!empty($operateur?->user?->name))
                                {{ $operateur?->user?->name }}
                            @endif
                        </td>
                        <td width="10%" style="text-align: center;">
                            @if (!empty($operateur?->visite_conformite) && $operateur?->visite_conformite === 'Oui')
                                <span
                                    class="{{ $operateur?->visite_conformite }}">{{ $operateur?->visite_conformite }}</span>
                            @endif
                        </td>
                        <td width="10%" style="text-align: center;">
                            @if (!empty($operateur?->visite_conformite) && $operateur?->visite_conformite === 'Non')
                                <span
                                    class="{{ $operateur?->visite_conformite }}">{{ $operateur?->visite_conformite }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr class="item">
                        <td><b>{{ __('CIVILITE') }}</b></td>
                        <td colspan="8">
                            @if (!empty($operateur?->user?->civilite))
                                {{ $operateur?->user?->civilite }}
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="item">
                        <td><b>{{ __('TITRE') }}</b></td>
                        <td colspan="8">
                            @if (!empty($operateur?->user?->fonction_responsable))
                                {{ $operateur?->user?->fonction_responsable }}
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="item">
                        <td><b>{{ __('ADRESSE') }}</b></td>
                        <td colspan="8">
                            @if (!empty($operateur?->user?->adresse))
                                {{ $operateur?->user?->adresse }}
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="item">
                        <td><b>{{ __('CONTACTS') }}</b></td>
                        <td colspan="3">
                            @if (!empty($operateur?->user?->fixe))
                                Tél 1 : <a href="tel:+221{{ $operateur?->user?->fixe }}">
                                    {{ $operateur?->user?->fixe }}
                                </a>
                            @endif
                            <br>
                            @if (!empty($operateur?->user?->telephone))
                                Tél 2 : <a href="tel:+221{{ $operateur?->user?->telephone }}">
                                    {{ $operateur?->user?->telephone }}
                                </a>
                            @endif
                            <br>
                            @if (!empty($operateur?->user?->telephone_parent) && $operateur?->user?->telephone_parent != $operateur?->user?->telephone)
                                Tél 3 : <a
                                    href="tel:+221{{ $operateur?->user?->telephone_parent }}">{{ $operateur?->user?->telephone_parent }}</a>
                            @endif
                        </td>
                        <td colspan="5">
                            @if (!empty($operateur?->user?->email))
                                Email 1 : <a
                                    href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>
                            @endif
                            <br>
                            @if (!empty($operateur?->user->email_responsable))
                                Email 2 : <a
                                    href="mailto:{{ $operateur?->user?->email_responsable }}">{{ $operateur?->user?->email_responsable }}</a>
                            @endif
                            <br>
                            @if (!empty($operateur?->user?->web))
                                Web : {{ $operateur?->user?->web }}
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr class="item">
                        <td><b>{{ __('STATUT JURIDIQUE') }}</b></td>
                        <td colspan="8">
                            @if (!empty($operateur?->statut))
                                {{ $operateur?->statut }}
                            @endif
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    @php
                        $validFiles = $operateur?->user?->files->filter(fn($file) => !empty($file?->file));
                    @endphp

                    <tr class="item">
                        <td rowspan="5"><b>{{ __('DOSSIERS FOURNIS') }}</b></td>
                        <td colspan="3" rowspan="3">
                            Actes de création de l'entreprise
                        </td>
                        <td colspan="5">
                            RCCM / NINEA : {{ $operateur?->user?->rccm }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr class="item">
                        <td colspan="5">
                            Numéro : <span style="color: red">{{ $operateur?->user?->ninea }}</span>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr class="item">
                        <td colspan="5">
                            <b>Fichiers joints</b>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mt-2">
                                    <thead>
                                        <tr>
                                            <th>Fichier</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($validFiles as $file)
                                            <tr>
                                                <td>{{ $file?->legende }}</td>
                                                <td><span
                                                        class="{{ $file?->statut === 'Validé' ? 'Valide' : ($file?->statut === 'Rejeté' ? 'Rejete' : 'Autre') }}">
                                                        {{ $file?->statut }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr class="item">
                        <td colspan="8">
                            CV formateur(s) daté(s) et signé(s) :
                            {{ count($operateur->operateurformateurs) ?? 0 }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr class="item">
                        <td colspan="8">
                            Quitus fiscal ou récépissé de dépôt du quitus fiscal (date visa) :
                            {{ $operateur?->debut_quitus?->format('d/m/Y') ?? '' }}
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="page-break-after: always;"></div>

        <div class="table-responsive">
            <table class="table table-bordered pt-10" width="100%">
                <tbody>
                    <tr class="item">
                        <td width="20%" style="text-align: center;"
                            rowspan="{{ count($operateur?->operateurmodules) + 1 }}">
                            <b>{{ __("DOMAINES D'INTERVENTION") }}</b>
                        </td>

                        <td colspan="3" width="25%" style="text-align: center;">
                            <b>DOMAINE</b>
                        </td>

                        <td colspan="4" width="35%" style="text-align: center;">
                            <b>MODULE</b>
                        </td>

                        <td colspan="2" width="20%" style="text-align: center;">
                            <b>{{ __('CONFORMITE') }}</b>
                        </td>
                    </tr>
                    @foreach ($operateur?->operateurmodules as $operateurmodule)
                        <tr class="item">
                            <td colspan="3" style="text-align: center;">
                                {{ $operateurmodule?->domaine }}
                            </td>

                            <td colspan="4" style="text-align: center;">
                                {{ $operateurmodule?->module }}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr class="item">
                        <td width="20%" style="text-align: center;"
                            rowspan="{{ count($operateur->operateurformateurs) + 1 }}">
                            <b>{{ __('FORMATEURS') }}</b>
                        </td>

                        <td colspan="3" width="25%" style="text-align: center;">
                            <b>Nom formateur</b>
                        </td>

                        <td colspan="4" width="35%" style="text-align: center;">
                            <b>Champs profes. et années d'expérience</b>
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach ($operateur?->operateurformateurs as $operateurformateur)
                        <tr class="item">
                            <td colspan="3" style="text-align: center;">
                                {{ $operateurformateur?->name }}
                            </td>

                            <td colspan="4" style="text-align: center;">
                                {{ $operateurformateur?->domaine . ' (' . $operateurformateur?->nbre_annees_experience . ' ans)' }}
                            </td>
                            <td style="text-align: center;">
                                @if ($operateurformateur?->statut === 'Oui')
                                    <span class="Oui">{{ $operateurformateur?->statut }}</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if ($operateurformateur?->statut === 'Non')
                                    <span class="Non">{{ $operateurformateur?->statut }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div style="page-break-after: always;"></div>

        <div class="table-responsive">
            <table class="table table-bordered pt-10">
                <tbody>
                    <tr class="item">
                        <td width="20%" style="text-align: center;">
                            <b>{{ __('MOYENS PEDAGOGIQUES') }}</b>
                        </td>
                        <td colspan="6" width="100%">
                            <b>DESIGNATION</b>
                        </td>
                        <td style="text-align: center">
                            <b>Qté</b>
                        </td>
                        <td style="text-align: center">
                            <b>Etat</b>
                        </td>
                        <td colspan="2" width="20%" style="text-align: center;">
                            <b>{{ __('CONFORMITE') }}</b>
                        </td>
                    </tr>
                    @foreach ($operateur?->operateurequipements?->sortBy('type') as $operateurequipement)
                        <tr class="item">
                            <td style="text-align: center;">
                                {{ $operateurequipement?->type }}
                            </td>
                            <td colspan="6">
                                {{ $operateurequipement?->designation }} <br>
                            </td>
                            <td style="text-align: center">
                                {{ $operateurequipement?->quantite }}
                            </td>
                            <td style="text-align: center">
                                {{ $operateurequipement?->etat }}
                            </td>
                            <td>
                                @if ($operateurequipement?->statut === 'Oui')
                                    <span class="Oui">{{ $operateurequipement?->statut }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($operateurequipement?->statut === 'Non')
                                    <span class="Non">{{ $operateurequipement?->statut }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div style="page-break-after: always;"></div>

        <div class="table-responsive">
            <table class="table table-bordered pt-10">
                <tbody>

                    <tr class="item">
                        <td width="20%" style="text-align: center;">
                            <b>{{ __('EXPERIENCES') }}</b>
                        </td>
                        <td colspan="7" style="text-align: center;">
                            <b>Référence & Activités</b>
                        </td>
                        <td colspan="2" width="10%" style="text-align: center;">
                            <b>{{ __('CONFORMITE') }}</b>
                        </td>
                    </tr>
                    @foreach ($operateur?->operateureferences as $operateureference)
                        <tr class="item">
                            <td style="text-align: center;">
                                <a
                                    href="tel:+221{{ $operateureference?->contact }}">{{ $operateureference?->contact }}</a>
                            </td>
                            <td colspan="7">
                                {{ ' - ' . $operateureference?->organisme . ',' }}
                                {{ $operateureference?->periode }} <br>
                                {{ ' - ' . $operateureference?->description }}
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endforeach

                    <tr class="item">
                        <td><b>{{ __('OBSERVATIONS') }}</b></td>
                        <td colspan="9">
                            @if (!empty($operateur?->observations))
                                @foreach (explode("\n", mb_strtoupper($operateur?->observations)) as $line)
                                    {{ $line }}<br>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html> --}}

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>

    <link href="{{ asset('assets/img/favicon-onfp.png') }}" rel="icon">

    <style>
        @page {
            margin: 0cm 0cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        .heading {
            font-weight: bold;
            background: #f5f5f5;
        }

        .section-title {
            background: #eee;
            font-weight: bold;
            text-align: center;
        }

        .Oui {
            color: #198754;
            font-weight: bold;
        }

        .Non {
            color: #DC3545;
            font-weight: bold;
        }

        .Valide {
            color: #28A745;
            font-weight: bold;
        }

        .Rejete {
            color: #DC3545;
            font-weight: bold;
        }

        .Autre {
            color: #333;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="invoice-box">

        {{-- HEADER --}}
        <div style="text-align:center; margin-bottom:20px;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('assets/img/entete.png'))) }}"
                style="width: 100%; max-width: 380px;" />
        </div>

        {{-- TABLE PRINCIPALE --}}
        <table>

            {{-- DOSSIER --}}
            <tr class="heading">
                <td colspan="6">
                    DOSSIER N° : <span style="color:red">{{ $operateur?->numero_dossier }}</span>
                </td>
                <td colspan="6" style="text-align:right;">
                    COURRIER N° : <span style="color:red">{{ $operateur?->numero_arrive }}</span>
                </td>
            </tr>

            {{-- OPERATEUR --}}
            <tr>
                <td><b>Dénomination</b></td>
                <td colspan="9">{{ $operateur?->user?->display_operateur }}</td>
                <td colspan="2" style="width: 11%; text-align:center;">Conformité</td>
            </tr>

            <tr>
                <td><b>Responsable</b></td>
                <td colspan="9">
                    {{ $operateur?->user?->civilite }} {{ $operateur?->user?->firstname }}
                    {{ $operateur?->user?->name }}
                </td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
            </tr>

            {{-- <tr>
                <td><b>Civilité</b></td>
                <td colspan="9">{{ $operateur?->user?->civilite }}</td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
            </tr> --}}

            <tr>
                <td><b>Titre</b></td>
                <td colspan="9">{{ $operateur?->user?->fonction_responsable }}</td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
            </tr>

            <tr>
                <td><b>Adresse</b></td>
                <td colspan="5">{{ $operateur?->user?->adresse }}</td>
                <td colspan="2">{{ $operateur?->departement->nom }}</td>
                <td colspan="2">{{ $operateur?->departement?->region?->nom }}</td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
            </tr>

            {{-- CONTACTS --}}
            <tr>
                <td><b>Contacts</b></td>
                <td colspan="3">
                    @if ($operateur->numero)
                        <a href="tel:+221{{ str_replace('/', '', $operateur->numero) }}"
                            style="text-decoration: none;">
                            {{ $operateur->numero }}
                        </a>
                    @else
                        <span class="text-muted fst-italic">Aucun numéro</span>
                    @endif
                </td>

                <td colspan="3"><a
                        href="mailto:{{ $operateur?->user?->email }}">{{ $operateur?->user?->email }}</a>

                </td>
                <td colspan="3"><a href="{{ $operateur?->user?->web }}" target="_blank"
                        rel="noopener noreferrer">{{ $operateur?->user?->web ?? '-' }}</a>
                </td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
            </tr>

            {{-- STATUT --}}
            <tr>
                <td><b>Statut juridique</b></td>
                <td colspan="9">{{ $operateur?->user?->statut }}</td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
            </tr>

            {{-- ================= DOSSIERS ================= --}}
            <tr class="section-title">
                <td colspan="12">DOSSIERS FOURNIS</td>
            </tr>

            <tr>
                <td><b>N° NINEA</b></td>
                <td colspan="9">
                    <span style="color:red">{{ $operateur?->user?->ninea ?? '-' }}</span>
                </td>
                <td style="width: 6%; text-align:center;"><b></b></td>
                <td style="width: 6%; text-align:center;"><b></b></td>
            </tr>

            {{-- FICHIERS --}}
            <tr class="section-title">
                <td colspan="12">FICHIERS JOINTS</td>
            </tr>

            @forelse ($validFiles as $file)
                <tr>
                    <td colspan="8">{{ $file->legende }}</td>
                    <td colspan="2">
                        @if ($file->statut === 'Validé')
                            <span class="Valide">Validé</span>
                        @elseif ($file->statut === 'Rejeté')
                            <span class="Rejete">Rejeté</span>
                        @else
                            <span class="Autre">{{ $file->statut ?? 'Inconnu' }}</span>
                        @endif
                    </td>
                    <td style="width: 6%; text-align:center;"><b></b></td>
                    <td style="width: 6%; text-align:center;"><b></b></td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align:center;">Aucun fichier</td>
                    <td style="width: 6%; text-align:center;"><b></b></td>
                    <td style="width: 6%; text-align:center;"><b></b></td>
                </tr>
            @endforelse

        </table>

        <div style="page-break-after: always;"></div>

        {{-- ================= DOMAINES ================= --}}
        <table>

            <tr class="section-title">
                <td colspan="12">DOMAINES D'INTERVENTION</td>
            </tr>

            <tr>
                <td colspan="4" style="width: 35%;"><b>DOMAINE</b></td>
                <td colspan="6" style="width: 53%;"><b>MODULE</b></td>
                <td colspan="2" style="width: 11%; text-align:center;">Conformité</td>
            </tr>

            {{-- <tr>
                <td colspan="4"></td>
                <td colspan="6"></td>
                <td style="width: 6%; text-align:center;"><b>Oui</b></td>
                <td style="width: 6%; text-align:center;"><b>Non</b></td>
            </tr> --}}

            @foreach ($operateur?->operateurmodules as $module)
                <tr>
                    <td colspan="4" style="width: 35%;">{{ $module->domaine }}</td>
                    <td colspan="6" style="width: 53%;">{{ $module->module }}</td>

                    <td style="width: 6%; text-align:center;">
                        @if ($module->statut === 'Oui')
                            <span class="Oui">✔</span>
                        @endif
                    </td>

                    <td style="width: 6%; text-align:center;">
                        @if ($module->statut === 'Non')
                            <span class="Non">✔</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

        <div style="page-break-after: always;"></div>

        {{-- ================= FORMATEURS ================= --}}
        <table style="table-layout: fixed; width: 100%;">

            <tr class="section-title">
                <td colspan="12">FORMATEURS</td>
            </tr>

            <tr>
                <td colspan="4" style="width: 35%;"><b>NOM</b></td>
                <td colspan="6" style="width: 53%;"><b>EXPÉRIENCE</b></td>
                <td colspan="2" style="width: 11%; text-align:center;">Conformité</td>
            </tr>

            <tr>
                <td colspan="4"></td>
                <td colspan="6"></td>
                <td style="width: 6%; text-align:center;"><b>Oui</b></td>
                <td style="width: 6%; text-align:center;"><b>Non</b></td>
            </tr>

            @foreach ($operateur?->operateurformateurs as $formateur)
                <tr>
                    <td colspan="4" style="width: 35%;">{{ $formateur->name }}</td>
                    <td colspan="6" style="width: 53%;">
                        {{ $formateur->domaine }} ({{ $formateur->nbre_annees_experience }} ans)
                    </td>

                    <td style="width: 6%; text-align:center;">
                        @if ($formateur->statut === 'Oui')
                            <span class="Oui">✔</span>
                        @endif
                    </td>

                    <td style="width: 6%; text-align:center;">
                        @if ($formateur->statut === 'Non')
                            <span class="Non">✔</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

        <div style="page-break-after: always;"></div>

        {{-- ================= EQUIPEMENTS ================= --}}
        <table style="table-layout: fixed; width: 100%;">

            <tr class="section-title">
                <td colspan="12">MOYENS PÉDAGOGIQUES</td>
            </tr>

            <tr>
                <td colspan="4" style="width: 35%;"><b>TYPE</b></td>
                <td colspan="4" style="width: 35%;"><b>DÉSIGNATION</b></td>
                <td style="width: 8%;"><b>QTÉ</b></td>
                <td style="width: 8%;"><b>ÉTAT</b></td>
                <td colspan="2" style="width: 11%; text-align:center;">Conformité</td>
            </tr>

            <tr>
                <td colspan="4"></td>
                <td colspan="4"></td>
                <td></td>
                <td></td>
                <td style="text-align:center;"><b>Oui</b></td>
                <td style="text-align:center;"><b>Non</b></td>
            </tr>

            @foreach ($operateur?->operateurequipements as $eq)
                <tr>
                    <td colspan="4" style="width: 35%;">{{ $eq->type }}</td>
                    <td colspan="4" style="width: 35%;">{{ $eq->designation }}</td>
                    <td style="text-align:center;">{{ $eq->quantite }}</td>
                    <td style="text-align:center;">{{ $eq->etat }}</td>

                    <td style="text-align:center;">
                        @if ($eq->statut === 'Oui')
                            <span class="Oui">✔</span>
                        @endif
                    </td>

                    <td style="text-align:center;">
                        @if ($eq->statut === 'Non')
                            <span class="Non">✔</span>
                        @endif
                    </td>
                </tr>
            @endforeach

        </table>

        <div style="page-break-after: always;"></div>

        {{-- ================= REFERENCES ================= --}}
        <table style="width:100%; border-collapse: collapse; table-layout: fixed;">

            <tr class="section-title">
                <td colspan="12">EXPÉRIENCES</td>
            </tr>

            @foreach ($operateur?->operateureferences as $ref)
                <tr>
                    <td colspan="4" style="vertical-align: top; padding: 5px; white-space: nowrap; width: 120px;">
                        {{ $ref->contact }}
                    </td>

                    <td colspan="8" style="vertical-align: top; padding: 8px; line-height: 20px;">
                        {{ $ref->organisme }} - {{ $ref->periode }} <br>
                        {!! nl2br(e($ref->description)) !!}
                    </td>
                </tr>
            @endforeach

        </table>
        <table style="width: 100%; border-collapse: collapse;">

            <tr class="section-title">
                <td colspan="12">OBSERVATIONS</td>
            </tr>

            @if (!empty($operateur?->observations))
                <tr>
                    <td colspan="12" style="padding: 8px;">
                        {!! nl2br(e(strtoupper($operateur?->observations))) !!}
                    </td>
                </tr>
            @endif

            {{-- Grand espace sans bordures internes --}}
            <tr>
                <td colspan="12" style="height: 200px;"></td>
            </tr>

        </table>

        <br><br>

        <table style="width: 100%; border-collapse: collapse; border: none;">

            {{-- Ligne "Fait à ..." --}}
            <tr>
                <td colspan="2" style="border: none; text-align: right; padding-bottom: 40px;">
                    Fait à ................................................................, le ...../......./........
                </td>
            </tr>

            {{-- Signatures --}}
            <tr>
                <td style="border: none; width: 50%; text-align: left;">
                    <b>Signature ONFP</b>
                </td>

                <td style="border: none; width: 50%; text-align: center;">
                    <b>Signature de l'Opérateur</b>
                </td>
            </tr>

            {{-- Espaces signatures --}}
            <tr>
                <td style="border: none; height: 100px;"></td>
                <td style="border: none;"></td>
            </tr>

        </table>

    </div>

</body>

</html>
