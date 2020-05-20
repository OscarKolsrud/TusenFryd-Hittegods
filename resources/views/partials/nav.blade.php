<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/home') }}">
            {!! config('app.name', trans('titles.app')) !!}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            <span class="sr-only">{!! trans('titles.toggleNav') !!}</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- Left Side Of Navbar --}}
            <ul class="navbar-nav mr-auto">

                @guest

                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Gjenstander
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item {{ Request::is('case/items/create') ? 'active' : null }}"
                               href="{{ route('create_item') }}">
                                <i class="fa fa-plus-square" aria-hidden="true"></i> Legg til ny
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item {{ Request::is('case/list/*/found') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'found', 'view' => 'all']) }}">
                                Aktive
                            </a>
                            <a class="dropdown-item {{ Request::is('case/list/*/found/*') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'found', 'date' => \Carbon\Carbon::now()->toDateString(), 'view' => 'all']) }}">
                                Aktive reg. idag
                            </a>
                            <a class="dropdown-item {{ Request::is('case/list/found/canceled') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'wait_for_police', 'view' => 'found']) }}">
                                Venter sending til politi
                            </a>
                            <a class="dropdown-item {{ Request::is('case/list/found/police') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'police', 'view' => 'found']) }}">
                                Sendt til politi
                            </a>
                            <a class="dropdown-item {{ Request::is('case/list/found/evicted') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'evicted', 'view' => 'found']) }}">
                                Kastet
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Etterlysninger
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item {{ Request::is('case/lost/create') ? 'active' : null }}"
                               href="{{ route('create_lost') }}">
                                <i class="fa fa-plus-square" aria-hidden="true"></i> Legg til ny
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item {{ Request::is('case/list/*/lost') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'lost', 'view' => 'all']) }}">
                                Aktive
                            </a>
                            <a class="dropdown-item {{ Request::is('case/list/*/lost/*') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'lost', 'date' => \Carbon\Carbon::now()->toDateString(), 'view' => 'all']) }}">
                                Aktive reg. idag
                            </a>
                            <a class="dropdown-item {{ Request::is('case/list/lost/canceled') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'canceled', 'view' => 'lost']) }}">
                                Avsluttet
                            </a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Utsending/Utlevering
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item {{ Request::is('case/list/all/wait_for_pickup') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'wait_for_pickup', 'view' => 'all']) }}">
                                Venter på utlevering
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item {{ Request::is('case/list/all/wait_for_send') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'wait_for_send', 'view' => 'all']) }}">
                                Venter på utsending
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item {{ Request::is('case/list/all/picked_up') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'picked_up', 'view' => 'all']) }}">
                                Utlevert
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item {{ Request::is('case/list/all/sent') ? 'active' : null }}"
                               href="{{ route('list_case', ['list' => 'sent', 'view' => 'all']) }}">
                                Sendt
                            </a>
                        </div>
                    </li>
                @endguest

                @role('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item {{ (Request::is('roles') || Request::is('permissions')) ? 'active' : null }}"
                           href="{{ route('laravelroles::roles.index') }}">
                            Rolle Administrasjon
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'active' : null }}"
                           href="{{ url('/users') }}">
                            Bruker Administrasjon
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('users/create') ? 'active' : null }}"
                           href="{{ url('/users/create') }}">
                            Legg Til Ny Bruker
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('themes','themes/create') ? 'active' : null }}"
                           href="{{ url('/themes') }}">
                            Temaer
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('logs') ? 'active' : null }}" href="{{ url('/logs') }}">
                            Systemlogger
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('activity') ? 'active' : null }}"
                           href="{{ url('/activity') }}">
                            Aktivitetslogger
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('phpinfo') ? 'active' : null }}"
                           href="{{ url('/phpinfo') }}">
                            PHP Informasjon
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('routes') ? 'active' : null }}"
                           href="{{ url('/routes') }}">
                            Routing detaljer
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('active-users') ? 'active' : null }}"
                           href="{{ url('/active-users') }}">
                            Aktive brukere
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('blocker') ? 'active' : null }}"
                           href="{{ route('laravelblocker::blocker.index') }}">
                            Blokkeringer
                        </a>
                    </div>
                </li>
                @endrole
            </ul>
            {{-- Right Side Of Navbar --}}
            <ul class="navbar-nav ml-auto">
                {{-- Authentication Links --}}
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ trans('titles.login') }}</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            @if ((Auth::User()->profile) && Auth::user()->profile->avatar_status == 1)
                                <img src="{{ Auth::user()->profile->avatar }}" alt="{{ Auth::user()->name }}"
                                     class="user-avatar-nav">
                            @else
                                <div class="user-avatar-nav"></div>
                            @endif
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item {{ Request::is('profile/'.Auth::user()->name, 'profile/'.Auth::user()->name . '/edit') ? 'active' : null }}"
                               href="{{ url('/profile/'.Auth::user()->name) }}">
                                Min Profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                Logg ut
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
