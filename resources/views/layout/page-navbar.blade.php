<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">
        @php
            $unreadNotifications = $user->unReadNotifications;
            $notificationCount = $unreadNotifications->count();
        @endphp

        @if ($notificationCount > 0)
            @can('courrier-notification-show')
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-primary badge-number">{{ $notificationCount }}</span>
                    </a><!-- End Notification Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="dropdown-header">
                            {{ $notificationCount }} nouvelles notifications
                            <a href="{{ url('notifications') }}">
                                <span class="badge rounded-pill bg-primary p-2 ms-2">Voir toutes</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @foreach ($unreadNotifications as $notification)
                            <a class="dropdown-item d-flex align-items-center"
                                href="{{ route('courriers.showFromNotification', ['courrier' => $notification->data['courrierId'], 'notification' => $notification->id]) }}">
                                <li class="notification-item">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <div>
                                        <h4>{{ $notification->data['firstname'] }} {{ $notification->data['name'] }}</h4>
                                        <p>{{ $notification->data['courrierTitle'] }}</p>
                                        <p>{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </li>
                            </a>
                        @endforeach

                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="dropdown-footer">
                            <a href="{{ url('notifications') }}">Voir toutes les notifications</a>
                        </li>
                    </ul><!-- End Notification Dropdown Items -->
                </li>
            @endcan
        @endif
        {{-- 

        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                <img class="rounded-circle" alt="Profil" src="{{ asset($user->getImage()) }}">
                <span class="d-none d-md-block dropdown-toggle ps-2">
                    @if ($user->operateur)
                        {{ $user->username }}
                    @elseif ($user->name)
                        {{ $user->civilite . ' ' . $user->name }}
                    @else
                        {{ $user->username }}
                    @endif
                </span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                <li class="dropdown-header">
                    <h6>
                        @if ($user->operateur)
                            {{ $user->username }}
                        @elseif ($user->name)
                            {{ $user->civilite . ' ' . $user->firstname . ' ' . $user->name }}
                        @else
                            {{ $user->username }}
                        @endif
                    </h6>
                    <span><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/profil') }}">
                        <i class="bi bi-person"></i>
                        <span>Mon Profil</span>
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item show_confirm_disconnect"><i
                                class="bi bi-box-arrow-in-left"></i>Se
                            déconnecter</button>
                    </form>
                </li>

            </ul>
        </li> --}}
        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                {{-- Photo profil avec statut --}}
                <span
                    class="nav-profile-image-wrapper
            {{ $user?->last_activity && \Carbon\Carbon::parse($user?->last_activity)->diffInMinutes(now()) < 5
                ? 'online'
                : 'offline' }}">

                    <img src="{{ asset($user->getImage()) }}" alt="Profil" class="nav-profile-image">
                </span>
                <span class="d-none d-md-block dropdown-toggle ps-2">
                    @php
                        $displayName = '';

                        if ($user->operateur) {
                            $displayName = !empty($user->username) ? $user->username : $user->civilite . ' ' . $user->name;
                        } else {
                            if (!empty($user->name)) {
                                $displayName = $user->civilite . ' ' . $user->name;
                            } else {
                                $displayName = !empty($user->username) ? $user->username : $user->civilite . ' ' . $user->name;
                            }
                        }
                    @endphp
                    {{ $displayName }}
                </span>
            </a>

            {{-- Dropdown --}}
            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                <li class="dropdown-header text-center">
                    <h6>
                        @if ($user->operateur)
                            {{ $user->username }}
                        @elseif ($user->name)
                            {{ $user->civilite . ' ' . $user->firstname . ' ' . $user->name }}
                        @else
                            {{ $user->username }}
                        @endif
                    </h6>

                    {{-- Statut --}}
                    @if ($user?->last_activity && \Carbon\Carbon::parse($user?->last_activity)->diffInMinutes(now()) < 5)
                        <span class="text-success fw-bold">En ligne</span>
                    @else
                        <span class="text-danger fw-bold">Hors ligne</span>
                    @endif

                    <br>
                    <span>
                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                    </span>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <a class="dropdown-item d-flex align-items-center" href="{{ url('/profil') }}">
                        <i class="bi bi-person"></i>
                        <span>Mon Profil</span>
                    </a>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item show_confirm_disconnect">
                            <i class="bi bi-box-arrow-in-left"></i>
                            Se déconnecter
                        </button>
                    </form>
                </li>

            </ul>
        </li>

    </ul>
</nav>
