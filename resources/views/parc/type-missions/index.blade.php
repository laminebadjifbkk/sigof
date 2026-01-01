@extends('layout.user-layout')
@section('title', 'ONFP - Types de mission')

@section('space-work')
<section class="section register">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-0">Types de mission</h1>
            <a href="{{ route('parc-type-missions.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Ajouter un type
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <table class="table table-hover table-striped shadow-sm" id="table-type-mission">
            <thead class="table-dark">
                <tr>
                    <th width="5%">#</th>
                    <th>Libellé</th>
                    <th class="text-center" width="12%">Missions</th>
                    <th class="text-center" width="12%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($typesMissions as $type)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            <strong>{{ $type->libelle }}</strong>
                        </td>

                        <td class="text-center">
                            <span class="badge bg-info">
                                {{ $type->missions_count }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="d-flex justify-content-center gap-1">
                                <a href="{{ route('parc-type-missions.edit', $type) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('parc-type-missions.destroy', $type) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger show_confirm"
                                            {{ $type->missions_count > 0 ? 'disabled' : '' }}
                                            title="{{ $type->missions_count > 0 ? 'Type utilisé dans des missions' : 'Supprimer' }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</section>
@endsection

@push('scripts')
<script>
    new DataTable('#table-type-mission', {
        ordering: false,
        layout: {
            topStart: {
                buttons: ['csv', 'excel', 'print'],
            }
        },
        language: {
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun type trouvé",
            "sInfo": "Affichage de _START_ à _END_ sur _TOTAL_ types",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "oPaginate": {
                "sPrevious": "Précédent",
                "sNext": "Suivant"
            }
        }
    });
</script>
@endpush
