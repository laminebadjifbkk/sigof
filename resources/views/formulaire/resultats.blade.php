@extends('layout.user-layout')
@section('title', 'ONFP | RESULTAT RECHERCHE')
@section('space-work')
    @can('inscriptioncontact-view')
        <section class="section register">
            <div class="row justify-content-center">
                <h4 class="card-title">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 p-3 bg-light rounded shadow-sm">
                        <span>Résult de la rechaerche</span>
                    </div>
                </h4>
                {{-- Tableau inscriptions --}}
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatables align-middle" id="table-inscriptions">
                                <thead class="table-primary">
                                    <tr>
                                        @foreach ($labels as $key => $label)
                                            <th>{{ $label }}</th>
                                        @endforeach
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($formulaires as $inscription)
                                        <tr>
                                            @foreach (array_keys($labels) as $field)
                                                <td>
                                                    @if (in_array($field, ['cin_file', 'facture_file', 'cv', 'diplome']))
                                                        @php
                                                            $fileUrl = $inscription->getFileUrl($field);
                                                        @endphp
                                                        @if ($fileUrl)
                                                            <a href="{{ $fileUrl }}" target="_blank"
                                                                class="btn btn-outline-secondary btn-sm" title="Télécharger">
                                                                <i class="bi bi-download"></i>
                                                            </a>
                                                        @else
                                                            -
                                                        @endif
                                                    @elseif ($field === 'date_naissance' && $inscription->date_naissance)
                                                        {{ \Carbon\Carbon::parse($inscription->date_naissance)->format('d/m/Y') }}
                                                    @else
                                                        {{ $inscription->$field ?? '-' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('formulaires.show', $inscription->id) }}"
                                                        class="btn btn-warning btn-sm" title="Voir les détails">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    <button type="button"
                                                        class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="visually-hidden">Actions</span>
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                        <li>
                                                            <a href="{{ route('formulaires.edit', $inscription->id) }}"
                                                                class="dropdown-item text-primary" title="Modifier les détails">
                                                                <i class="bi bi-pencil-square me-2"></i> Modifier
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <form action="{{ route('formulaires.destroy', $inscription->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger show_confirm">
                                                                    <i class="bi bi-trash me-2"></i> Supprimer
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endcan
@endsection
