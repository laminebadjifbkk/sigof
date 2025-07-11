<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @hasanyrole('Employe|super-admin|admin')
            <li class="nav-item">
                <a class="nav-link " href="{{ url('/home') }}">
                    <i class="bi bi-grid"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link " href="{{ url('/') }}">
                    <i class="bi bi-grid"></i>
                    <span>Tableau de bord</span>
                </a>
            </li> --}}
        @endhasanyrole

        @hasanyrole('super-admin|admin')
            @can('user-view')
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#users-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-person"></i><span>{{ __('Gestion utilisateurs') }}</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="users-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('users.online') }}">
                                <span>En ligne</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('users.actifs') }}">
                                <span>Actifs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('users.inactifs') }}">
                                <span>Inactifs</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('users.corbeille') }}">
                                <span>Corbeille</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('users.restored') }}">
                                <span>Restaurés</span>
                            </a>
                        </li>
                        @can('rapport-user-view')
                            <li class="nav-item">
                                <a class="nav-link collapsed" href="{{ route('users.rapport') }}">
                                    <span>Utilisateurs</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcan
        @endhasanyrole

        @can('role-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#autorisation-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-key"></i><span>{{ __("Contrôle d'accès") }}</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="autorisation-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('roles') }}">
                            <span>Roles</span>
                        </a>
                    </li>
                    @can('permission-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('permissions') }}">
                                <span>Permissions</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('une-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#actualite-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-envelope"></i><span>Page de présentation</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="actualite-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                    @can('post-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('postes') }}">
                                <span>Actualité</span>
                            </a>
                        </li>
                    @endcan
                    @can('une-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('unes') }}">
                                <span>A la une</span>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan

        @can('courrier-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#courrier-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-envelope"></i><span>Gestion du courrier</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="courrier-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('courriers') }}">
                            <span>Courriers</span>
                        </a>
                    </li>
                    @can('arrive-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('arrives') }}">
                                <span>Arrivés</span>
                            </a>
                        </li>
                    @endcan

                    @can('depart-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('departs') }}">
                                <span>Départs</span>
                            </a>
                        </li>
                    @endcan

                    @can('interne-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('internes') }}">
                                <span>Internes</span>
                            </a>
                        </li>
                    @endcan

                    @can('courrier-operateur-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('arrivesop') }}">
                                <span>Operateurs</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @hasrole('Employe')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('mescourriers') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Mes courriers</span>
                </a>
            </li>
        @endhasrole

        @hasrole('Demandeur')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('nouvellesformations') }}">
                    <i class="bi bi-grid"></i>
                    <span>Mes formations</span>
                </a>
            </li>
        @endhasrole

        @can('demande-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#demande--ind-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-folder-plus"></i><span>Gestion des demandes</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="demande--ind-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                    @can('individuelle-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('individuelles') }}">
                                <span>Demandes individuelles</span>
                            </a>
                        </li>
                    @endcan

                    @can('demande-show')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('collectives.index') }}">
                                <span>Demandes collectives</span>
                            </a>
                        </li>
                    @endcan

                    @can('demande-show')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('demandeurs.individuel') }}">
                                <span>Demandeurs individuels</span>
                            </a>
                        </li>
                    @endcan

                    @can('collective-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('listecollectives') }}">
                                <span>Demandeurs collectives</span>
                            </a>
                        </li>
                    @endcan

                    @can('demande-show')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('users.individuelle_collective') }}">
                                <span>Individuelles/Collectives</span>
                            </a>
                        </li>
                    @endcan
                    @hasrole('super-admin')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('individuelles.corbeille') }}">
                                <span>Corbeille individuelles</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('collectives.corbeille') }}">
                                <span>Corbeille collectives</span>
                            </a>
                        </li>
                    @endhasrole
                </ul>
            </li>
        @endcan

        @can('demandeur-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#demandeurs-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-check"></i><span>Mes demandes</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="demandeurs-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('demandesIndividuelle') }}">
                            <span>Individuelles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('demandesCollective') }}">
                            <span>Collectives</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#demandeurs-nav-programme" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-list-check"></i>
                    <span>Offres spéciales</span>
                    @if ($projets->contains('statut', 'ouvert'))
                        <i class="bi bi-bell-fill text-danger ms-2" title="Projet(s) ouvert(s) disponible(s)"></i>
                    @else
                        <span class="ms-2 small text-danger fst-italic">(aucune)</span>
                    @endif
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="demandeurs-nav-programme" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    @foreach ($projets as $projet)
                        <li class="nav-item">
                            <a class="nav-link collapsed"
                                href="{{ route('projetsIndividuelle', ['uuid' => $projet->uuid]) }}">
                                <span>{{ $projet->sigle }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endcan

        @can('devenir-operateur-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#demandeurs-operateur-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-folder-plus"></i><span>Devenir opérateur</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="demandeurs-operateur-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('devenirOperateur') }}">
                            <span>Agrément</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('operateur-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#operateur-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people-fill"></i><span>Gestion opérateurs</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="operateur-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('operateurs') }}">
                            <span>Opérateurs</span>
                        </a>
                    </li>
                    @can('agrement-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('agrement') }}">
                                <span>Traitement agréments</span>
                            </a>
                        </li>
                    @endcan

                    @can('agrement-commission')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('commissionagrements.index') }}">
                                <span>Commission agrément</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('commissionmembres.index') }}">
                                <span>Commission membres</span>
                            </a>
                        </li>
                    @endcan

                    @can('operateurmodule-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('operateurmodules') }}">
                                <span>Modules opérateurs</span>
                            </a>
                        </li>
                    @endcan

                    @can('operateurcategorie-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('operateurcategories') }}">
                                <span>Catégories</span>
                            </a>
                        </li>
                    @endcan


                    @hasrole('super-admin')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('operateurs.corbeille') }}">
                                <span>Corbeille</span>
                            </a>
                        </li>
                    @endhasrole
                </ul>
            </li>
        @endcan

        @can('formation-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-toggle="collapse" data-bs-target="#formations-nav" href="#">
                    <i class="bi bi-folder-symlink-fill"></i>
                    <span>
                        @if ($formationsEnCours > 0)
                            Formations
                            <span class="badge bg-warning text-white ms-2">{{ $formationsEnCours }} en cours</span>
                        @else
                            Gestion formations
                        @endif
                    </span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="formations-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('home') }}">
                            @if ($formationsEnCours === 1)
                                <span class="badge bg-warning text-white ms-2">{{ $formationsEnCours }} formation en
                                    cours</span>
                            @elseif ($formationsEnCours > 1)
                                <span class="badge bg-warning text-white ms-2">{{ $formationsEnCours }} formations en
                                    cours</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('formations.index') }}">
                            <span>Formations</span>
                        </a>
                    </li>

                    @can('convention-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('showConventions') }}">
                                <span>Conventions & DETF</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('ingenieur-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('ingenieurs.index') }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Gestion des ingénieurs</span>
                </a>
            </li>
        @endcan
        @can('lettrevaluation-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('lettrevaluations.index') }}">
                    <i class="bi bi-file"></i>
                    <span>Lettre évaluation & ABE</span>
                </a>
            </li>
        @endcan

        @can('evaluateur-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('evaluateurs') }}">
                    <i class="bi bi-person"></i>
                    <span>Evaluateurs externes</span>
                </a>
            </li>
        @endcan

        @can('onfpevaluateur-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('onfpevaluateurs') }}">
                    <i class="bi bi-person"></i>
                    <span>Evaluateurs ONFP</span>
                </a>
            </li>
        @endcan

        @can('localite-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#localite-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-globe"></i><span>Gestion localités</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="localite-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('localites') }}">
                            <span>Localités</span>
                        </a>
                    </li>

                    @can('region-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('regions') }}">
                                <span>Régions</span>
                            </a>
                        </li>
                    @endcan

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('departements') }}">
                            <span>Départements</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('arrondissements') }}">
                            <span>Arrondissement</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('communes') }}">
                            <span>Commune</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('employe-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#employes-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people"></i><span>Gestion employés</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="employes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('/employes') }}">
                            <span>Employés</span>
                        </a>
                    </li>
                    {{-- @can('direction-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/directions') }}">
                                <span>Directions</span>
                            </a>
                        </li>
                    @endcan --}}

                    @can('categorie-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/categories') }}">
                                <span>Catégories</span>
                            </a>
                        </li>
                    @endcan

                    @can('fonction-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/fonctions') }}">
                                <span>Fonction</span>
                            </a>
                        </li>
                    @endcan

                    @can('loi-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/lois') }}">
                                <span>Lois</span>
                            </a>
                        </li>
                    @endcan

                    @can('decret-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/decrets') }}">
                                <span>Decret</span>
                            </a>
                        </li>
                    @endcan

                    @can('pv-recrutement-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/procesverbals') }}">
                                <span>PV</span>
                            </a>
                        </li>
                    @endcan

                    @can('decision')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/decisions') }}">
                                <span>Décisions</span>
                            </a>
                        </li>
                    @endcan

                    @can('article-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/articles') }}">
                                <span>Articles</span>
                            </a>
                        </li>
                    @endcan

                    @can('nommination-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('/nomminations') }}">
                                <span>Nomminations</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan

        @can('module-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#modules-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-layers-half"></i><span>{{ __('Gestion modules') }}</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="modules-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('modules') }}">
                            <span>Modules</span>
                        </a>
                    </li>
                    @can('domaine-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('domaines') }}">
                                <span>Domaines</span>
                            </a>
                        </li>
                    @endcan
                    @can('secteur-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('secteurs') }}">
                                <span>Secteurs</span>
                            </a>
                        </li>
                    @endcan

                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ url('collectivemodules') }}">
                            <span>Modules demandes collectives</span>
                        </a>
                    </li>
                    @hasrole('super-admin')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('modules.corbeille') }}">
                                <span>Corbeille</span>
                            </a>
                        </li>
                    @endhasrole
                </ul>
            </li>
        @endcan

        @hasrole(['CCP', 'super-admin', 'DG', 'admin', 'SG', 'DPP', 'ADIOF', 'Ingenieur'])
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('projets') }}">
                    <i class="bi bi-layers-half"></i>
                    <span>Nos partenaires</span>
                </a>
            </li>
        @endhasrole

        @can('contact-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('contacts') }}">
                    <i class="bi bi-envelope"></i>
                    <span>Contactez-nous</span>
                </a>
            </li>
        @endcan

        @can('antenne-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#antennes-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people"></i><span>Nos antennes</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="antennes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    @hasrole('super-admin|dg')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ url('antennes') }}">
                                <span>Antennes</span>
                            </a>
                        </li>
                    @endhasrole
                    @foreach ($antennes as $antenne)
                        @if (!empty($antenne->code) && is_string($antenne->code))
                            @hasrole([$antenne->code, 'CAR', 'super-admin'])
                                <li class="nav-item">
                                    <a class="nav-link collapsed" href="{{ route('antennes.show', $antenne->id) }}">
                                        <span>{{ $antenne->name }}</span>
                                    </a>
                                </li>
                            @endhasrole
                        @endif
                    @endforeach
                </ul>
            </li>
        @endcan

        @can('direction-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#directions-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people"></i><span>Nos directions</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="directions-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    @hasrole('super-admin|DG|DRH|ADRH')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('directions.index') }}">
                                <span>Directions</span>
                            </a>
                        </li>
                    @endhasrole
                    @foreach ($directions as $direction)
                        @if (!empty($direction->sigle) && is_string($direction->sigle))
                            @hasrole([$direction->sigle, 'super-admin'])
                                <li class="nav-item">
                                    <a class="nav-link collapsed" href="{{ route('directions.show', $direction) }}">
                                        <span>{{ $direction->sigle }}</span>
                                    </a>
                                </li>
                            @endhasrole
                        @endif
                    @endforeach
                </ul>
            </li>
        @endcan

        @can('rapport-suivi-formes-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#suivi-formes-nav" data-bs-toggle="collapse"
                    href="#">
                    <i class="bi bi-diagram-3-fill"></i><span>{{ __('Suivi des formés') }}</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="suivi-formes-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('suiviformes.suivi-individuelle') }}">
                            <span>Individuelles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="{{ route('suiviformes.suivi-collective') }}">
                            <span>Collectives</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        @can('rapport-view')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#rapport-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-files"></i><span>{{ __('Générer rapports') }}</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="rapport-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    @can('rapport-individuelle-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('individuelles.rapport') }}">
                                <span>Demandes individuelles</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-collective-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('collectives.rapport') }}">
                                <span>Demandes collectives</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-individuelle-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('modules.rapport') }}">
                                <span>Demandeurs modules</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-formation-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('formations.rapport') }}">
                                <span>Formations</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-arrive-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('arrives.rapport') }}">
                                <span>Courriers arrivés</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-depart-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('departs.rapport') }}">
                                <span>Courriers départs</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-operateur-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('operateurs.rapport') }}">
                                <span>Opérateurs</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-formes-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('formes.rapport') }}">
                                <span>Formés individuelles</span>
                            </a>
                        </li>
                    @endcan

                    @can('rapport-formes-view')
                        <li class="nav-item">
                            <a class="nav-link collapsed" href="{{ route('formesCollective.rapport') }}">
                                <span>Formés collectives</span>
                            </a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endcan

        @can('file-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('files.index') }}">
                    <i class="bi bi-files"></i> <span>Fichiers utilisateurs</span>
                </a>
            </li>
        @endcan

        @can('convention-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('conventions.index') }}">
                    <i class="bi bi-journal"></i> <span>Conventions collectives</span>
                </a>
            </li>
        @endcan

        @can('referentiel-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('referentiels.index') }}">
                    <i class="bi bi-journals"></i> <span>Référentiels formations</span>
                </a>
            </li>
        @endcan

        @can('manuel-view')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('manuels.index') }}">
                    <i class="bi bi-book"></i>
                    <span>Nos publications</span>
                </a>
            </li>
        @endcan
    </ul>
</aside>
