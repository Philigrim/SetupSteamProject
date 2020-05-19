@extends('layouts.app', ['title' => __('User Profile')])

@section('additional_header_content')
{{--Gijgo--}}
    <script src="/gijgo/dist/modular/js/core.js" type="text/javascript"></script>
    <link href="/gijgo/dist/modular/css/core.css" rel="stylesheet" type="text/css">

{{--Date pickeris--}}
    <link href="/gijgo/dist/modular/css/datepicker.css" rel="stylesheet" type="text/css">
    <script src="/gijgo/dist/modular/js/datepicker.js"></script>

{{--Toggle buttonas--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
    jQuery(document).ready(function($) {
      $('.promote-class').change(function() {
        var event_id = $(this).data('id');
        var is_manual_promoted = $(this).is(':checked');
        $.ajax({
          type: "GET",
          dataType: "json",
          url: 'paskaitos/promote',
          data: {'is_manual_promoted': is_manual_promoted, 'event_id': event_id}
        });
      })
    })
    </script>

    <link href="{{ asset('css/win.css') }}" rel="stylesheet" type="text/css" >
    <link href="/css/win.css" rel="stylesheet">
@endsection

@section('content')
    @include('users.partials.header', [
        'title' => __('Mano paskaitos'),
        'description' => __('Šiame puslapyje galite apžvelgti praėjusias ir būsimas paskaitas, taip pat matyti, kur ir kada jos vyko.'),
        'class' => 'col-lg-14'
    ])

    <div class="container-fluid mt--7">
        <div class="col-xl-13 order-xl-1 mt-3">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <h3 class="col-12 mb-0">{{ __('Paskaitos') }}</h3>
                    </div>
                </div>
                <div class="card-body" style="padding-left:0">
                    <div class="column pl-lg-4 " >

                        @if (session()->has('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif
                        @if (session()->has('dangerstatus'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('dangerstatus') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <th><p class="heading-small text-muted mb-4" style="font-weight:bold">{{ __('Būsimos paskaitos') }}</p></th>
 
                        <table class ="table table-hover table-responsive table-sm" cellspacing="0" >
                            
                            
                            <tr>
                                <th>Paskaitos pavadinimas:</th>
                                <th>Destytojas:</th>
                                <th>Data ir laikas:</th>
                                <th>Dalyviai/Iš viso vietų:</th>
                                <th>Adresas:</th>
                                <th>Centras, kabinetas:</th>
                                <th style="text-align: center;">Veiksmas</th>
                            </tr>
                            @foreach($futureEvents as $reservation)
                            <tr>
                                <th style="font-weight:normal">{{ $reservation->event->name}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->lecturer->lecturer->user->firstname }} {{ $reservation->event->lecturer->lecturer->user->lastname }}</th>
                                <th style="font-weight:normal">{{ $reservation->date }}, {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }}</th>
                                <th style="font-weight:normal;">{{ $reservation->event->max_capacity-$reservation->event->capacity_left }}/{{ $reservation->event->max_capacity}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->room->steam->city->city_name}}, {{ $reservation->event->room->steam->address}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->room->steam->steam_name}}, {{ $reservation->event->room->room_number}}</th>
                                @if(Auth::user()->isRole()=="mokytojas")
                                @if(date($reservation->date) > date('Y-m-d', strtotime(date('Y-m-d'). ' + 2 days')))
                                <th><button href ="#" class="show-modal btn btn-success mt-2" data-id="{{$reservation->event->id}}" data-pupil_count = "{{ $reservation->event->teacher->pupil_count }}" data-capacity= "{{$reservation->event->capacity_left}}" data-name="{{$reservation->event->name}}">Keisti</button> </th>
                                @else
                                <th><button href ="#" class="show-toolate-modal btn btn-success mt-2" data-id="{{$reservation->event->id}}">Keisti</button> </th>
                                @endif
                                @else 
                                <th style="text-align: center;">
                                    <button href ="#" class="show-participants btn btn-success btn-sm" data-id="{{$reservation->event->id}}">Dalyviai</button>
                                    <button class="btn btn-success btn-sm show-edit-event" data-toggle = "modal" data-target = "#editEventModal" data-id="{{ $reservation->id }}"
                                        data-name="{{ $reservation->event->name }}" data-course_title="{{ $reservation->event->course->course_title }}" data-subject_title="{{ $reservation->event->course->subject->subject }}"
                                        data-city="{{ $reservation->room->steam->city->city_name }}" data-steam_center="{{ $reservation->room->steam->steam_name }}" data-room="{{ $reservation->room->room_number }}({{$reservation->room->capacity }}) {{ $reservation->room->subject->subject }}" data-room_id="{{ $reservation->room->id }}"
                                        data-reservation_date="{{ $reservation->date }}" data-reservation_time="{{ substr($reservation->start_time, 0, 5) }}-{{ substr($reservation->end_time, 0, 5) }}"
                                        data-event_capacity="{{ $reservation->event->max_capacity }}" data-event_capacity_used="{{$reservation->event->max_capacity}}-{{$reservation->event->capacity_left}}" data-event_description="{{ $reservation->event->description }}" @if(isset($reservation->event->file->id)) data-file_name="{{ $reservation->event->file->name }}" @endif
                                        data-lecturers="@foreach($lecturers[$reservation->event->id] as $lecturer){{ $lecturer->lecturer->id }};@endforeach">
                                        Redaguoti
                                    </button>
                                </th>
                                @endif
                            </tr>
                            @endforeach
                            <tr><th></th><th></th><th></th><th></th><th></th><th></th></tr>
                            <th style="border:0;"><p class="heading-small text-muted mt-5" style="margin-left: -20; font-weight:bold;">{{ __('Praėjusios paskaitos') }}</p></th>

                        
                            <tr>
                                <th>Paskaitos pavadinimas:</th>
                                <th>Destytojas:</th>
                                <th>Data ir laikas:</th>
                                <th>Dalyviai/Iš viso vietų:</th>
                                <th>Adresas:</th>
                                <th>Centras, kabinetas:</th>
                                <th style="text-align: center;">Veiksmas</th>
                            </tr>
                            @foreach($pastEvents as $reservation)
                            <tr>
                                <th style="font-weight:normal">{{ $reservation->event->name}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->lecturer->lecturer->user->firstname }} {{ $reservation->event->lecturer->lecturer->user->lastname }}</th>
                                <th style="font-weight:normal">{{ $reservation->date }}, {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }}</th>
                                <th style="font-weight:normal">{{ $reservation->event->max_capacity-$reservation->event->capacity_left }}/{{ $reservation->event->max_capacity}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->room->steam->city->city_name}}, {{ $reservation->event->room->steam->address}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->room->steam->steam_name}}, {{ $reservation->event->room->room_number}}</th>
                                @if(Auth::user()->isRole()!="mokytojas")
                                <th style="text-align: center;"><button href ="#" class="show-participants btn btn-success btn-sm" data-id="{{$reservation->event->id}}">Dalyviai</button></th>
                                @endif
                            </tr>
                            @endforeach
                        </table>
                    </div>

                    <div class="modal fade" id="show" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Registracijos keitimas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                  <form action = "/manopaskaitos" method="post">
                                      @csrf
                                      <div class="form-group">
                                          <label for="">Paskaitos pavadinimas :</label>
                                          <b id ="name"/>
                                      </div>
                                      <div class="form-group">
                                          <input  type="hidden"type="text" name="event_id" id="id">
                                      </div>
                                      <br>
                                      <div class="form-group">
                                          <b>Mokinių skaičius</b>
                                          <input id='set-capacity'name ="pupil_count" class="col-5" value ="1" min="1" type="number" placeholder="0" min="0">
                                      </div>
                                  </form>
                                  <div class="modal-footer">
                                      <div class="form-group">
                                          <button type="submit"  class="btn btn-success mt-4">{{ __('Patvirtinti') }}</button>
                                      </div>
                                  </div>
                                </div>
                           </div>
                        </div>
                    </div>

                   
                    <div class="modal fade" id="show1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Registracijos keitimas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                 Registracijos negalite redaguoti, nes liko mažiau nei dvi dienos iki paskaitos pradžios!
                                </div>
                           </div>
                        </div>
                    </div> 
                    <div class="modal fade" id="show-participants" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Paskaitos dalyvių sąrašas</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <table class ="table table-hover table-responsive table-sm" cellspacing="0" >
                            
                            
                                        <tr>
                                            <th>Mokytojas</th>
                                            <th>Mokinių skaičius</th>
                                        </tr>
                                        <tr>
                                            <th style="font-weight:normal">Belekoks geras</th>
                                            <th style="font-weight:normal">8</th>
                                    </table>                            
                                </div>
                           </div>
                        </div>
                    </div>


            </div>
        </div>
    </div>

    </div>
    
@if(Auth::user()->isRole()=="paskaitu_lektorius")
<!-- Event Editing Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby = "editEventLabel" aria-hidden = "true">
    <div class="modal-dialog modal-lg">
        <div class = "modal-content">
            <div class = "modal-header pb-3">
                <h2 class = "modal-title" id="editEventLabel">Paskaitos redagavimas</h2>
                <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">&times;</button>
            </div>

            <form id="eventEditingModalForm" method="post">
            <input type="hidden" name="_method" value="patch" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <input type="hidden" id="editing_id" name="edited_id">

            <div class="modal-body pt-2">
                <div class="row d-flex justify-content-start">

                <div class="col-md-8">
                <div class="form-group">
                    <input class="form-control" placeholder="Paskaitos pavadinimas" name="name" id="editing_name"">
                </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <select onload="update_dropdown()" class="form-control dropdown-menu-arrow dynamic-lecturers" name="course_id" id="course_id" data-dependent="lecturer_id">
                    <option value="" selected disabled="">Kursai</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_title }} {{ "(".$course->subject->subject.")" }}</option>
                    @endforeach
                    </select>
                </div>
                </div>

                <div class="card bg-secondary shadow" style="position: absolute; margin-left: 900px; margin-top: -68px">
                    <div class="card-header bg-white border-0">
                    <input type="hidden" id="lecturers_was" name="lecturers_was">
                    <h2 class="col-12 mb-0">{{ __('Dėstytojai') }}</h2>
                    </div>
                    <div class="card-body">
                        <div class="col">
                            <div class="form-group">
                                <table class="table table-sm align-items-center table-scroll" id="lecturer_id"></table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <select class="form-control dropdown-menu-arrow dynamic-ccr" name="city_id" id="city_id" data-dependent="steam_id">
                    <option value="" selected disabled="">Miestas</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                    @endforeach
                    </select>
                </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <select class="form-control dropdown-menu-arrow dynamic-ccr" name="steam_id" id="steam_id" data-dependent="room_id">
                        <option value="" selected disabled>STEAM centras</option>
                    </select>
                </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <input type="hidden" id="room_was" name="room_was">
                    <select class="form-control dropdown-menu-arrow room update-time" name="room_id" id="room_id">
                    <option selected disabled>Kambarys</option>
                    </select>
                </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <input type="hidden" id="date_was" name="date_was">
                    <input class=" form-group form-control input-group update-time" name="date" placeholder="Data" id="datepicker" />
                </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <input type="hidden" id="time_was" name="time_was">
                    <select name="time" id="time" class="form-control dropdown-menu-arrow">
                    </select>
                </div>
                </div>

                <div class="col-md-4">
                <div class="form-group">
                    <input type="hidden" id="edit-capacity_left" name="capacity_left">
                    <input class="form-control input-group" id="edit-capacity" type="number" min="1" name="capacity" value="1" placeholder="Žmonių skaičius">
                </div>
                </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control" rows="5" placeholder="Apie paskaitą ..." id="description" name="description" maxlength="200"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                <input type="file" class="form-control-file" multiple="" name="file" id="file" style="display:none" aria-describedby="fileHelp">
                <button type="button" class="btn-default" onclick="document.getElementById('file').click()">Pasirinkite failą</button>
                <div style="display: inline-block;" id="file-name"></div>
                <small id="fileHelp" class="form-text text-muted"> Failą pridėti nėra būtina. Leidžiami formatai: doc, docx, pdf, txt, pptx, ppsx, odt, ods, odp, tiff, jpeg, png. Failas negali būti didesnis nei 5MB.</small>
                </div>
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
@endif

<!-- Editing scripts -->
@if (Auth::user()->isRole()=="paskaitu_lektorius")
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript">
  new GijgoDatePicker(document.getElementById('datepicker'), { uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });

  $('.dynamic-lecturers').change(function update_lecturers(){
      if($(this).val() != ''){
          var select = $(this).attr("id");
          var value = $(this).val();
          var dependent = $(this).data('dependent');
          var _token = $('input[name="_token').val();
          $.ajax({
              url:"{{ route('createeventcontroller.fetch_lecturers') }}",
              method: "POST",
              data:{select:select, value:value, _token:_token},
              success:function(result){
                  $('#'+dependent).html(result);
                  var lecturers = $('#lecturers_was').val();
                  var lecturers = lecturers.substring(0, lecturers.length-1);
                  var lecturersArray = lecturers.split(';');
                  for(var i=0; i<lecturersArray.length; i++){
                    $("#"+lecturersArray[i]).prop("checked", "checked");
                  }
              }
          })
      }
  })

  $("#file").change(function(){
    $("#file-name").text(this.files[0].name);
  });

  $('.dynamic-ccr').change(function update_multi_dropdown(){
      if($(this).val() != ''){
          var select = $(this).attr("id");
          var value = $(this).val();
          var dependent = $(this).data('dependent');
          var _token = $('input[name="_token').val();
          $.ajax({
              async: false,
              url:"{{ route('createeventcontroller.fetch') }}",
              method: "POST",
              data:{select:select, value:value, _token:_token, dependent:dependent},
              success:function(result){
                  $('#room_id').html('<option value="" selected disabled>Kambarys</option>');
                  $('#'+dependent).html(result);
              }
          })
      }
  })

  $('.room').change(function set_new_max_capacity(){
    var room_value = $('#room_id').val();
    var room_capacity = $('#room_id').find(':selected').data('capacity');
    $('#room_id').find(':selected').data('capacity');
    $('#edit-capacity').attr("max", room_capacity);
    if(parseInt($('#edit-capacity').val()) > parseInt($('#edit-capacity').attr("max"))){
        $('#edit-capacity').val($('#edit-capacity').attr("max"));
    }else if($('#edit-capacity').val() < $('#edit-capacity').attr("min")){
        $('#edit-capacity').val($('#edit-capacity').attr("min"));
    }
  })

  $('.update-time').change(function update_time(){
    var room_value = $('#room_id').val();
    var date_value = $('#datepicker').val();
    if(room_value != null && date_value != ''){
      var _token = $('input[name="_token').val();
      $.ajax({
          async: false,
          url:"{{ route('createeventcontroller.fetch_time') }}",
          method: "POST",
          data:{room_value:room_value, date_value:date_value, _token:_token},
          success:function(result){
              $('#time').html(result);
          }
      })

    var timeWas = $('#time_was').val();
    if(date_value == $('#date_was').val() && timeWas != "" && room_value==$('#room_was').val()) {
        $("#time").append(new Option(timeWas, timeWas));
    }
      $('#time option').filter(function() { return ($(this).text() == timeWas); }).prop('selected', 'selected');
      document.querySelector("#time").dispatchEvent(new Event("change"));
    }
  })

  $('#edit-capacity').change(function(){
    if(parseInt($('#edit-capacity').val()) > parseInt($('#edit-capacity').attr("max"))){
        $('#edit-capacity').val($('#edit-capacity').attr("max"));
    }else if($('#edit-capacity').val() < $('#edit-capacity').attr("min")){
        $('#edit-capacity').val($('#edit-capacity').attr("min"));
    }
  })

  $('#set-capacity').change(function(){
    if(parseInt($('#set-capacity').val()) > parseInt($('#set-capacity').attr("max"))){
        $('#set-capacity').val($('#set-capacity').attr("max"));
    }else if($('#set-capacity').val() < $('#set-capacity').attr("min")){
        $('#set-capacity').val($('#set-capacity').attr("min"));
    }
  })
</script>

<script type="text/javascript">
  $(document).on('click', '.show-edit-event', function() {
  var id = $(this).data('id');
  $('#editing_id').val(id);
  var route = '{{ route("manopaskaitos.updateEvent", ":id") }}';
  route = route.replace(':id', id);
  document.getElementById('eventEditingModalForm').setAttribute("action", route);
  $('#editing_name').val($(this).data('name'));

  $('#lecturers_was').val($(this).data('lecturers'));

  var course_selected = $(this).data('course_title') + " (" + $(this).data('subject_title') + ")";
  $('#course_id option').filter(function() { return ($(this).text() == course_selected); }).prop('selected', 'selected');
  document.querySelector("#course_id").dispatchEvent(new Event("change"));

  var city_selected = $(this).data('city')
  $('#city_id option').filter(function() { return ($(this).text() == city_selected); }).prop('selected', 'selected');
  document.querySelector("#city_id").dispatchEvent(new Event("change"));

  var steam_center_selected = $(this).data('steam_center');
  $('#steam_id option').filter(function() { return ($(this).text() == steam_center_selected); }).prop('selected', 'selected');
  document.querySelector("#steam_id").dispatchEvent(new Event("change"));

  var room_selected = $(this).data('room');
  $("#room_was").val($(this).data('room_id'));
  $('#room_id option').filter(function() { return ($(this).text() == room_selected); }).prop('selected', 'selected');
  document.querySelector("#room_id").dispatchEvent(new Event("change"));

  $("#date_was").val($(this).data('reservation_date'));
  $('#datepicker').val($(this).data('reservation_date'));
  document.querySelector("#datepicker").dispatchEvent(new Event("change"));

  var timeWas = $(this).data('reservation_time');
  $("#time_was").val(timeWas);
  $("#time").append(new Option(timeWas, timeWas));
  $('#time option').filter(function() { return ($(this).text() == timeWas); }).prop('selected', 'selected');
  document.querySelector("#time").dispatchEvent(new Event("change"));

  var used = $(this).data('event_capacity_used');
  var usedArray = used.split('-');
  var used = usedArray[0]-usedArray[1];
  $('#edit-capacity_left').val(used);
  $('#edit-capacity').attr("min", used);
  $('#edit-capacity').val($(this).data('event_capacity'));
  document.querySelector("#edit-capacity").dispatchEvent(new Event("change"));

  $('#description').val($(this).data('event_description'));
  $("#file-name").text($(this).data('file_name'));
  })
</script>

@endif
<!-- /Editing scripts -->

@endsection
<script src="https://oss.maxcdn.com/viska isvalai. Pridedi libs/html5shiv/3.7.2/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.js"></script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css">
<script type="text/javascript">
    $(document).on('click', '.show-modal', function() {
        $('#show').modal('show');
        $('#id').val($(this).data('id'));
        $('#name').text($(this).data('name'));
        $('#set-capacity').attr("max",$(this).data('capacity'));
        $('#set-capacity').val($(this).data('pupil_count'));
    })
    $(document).on('click', '.show-toolate-modal', function() {
        $('#show1').modal('show');
        $('#id').val($(this).data('id'));

    })
    $(document).on('click', '.show-participants', function() {
        $('#show-participants').modal('show');

    })
</script>
