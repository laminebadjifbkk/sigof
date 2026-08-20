@auth
    <div class="modal fade modal-top-right" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content sigof-modal">
                <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Fermer">✕</button>
                <div class="modal-body">
                    <div class="modal-user">
                        <div class="avatar-bubble avatar-bubble--lg">
                            {{-- {{ Str::upper(Str::substr(Auth::user()->firstname, 0, 1)).''.Str::upper(Str::substr(Auth::user()->name, 0, 1)) }} --}}
                            {{-- <img src="{{ asset(Auth::user()->getImage()) }}" alt="Profil" class="nav-profile-image"> --}}
                            <div class="avatar-bubble avatar-bubble--lg">
                                @if (Auth::user()->getImage())
                                    <img src="{{ asset(Auth::user()->getImage()) }}" alt="Profil"
                                        class="nav-profile-image">
                                @else
                                    {{ Str::upper(Str::substr(Auth::user()->firstname, 0, 1)) . Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}
                                @endif
                            </div>
                        </div>
                        <div>
                            <p id="userModalLabel" class="modal-user-name">
                                {{ trim((Auth::user()->civilite ?? '') . ' ' . (Auth::user()->firstname ?? '') . ' ' . (Auth::user()->name ?? '')) ?: 'Mon compte' }}
                            </p>
                            <p class="modal-user-email">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('deconnexion') }}" class="modal-logout-form">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary text-white btn-block nav-cta-logout">Se
                            déconnecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endauth
