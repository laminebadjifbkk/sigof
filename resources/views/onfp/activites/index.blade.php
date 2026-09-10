@extends('layouts.app')

@section('title', 'Activités')

@section('content')

<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h3 mb-1">
                Pilotage des activités
            </h1>

            <p class="text-muted mb-0">
                Suivi des activités et actions de l'ONFP
            </p>
        </div>

        <a
            href="{{ route('onfp.activites.create') }}"
            class="btn btn-primary"
        >
            + Nouvelle activité
        </a>

    </div>


    {{-- Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    {{-- Filtres --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('onfp.activites.index') }}"
            >

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label">
                            Recherche
                        </label>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="form-control"
                            placeholder="Référence, titre..."
                        >

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Direction
                        </label>

                        <select
                            name="direction_id"
                            class="form-select"
                        >

                            <option value="">
                                Toutes
                            </option>

                            @foreach($directions as $direction)

                                <option
                                    value="{{ $direction->id }}"
                                    @selected(
                                        request('direction_id') == $direction->id
                                    )
                                >
                                    {{ $direction->sigle ?: $direction->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Type
                        </label>

                        <select
                            name="type_id"
                            class="form-select"
                        >

                            <option value="">
                                Tous
                            </option>

                            @foreach($types as $type)

                                <option
                                    value="{{ $type->id }}"
                                    @selected(
                                        request('type_id') == $type->id
                                    )
                                >
                                    {{ $type->libelle }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Statut
                        </label>

                        <select
                            name="statut"
                            class="form-select"
                        >

                            <option value="">
                                Tous
                            </option>

                            <option
                                value="a_faire"
                                @selected(request('statut') === 'a_faire')
                            >
                                À faire
                            </option>

                            <option
                                value="en_cours"
                                @selected(request('statut') === 'en_cours')
                            >
                                En cours
                            </option>

                            <option
                                value="suspendue"
                                @selected(request('statut') === 'suspendue')
                            >
                                Suspendue
                            </option>

                            <option
                                value="terminee"
                                @selected(request('statut') === 'terminee')
                            >
                                Terminée
                            </option>

                            <option
                                value="annulee"
                                @selected(request('statut') === 'annulee')
                            >
                                Annulée
                            </option>

                        </select>

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Santé
                        </label>

                        <select
                            name="etat_sante"
                            class="form-select"
                        >

                            <option value="">
                                Tous
                            </option>

                            <option
                                value="normal"
                                @selected(request('etat_sante') === 'normal')
                            >
                                Normal
                            </option>

                            <option
                                value="a_surveiller"
                                @selected(request('etat_sante') === 'a_surveiller')
                            >
                                À surveiller
                            </option>

                            <option
                                value="risque"
                                @selected(request('etat_sante') === 'risque')
                            >
                                Risque
                            </option>

                            <option
                                value="critique"
                                @selected(request('etat_sante') === 'critique')
                            >
                                Critique
                            </option>

                        </select>

                    </div>


                    <div class="col-md-1 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Filtrer
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- Tableau --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Activités
                </h5>

                <span class="badge bg-secondary">
                    {{ $activites->total() }}
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Référence</th>

                        <th>Activité</th>

                        <th>Direction</th>

                        <th>Responsable</th>

                        <th>Progression</th>

                        <th>Statut</th>

                        <th>Santé</th>

                        <th>Échéance</th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($activites as $activite)

                        <tr>

                            <td>
                                <strong>
                                    {{ $activite->reference }}
                                </strong>
                            </td>


                            <td>

                                <a
                                    href="{{ route(
                                        'onfp.activites.show',
                                        $activite
                                    ) }}"
                                    class="text-decoration-none"
                                >
                                    {{ $activite->titre }}
                                </a>

                                @if($activite->type)
                                    <div>
                                        <small class="text-muted">
                                            {{ $activite->type->libelle }}
                                        </small>
                                    </div>
                                @endif

                            </td>


                            <td>

                                @if($activite->direction)

                                    <span>
                                        {{ $activite->direction->sigle
                                            ?: $activite->direction->name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td>

                                @php
                                    $principal = $activite
                                        ->responsables
                                        ->firstWhere('is_principal', true);
                                @endphp

                                @if($principal && $principal->employee)

                                    {{ $principal->employee->user?->name
                                        ?? $principal->employee->matricule }}

                                @else

                                    <span class="text-muted">
                                        Non affecté
                                    </span>

                                @endif

                            </td>


                            <td style="min-width: 130px">

                                <div class="d-flex justify-content-between">

                                    <small>
                                        {{ $activite->progression }}%
                                    </small>

                                </div>

                                <div
                                    class="progress"
                                    style="height: 6px"
                                >

                                    <div
                                        class="progress-bar"
                                        role="progressbar"
                                        style="width: {{ $activite->progression }}%"
                                    ></div>

                                </div>

                            </td>


                            <td>

                                @php
                                    $statutClasses = [
                                        'a_faire'   => 'secondary',
                                        'en_cours'  => 'primary',
                                        'suspendue' => 'warning',
                                        'terminee'  => 'success',
                                        'annulee'   => 'dark',
                                    ];

                                    $statutLabels = [
                                        'a_faire'   => 'À faire',
                                        'en_cours'  => 'En cours',
                                        'suspendue' => 'Suspendue',
                                        'terminee'  => 'Terminée',
                                        'annulee'   => 'Annulée',
                                    ];
                                @endphp

                                <span class="badge bg-{{
                                    $statutClasses[$activite->statut]
                                    ?? 'secondary'
                                }}">
                                    {{ $statutLabels[$activite->statut]
                                        ?? $activite->statut }}
                                </span>

                            </td>


                            <td>

                                @php
                                    $santeClasses = [
                                        'normal'       => 'success',
                                        'a_surveiller' => 'warning',
                                        'risque'       => 'orange',
                                        'critique'     => 'danger',
                                    ];

                                    $santeLabels = [
                                        'normal'       => 'Normal',
                                        'a_surveiller' => 'À surveiller',
                                        'risque'       => 'Risque',
                                        'critique'     => 'Critique',
                                    ];
                                @endphp

                                <span class="badge bg-{{
                                    $santeClasses[$activite->etat_sante]
                                    ?? 'secondary'
                                }}">
                                    {{ $santeLabels[$activite->etat_sante]
                                        ?? $activite->etat_sante }}
                                </span>

                            </td>


                            <td>

                                @if($activite->date_fin_prevue)

                                    {{ $activite
                                        ->date_fin_prevue
                                        ->format('d/m/Y') }}

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            <td class="text-end">

                                <div class="btn-group">

                                    <a
                                        href="{{ route(
                                            'onfp.activites.show',
                                            $activite
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Voir"
                                    >
                                        Voir
                                    </a>

                                    <a
                                        href="{{ route(
                                            'onfp.activites.edit',
                                            $activite
                                        ) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Modifier"
                                    >
                                        Modifier
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'onfp.activites.destroy',
                                            $activite
                                        ) }}"
                                        onsubmit="return confirm(
                                            'Voulez-vous vraiment supprimer cette activité ?'
                                        );"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            Supprimer
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5"
                            >

                                <div class="text-muted">

                                    <h5>
                                        Aucune activité trouvée
                                    </h5>

                                    <p>
                                        Commencez par créer une nouvelle activité.
                                    </p>

                                    <a
                                        href="{{ route(
                                            'onfp.activites.create'
                                        ) }}"
                                        class="btn btn-primary"
                                    >
                                        Nouvelle activité
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($activites->hasPages())

            <div class="card-footer">

                {{ $activites->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
