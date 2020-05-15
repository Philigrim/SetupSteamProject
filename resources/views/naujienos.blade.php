@extends('layouts.app', ['title' => __('Naujienos')])

@section('additional_header_content')

@if (Auth::user()->isRole()=="admin")
{{--Toggle button--}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script>
    jQuery(document).ready(function($) {
      $('.promote-class').change(function() {
        var event_id = $(this).data('id'); 
        var is_manual_promoted = $(this).is(':checked');
        
        if(!is_manual_promoted){
            $("#" + event_id).remove();
        }
        $.ajax({
          type: "GET",
          dataType: "json",
          url: 'paskaitos/promote',
          data: {'is_manual_promoted': is_manual_promoted, 'event_id': event_id}
        });
      })
    })
    </script>
@endif

@endsection

@section('content')
@include('users.partials.header', ['title' => __('Naujienos'),
         'description' => __('Šiame puslapyje skelbiama informacija apie STEAM centrus bei kitos naujienos.'),
         'class' => 'col-lg-12'])

@if (session('status'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('status') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if (session()->has('message'))
    @if(session()->get('message')==('Jūs jau užsiregistravę į šią paskaitą!'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      {{ session()->get('message') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
      </button>
  </div>
    @endif
    @if(session()->get('message')==('Jūs sėkmingai užsiregistravote į paskaitą'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endif

@if (Auth::user()->isRole()=="admin")
<!-- Announcemenet Creation Modal -->
<div class = "modal fade" id = "createAnnouncementModal" tabindex = "-1" role = "dialog" aria-labelledby = "createAnnouncementLabel" aria-hidden = "true">

    <div class = "modal-dialog modal-lg">
        <div class = "modal-content">

            <div class = "modal-header">
                <h2 class = "modal-title" id = "createAnnouncementLabel">
                    Naujo pranešimo kūrimas
                </h2>

                <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
                    &times;
                </button>
            </div>
            <form action = {{ route('announcements.store')}} method="post">
            @csrf

            <div class = "modal-body">
                <label>Autorius:</label>
                <input class="form-control" placeholder="Įveskite pranešimo autorių" name="announcement_author" value="{{ auth()->user()->firstname}} {{auth()->user()->lastname}}" required
                oninvalid="this.setCustomValidity('Pranešimo autoriaus langelis negali būti tuščias.')">

                <label class="mt-3">Pranešimo pavadinimas:</label>
                <input class="form-control" placeholder="Įveskite pranešimo pavadinimą" name="announcement_title" required
                oninvalid="this.setCustomValidity('Pranešimo pavadinimo langelis  negali būti tuščias.')">

                <label class="mt-3">Pranešimas:</label>
                <textarea class="form-control" rows="5" placeholder="Įveskite pranešimą kitiems vartotojams" name="announcement_text" required
                oninvalid="this.setCustomValidity('Pranešimo langelis negali būti tuščias.')"></textarea>
            </div>

            <div class = "modal-footer">
                <button type = "button" class = "btn btn-danger" data-dismiss = "modal">
                    Atšaukti
                </button>

                <button type = "submit" class = "btn btn-success">
                    Paskelbti naujieną
                </button>

            </div>

            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div>
@endif

<div class="container-fluid row d-flex justify-content-center">
    <div class="col-xl-6 mt-3">
    <div clas="row">
    

        <!-- Search form -->
        <form action="{{ route('announcements.search') }}" method="get">
            <div class="input-group border border-primary rounded">
                <input class="form-control border border-none" name="search" type="text" @if (isset($search_value)) value="{{ $search_value }}" @endif placeholder="Raskite norimą pranešimą">
                <div class="input-group-append">
                <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <!-- Announcements -->
        @foreach($announcements as $announcement)
                <div class="card card-stats mt-3 xl-7">
                <div class="card-body border border-primary shadow rounded">
                @if (Auth::user()->isRole()=="admin")
                <div class="row float-right">

                <button
                    data-id="{{ $announcement->id }}" data-title="{{ $announcement->title }}" data-author="{{ $announcement->author }}" data-created_at="{{ $announcement->created_at }}" data-text="{{ $announcement->text }}"
                    class="show-edit-announcement btn btn-success ml-3 mb-3" data-toggle = "modal" data-target = "#editAnnouncementModal">
                    Redaguoti
                </button>
               
                <!-- Announcemenet Editing Modal -->
                <div class="modal fade" id="editAnnouncementModal" tabindex="-1" role="dialog" aria-labelledby = "editAnnouncementLabel" aria-hidden = "true">
                    <div class="modal-dialog modal-lg">
                        <div class = "modal-content">
                            <div class = "modal-header pb-3">
                                <h2 class = "modal-title" id="editAnnouncementLabel">Pranešimo redagavimas</h2>
                                <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">&times;</button>
                            </div>

                            <form action="{{ route('announcements.update', [$announcement->id]) }}" method="post">
                            <input type="hidden" name="_method" value="patch" />
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <input type="hidden" id="editing_id" name="edited_id" value="{{ $announcement->id }}">
                            
                            <div class="modal-body pt-2">

                                <div class="border-top mt-2 mb-2"></div>

                                <div class="row d-flex justify-content-center">
                                    <textarea class="font-weight-bold" style="text-align: center; height: 25px;" placeholder="Įveskite pranešimo pavadinimą" rows="1" id="editing_title" name="edited_title" required></textarea>
                                </div>
                
                                <div class="border-top mt-2 mb-2"></div>

                                <div>
                                    <span class="ml-2">Autorius:</span>
                                    <span class="ml-2"><textarea style="vertical-align: baseline; height: 25px;" rows="1" id="editing_author" name="edited_author" placeholder="Įveskite pranešimo autorių" required></textarea></span>
                                    <div class="ml-2 float-right" id="editing_created_at"></div>
                                </div>

                                <div class="border-top mt-2 mb-2"></div>

                                <textarea class="form-control" id="editing_text" name="edited_text" placeholder="Įveskite pranešimą kitiems vartotojams" rows="7" required></textarea>
                                
                                <div class="border-top mt-2 mb-2"></div>

                

                            </div>
                            <div class="modal-footer">
                                <button type = "button" class = "btn btn-danger" data-dismiss = "modal">
                                    Atšaukti
                                </button>
                                <button type = "submit" class = "btn btn-success">
                                    {{ __('Patvirtinti') }}
                                </button>
                            </div>
                            </form>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

                <form action="{{ url('/announcements', [$announcement->id]) }}" method="post">
                <Button class="btn btn-danger ml-2" type="submit">Ištrinti</Button>
                <input type="hidden" name="_method" value="delete" />
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                </div>

                <br>
                <br>
                @endif
                <div class="border-top mt-2 mb-2"></div>

                <div class="row d-flex justify-content-center">
                <h1 class="card-title font-weight-bold">{{ $announcement->title }}</h1>
                </div>

                <div class="border-top mt-2 mb-2"></div>

                <div>
                <span class="ml-2">Autorius:</span>
                <span class="ml-2">{{ $announcement->author }}</span>
                <div class="ml-2 float-right">{{ $announcement->created_at }}</div>
                </div>

                <div class="border-top mt-2 mb-2"></div>

                <div class="ml-2">{{ $announcement->text }}</div>

                <div class="border-top mt-2 mb-2"></div>
                </div>
                </div>
        @endforeach
        <!-- /Announcements -->

    </div>
    </div>

    <!-- Divider-->
    <div class="border-right mt-3 ml-4 mr-4 mb-2 d-flex justify-content-center"></div>

    <!-- Promoted Courses (right side)-->
    <div class="col-xl-5">
    <div clas="row">
        @foreach($reservations as $reservation)
        @csrf
        
        <div id="{{ $reservation->event->id }}" class="card card-stats mt-3 d-flex justify-content-center">
            <div class="card-body border border-primary shadow rounded">
                @if (Auth::user()->isRole()=="admin")
                <row class="float-right">
                    @if($reservation->event->is_manual_promoted)
                        <input data-id="{{ $reservation->event->id }}" class="promote-class" checked type="checkbox" data-onstyle="danger" data-toggle="toggle" data-on="Stabdyti iškelima">
                    @else
                        <button class="btn btn-primary mt-2" style="opacity: 1;" disabled>Iškeltas automatiškai</button>
                    @endif
                </row>
                <br>
                <br>
                @endif

                <div class="border-top mt-2 mb-2"></div>

                <h1 class="card-title font-weight-bold mb-0 d-flex justify-content-center"> {{ $reservation->event->name }} </h1>
                
                <div class="border-top"></div>
                
                <div class="row d-flex justify-content-center">
                    <img class="icon-sm pt-3" src="argon/img/icons/common/place.svg" alt="">
                    <h5 class="pt-3 pr-2">{{ $reservation->room->steam->city->city_name }}, {{ $reservation->room->steam->address }}</h5>
                    <img class="icon-sm pt-3" src="argon/img/icons/common/clock.svg" alt="">
                    <h5 class="pt-3 pr-2">{{ $reservation->date }}, {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }}</h5>
                    <img class="icon-sm pt-3" src="argon/img/icons/common/user.svg" alt="">
                    <h5 class="pt-3 pr-2">{{ $reservation->event->max_capacity - $reservation->event->capacity_left }}/{{ $reservation->event->max_capacity }}</h5>
                    <img class="icon-sm pt-3" src="argon/img/icons/common/book.svg" alt="">
                    <h5 class="pt-3">{{ $reservation->event->course->subject->subject }}</h5>
                </div>

                <div class="border-top mb-2"></div>

                <div class="ml-2"> {{ $reservation->event->description }} </div>

                <div class="border-top mt-2 mb-2"></div>

                @foreach($lecturers[$reservation->event->id] as $lecturer)
                    <button class="p-1 mt-2 btn btn-dark">
                        <h6 class="text-white text-center mb-0">{{ $lecturer->lecturer->user->firstname }} {{ $lecturer->lecturer->user->lastname }}</h6>
                    </button>
                @endforeach
                @if (Auth::user()->isRole()=="mokytojas")
                <button href ="#" data-id="{{$reservation->event->id}}" data-capacity= "{{$reservation->event->capacity_left}}"class="show-modal btn btn-primary exampleModalCenter mr-0 float-right" id="lol" data-name="{{$reservation->event->name}}">
                    Registruotis
                </button>
                <!-- Registration modal -->
                <div class="modal fade" id="show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Regitracija į paskaitą</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action = "/paskaitos" method="post">
                            @csrf
                        <div class="form-group">
                            <label for="">Paskaitos pavadinimas :</label>
                        </div>
                        <div class="form-group">
                            <input  type="hidden"type="text" name="event_id" id="id">
                        </div>
                        <br>
                        <div class="form-group">
                            <b>Mokinių skaičius</b>
                            <input id="set-capacity" name ="pupil_count" class="col-5" value ="1" min="1" max="1" type="number" placeholder="0">
                        </div>
                        <div class="modal-footer">
                            <div class="form-group">
                                <button type="submit"  class="btn btn-success mt-4">{{ __('Patvirtinti') }}</button>
                            </div>
                        </div>
                        </form>
                        </div>
                    </div>
                    </div>
                </div>
                @elseif (Auth::user()->isRole()=="paskaitu_lektorius")
                <button class="btn btn-primary mr-0 float-right" disabled>
                    Registracija tik mokytojams
                </button>
                @endif

                <div class="border-top mt-3"></div>
            </div>
        </div>
        {{ csrf_field() }}
        @endforeach
    </div>
    </div>

</div>



@endsection

{{--Modal script--}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

@if (Auth::user()->isRole()=="admin")
{{--Editing modal script--}}
<script type="text/javascript">
    $(document).on('click', '.show-edit-announcement', function() {
    $('#editing_id').val($(this).data('id'));
    $('#editing_title').text($(this).data('title'));
    $('#editing_author').text($(this).data('author'));
    $('#editing_created_at').text($(this).data('created_at'));
    $('#editing_text').text($(this).data('text'));
    })
</script>
@endif


@if (Auth::user()->isRole()=="mokytojas")
{{--Registration modal script--}}
<script type="text/javascript">
    $(document).on('click', '.show-modal', function() {
    $('#show').modal('show');
    $('#id').val($(this).data('id'));
    $('#name').text($(this).data('name'));
    $('#set-capacity').attr("max",$(this).data('capacity'));
    })

    $('#set-capacity').change(function(){
        if(parseInt($().val('#set-capacity')) > parseInt($('#set-capacity').attr("max"))){
            $('#set-capacity').val($('#set-capacity').attr("max"));
        }else if($('#set-capacity').val() < $('#set-capacity').attr("min")){
            $('#set-capacity').val($('#set-capacity').attr("min"));
        }
    })
</script>
@endif