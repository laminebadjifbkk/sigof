@extends('layout.user-layout')

@section('title', 'Mes notifications')

@section('space-work')

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="bi bi-bell me-2"></i>
                Mes notifications
            </h4>

            @if ($notifications->where('lu_at', null)->count() > 0)
                <form method="POST" action="{{ route('onfp.notifications.tout-marquer-lu') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-check2-all me-1"></i>
                        Tout marquer comme lu
                    </button>
                </form>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">

                @forelse ($notifications as $notification)
                    <div
                        class="d-flex align-items-start gap-3 p-3 border-bottom {{ $notification->lu_at ? '' : 'bg-light' }}">

                        <div class="rounded-circle {{ $notification->priorite === 'urgente' ? 'bg-danger' : 'bg-primary' }} text-white
                                d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:40px;height:40px;">
                            <i
                                class="bi {{ $notification->type === 'echeance_proche' ? 'bi-alarm' : 'bi-arrow-repeat' }}"></i>
                        </div>

                        <div class="flex-grow-1" style="min-width: 0;">

                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="fw-semibold">
                                    {{ $notification->titre }}
                                    @if (!$notification->lu_at)
                                        <span class="badge bg-primary ms-1">Nouveau</span>
                                    @endif
                                </div>
                                <div class="small text-muted flex-shrink-0">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>

                            <div class="text-break mt-1">
                                {{ $notification->message }}
                            </div>

                            <div class="d-flex gap-2 mt-2">

                                @if ($notification->activite)
                                    <a href="{{ route('onfp.activites.show', $notification->activite) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Voir l'activité
                                    </a>
                                @endif

                                @if (!$notification->lu_at)
                                    <form method="POST"
                                        action="{{ route('onfp.notifications.marquer-lue', $notification) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            Marquer comme lu
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('onfp.notifications.destroy', $notification) }}"
                                    onsubmit="return confirm('Supprimer cette notification ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger">
                                        Supprimer
                                    </button>
                                </form>

                            </div>

                        </div>

                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-bell-slash fs-1 d-block mb-2"></i>
                        Aucune notification pour le moment.
                    </div>
                @endforelse

            </div>

            @if ($notifications->hasPages())
                <div class="card-footer bg-white">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection
