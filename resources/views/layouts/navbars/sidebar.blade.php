

<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('home') }}">
            <img src="{{ asset('argon') }}/img/brand/steam1.jpeg" class="navbar-brand-img" alt = "...">
            <img src="{{ asset('argon') }}/img/brand/steam2.png" class="navbar-brand-img" alt="...">
        </a>
        <!-- User -->
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                        <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-1-800x800.jpg">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('Mano paskyra') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>{{ __('Nustatymai') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-calendar-grid-58"></i>
                        <span>{{ __('Activity') }}</span>
                    </a>
                    <a href="#" class="dropdown-item">
                        <i class="ni ni-support-16"></i>
                        <span>{{ __('Support') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('argon') }}/img/brand/steam1.jpeg">
                            <img src="{{ asset('argon') }}/img/brand/steam.png">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Navigation -->
            <ul class="navbar-nav">
                    <div class="collapse show" id="navbar-examples">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">
                                    {{ __('Naujienos') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Kursai') }}">
                                    {{ __('Kursai') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Paskaitos') }}">
                                    {{ __('Paskaitos') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    {{ __('Vartotojo paskyra') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('faq') }}">
                                    {{ __('D.U.K.') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('about') }}">
                                    {{ __('Apie') }}
                                </a>
                            </li>
                            @if(Auth::user()->isRole()=="admin" || Auth::user()->isRole()=="paskaitu_lektorius")
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('RouteToCreateEvent') }}">
                                    {{ __('Sukurti paskaitą') }}
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->isRole()=="admin")
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('RouteToCreateCourse') }}">
                                        {{ __('Sukurti kursą') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('RouteToUserManagement') }}">
                                        {{ __('Vartotojų valdymas') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

            </ul>

            <!-- Divider -->
            <hr class="my-3">

            <!-- Filters -->
            <p class="d-flex justify-content-center" style="font-size:150%;">Filtrai</p>

            <!-- Divider -->
            <hr class="my-0">

            @if (isset($title))
            @if ($title == __('Naujienos'))
            <form action="/filter/events" method="get">

            Kategorija:
            <select class="mdb-select md-form mb-2">
                <option disabled selected>Pasirinkite kategorija</option>
                <option value="1">Science</option>
                <option value="2">Technologijos</option>
                <option value="3">Engineering</option>
                <option value="4">Arts</option>
                <option value="5">Mathematics</option>
            </select>

            Laisvų vietų skaičius:
            <div class="row d-flex justify-content-center mb-2">
                <input class="col-5" type="number" placeholder="Nuo" min="0">
                <input class="col-5" type="number" placeholder="Iki" min="0">
            </div>

            Miestas:
            <select class="mdb-select md-form mb-3">
                <option disabled selected>Nurodykite miestą</option>
                <option value="1">Vilnius</option>
                <option value="2">Kaunas</option>
                <option value="3">Klaipėda</option>
                <option disabled="disabled">-------------------------------</option>
                <option value="4">Alytus</option>
                <option value="5">Marijapmolė</option>
                <option value="6">Panevėžys</option>
                <option value="7">Šiauliai</option>
                <option value="8">Tauragė</option>
                <option value="9">Telšiai</option>
                <option value="10">Utena</option>
            </select>

            <row class="d-flex justify-content-center mt-1">
                <button type = "submit" class = "btn btn-success">
                    Rodyti rezultatus
                </button>
            </row>

            </form>

            <!-- Divider -->
            <hr class="my-3">

            @endif
            @endif
            <!-- /Filters -->

                        <!-- Navigation -->
            <ul class="navbar-nav mb-md-3">

            </ul>
        </div>
    </div>
</nav>
