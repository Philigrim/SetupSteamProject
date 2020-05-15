<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-0" href="{{ route('announcements') }}">
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
                        <a href="{{ route('announcements') }}">
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
                                <a class="nav-link" href="{{ route('announcements') }}">
                                    {{ __('Naujienos') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Paskaitos') }}">
                                    {{ __('Paskaitos') }}
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
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Kursai') }}">
                                    {{ __('Kursai') }}
                                </a>
                            </li>
                            @if(Auth::user()->isRole()=="mokytojas")
                            <li class="mb-6">
                            </li>
                            @endif
                            @if(Auth::user()->isRole()=="paskaitu_lektorius")
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('RouteToCreateEvent') }}">
                                    {{ __('Sukurti paskaitą') }}
                                </a>
                            </li>
                            <li class="mb-6">
                            @endif
                            @if(Auth::user()->isRole()=="admin")
                            <!-- Divider -->
                            <li><hr class="my-0"></li>
                            <!-- Other functions -->
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="media align-items-center">{{ __('Kitos funkcijos') }}</div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-arrow" style="width:100%">
                                    <a href="{{ route('RouteToCreateCourse') }}" class="dropdown-item">
                                        {{ __('Sukurti kursą') }}
                                    </a>
                                    <a href="{{ route('RouteToCreateEvent') }}" class="dropdown-item">
                                        {{ __('Sukurti paskaitą') }}
                                    </a>
                                    <a href="{{ route('RouteToUserManagement') }}" class="dropdown-item">
                                        {{ __('Vartotojų valdymas') }}
                                    </a>
                                    <a href="{{ route('iterpimas') }}" class="dropdown-item">
                                        {{ __('Duomenų įterpimas') }}
                                    </a>
                                </div>
                            </li>
                            <!-- Divider -->
                            <li><hr class="my-0 mb-6"></li>
                            @endif
                        </ul>
                    </div>

            </ul>


            <!-- Search & Filters -->
            @if (isset($title) && $title == __('Paskaitos'))

            <!-- Filters -->

            <row class="d-flex justify-content-center">
            <form action="{{ route('Paskaitos')}} " method="get">
                <button id="clearFilers" type="submit" class="btn btn-danger" style="display: @if (isset($category_value) || isset($capacity_value) || isset($city_value) || isset($date_value) && $date_value!="") flex @else none @endif;">Panaikinti pasirinkimus</button>
            </form>
            </row>

            <form action="{{ route('events.filter')}} " method="get">

            {{ __('Kategorija:') }}
            <div class="row">
            <select id="Category" class="form-control mb-2 ml-3 dropdown-menu-arrow" style="width:80%;" name="filterCategoryInput" onchange="showDeleteButton(deleteCategoryButton)">
                <option value="" @if(!isset($category_value)) selected @endif disabled>{{ "Pasirinkite kategoriją" }}</option>
                @foreach($subjects as $subject)
                <option @if((isset($category_value)) && ($category_value == $subject->subject)) selected @endif value="{{ $subject->subject }}">{{ $subject->subject }}</option>
                @endforeach
            </select>

            <input id="deleteCategoryButton" value="(x)" type="button" class="btn btn-danger p-0" style="width:10%; height: 45px; @if(!isset($category_value)) display:none; @endif;" onclick="deleteValue(Category)"></input>
            </div>

            {{ __('Laisvų vietų skaičius:') }}
            <div class="row">
            <input id="Capacity" class="form-control ml-3 input-group mb-2" @if (isset($capacity_value)) value="{{ $capacity_value }}" @endif style="width:80%;" type="number" name="filterCapacityInput" placeholder="Nesvarbus" min="1" oninput="showDeleteButton(deleteCapacityButton)">
            
            <input id="deleteCapacityButton" value="(x)" type="button" class="btn btn-danger p-0" style="width:10%; height: 45px; @if(!isset($capacity_value)) display:none; @endif;" onclick="deleteValue(Capacity)"></input>
            </div>

            {{ __('Miestas:') }}
            <div class="row">
            <select id="City" class="form-control ml-3 mb-2 dropdown-menu-arrow mb-2" style="width:80%;" name="filterCityInput" onchange="showDeleteButton(deleteCityButton)">
                <option value="" @if(!isset($city_value)) selected @endif disabled>{{ "Nurodykite miestą" }}</option>
                @foreach($cities as $city)
                <option @if((isset($city_value)) && ($city_value == $city->city_name)) selected @endif value="{{ $city->city_name }}">{{ $city->city_name }}</option>
                @endforeach
            </select>
            <input id="deleteCityButton" value="(x)" type="button" class="btn btn-danger p-0" style="width:10%; height: 45px; @if(!isset($city_value)) display:none; @endif" onclick="deleteValue(City)"></input>
            </div>

            {{ __('Data:') }}
            <div class="row">
            <select id="dateInput" class="form-control ml-3 mb-2 dropdown-menu-arrow mb-2" style="width:80%;" name="filterDateInput" onchange="showHide(this)">
                <option value="" @if(!isset($date_value)) selected @endif disabled >{{ __('Įvesties tipas') }}</option>
                <option @if(isset($date_value) && $date_value == "future") selected @endif value="future">{{ __('Rodyti tik ateinančias') }}</option>
                <option @if(isset($date_value) && $date_value == "past") selected @endif value="past">{{ __('Rodyti tik praėjusias') }}</option>
                <option @if(isset($date_value) && $date_value == "all") selected @endif value="all">{{ __('Rodyti visas') }}</option>
                <option @if(isset($date_value) && $date_value == "oneDay") selected @endif value="oneDay">{{ __('Pasirinkti dieną') }}</option>
                <option @if(isset($date_value) && $date_value == "from") selected @endif value="from">{{ __('Įvesti nuo') }}</option>
                <option @if(isset($date_value) && $date_value == "till") selected @endif value="till">{{ __('Įvesti iki') }}</option>
                <option @if(isset($date_value) && $date_value == "interval") selected @endif value="interval">{{ __('Įvesti intervalą') }}</option>
            </select>
            <input id="deleteDateButton" value="(x)" type="button" class="btn btn-danger p-0" style="width:10%; height: 45px; @if(!isset($date_value) || (isset($date_value) && $date_value=="")) display:none; @endif" onclick="deleteValue(dateInput)"></input>
            </div>


            <div id="dateFilters">
            <div id="dateOneDayDiv" class="form-group mb-2" style="width:92%; display: @if(isset($date_value) && $date_value == "oneDay") flex @else none @endif ;">
                <input @if(isset($dateOneDay)) value="{{ $dateOneDay }}" @endif placeholder="Pasirinkite datą" id="dateOneDay" name="filterDateOneDay"/>
            </div>
            <div id="dateFromDiv" class="form-group mb-2" style="width:92%; display: @if(isset($date_value) && ($date_value == "from" || $date_value == "interval")) flex @else none @endif ;">
                <input @if(isset($dateFrom)) value="{{ $dateFrom }}" @endif placeholder="Pasirinkite datą" id="dateFrom" name="filterDateFrom"/>
            </div>
            <div id="dateTillDiv" class="form-group mb-2" style="width:92%; display: @if(isset($date_value) && ($date_value == "till" || $date_value == "interval")) flex @else none @endif ;">
                <input @if(isset($dateTill)) value="{{ $dateTill }}" @endif placeholder="Pasirinkite datą" id="dateTill" name="filterDateTill"/>
            </div>
            </div>

            <row class="d-flex justify-content-center mr-3">
                <button id="submitFilters" type = "submit" class = "btn btn-success">
                    {{ __('Rodyti rezultatus') }}
                </button>
            </row>

            </form>

            <script type="text/javascript">
                function showDeleteButton(buttonId) {
                    buttonId.style.display = 'flex';
                    clearFilers.style.display = 'flex';
                };
            </script>

            <script type="text/javascript">
                function deleteValue(inputId) {
                    inputId.value = "";
                    $( "#submitFilters" ).click();
                };
            </script>
            

            <!-- /Filters -->
            @endif

            <!-- Navigation -->
            <ul class="navbar-nav mb-md-3">

            </ul>
        </div>
    </div>
</nav>

