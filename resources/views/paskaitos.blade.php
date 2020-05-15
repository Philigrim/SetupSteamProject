@extends('layouts.app', ['title' => __('Paskaitos')])

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
    @include('layouts.headers.cards')
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if (session()->has('message'))
        @if(session()->get('message')==('Jūs jau užsiregistravę į šią paskaitą!')||session()->get('message')==('Jūs šiuo metu jau užimtas!'))
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
    <div class="container">

      <!-- Search form -->
      <form action="{{ route('events.search') }}" method="get">
        <div class="input-group mt-3">
          <input class="form-control" name="search" type="text" @if (isset($search_value)) value="{{ $search_value }}" @endif placeholder="Raskite norimą paskaitą">
          <div class="input-group-append">
          <button class="btn"><i class="fa fa-search"></i></button>
          </div>
        </div>
      </form>

      @if(isset($filtered) && $filtered == 't')
        <div class="row">
          <form action="{{ route('Paskaitos')}} " method="get">
              <input id="clearFilers" type="submit" class="btn btn-danger ml-3" value="Išvalyti visus filtrus"/>
          </form>

          @if(isset($category_value)) <input type="button" class="btn btn-danger ml-3 mb-3" value="Dalykas: {{ $category_value }} (x)" onclick="deleteValue(Category)"></input> @endif
          @if(isset($capacity_value)) <input type="button" class="btn btn-danger ml-3 mb-3" value="Laisvos vietos: bent {{ $capacity_value }} (x)" onclick="deleteValue(Capacity)"></input> @endif
          @if(isset($city_value)) <input type="button" class="btn btn-danger ml-3 mb-3" value="Miestas: {{ $city_value }} (x)" onclick="deleteValue(City)"></input> @endif
          @if(isset($date_value)) <input type="button" class="btn btn-danger ml-3 mb-3" value="
            @if($date_value=="past") Rodomos tik praejusios paskaitos (x)" @endif
            @if($date_value=="future") Rodomos tik ateinančios paskaitos (x)" @endif
            @if($date_value=="all") Data: visos (x)" @endif
            @if($date_value=="oneDay") Data: {{ $dateOneDay }} (x)" @endif
            @if($date_value=="from") Data nuo: {{ $dateFrom }} (x)" @endif
            @if($date_value=="till") Data iki: {{ $dateTill }} (x)" @endif
            @if($date_value=="interval") {{ $dateFrom }} - {{ $dateTill }} (x)" @endif
            onclick="deleteValue(dateInput)"></input>
          @endif
        </div>
      @endif

      @csrf
      @foreach($reservations as $reservation)
      <div class="row">

        <div class="col">
          <div class="card flex-row mb-3" id="{{ $reservation->event->id }}">
            @if(strlen($reservation->event->description) > 118)
                <img class="border-0 bg-white rounded-left" width="220" height="211" src="argon/img/brand/steam1-lectures.png" alt="">
            @else
                <img class="border-0 bg-white rounded-left" width="216" height="183" src="argon/img/brand/steam1-lectures.png" alt="">
            @endif
            <div class="w-100 mb-0 pb-0">
              <div class="card-header p-0 m-0  win-light-blue">
                <h2 class="ml-3 pb-0 m-0">{{ $reservation->event->name }}</h2>
                <div class="ml-3 mb-1 row">
                    <div class="p-0 pl-1 pr-1 bg-primary rounded">
                        <h6 class="text-white text-center mb-0">{{ $reservation->event->course->course_title }}</h6>
                    </div>
                </div>
              </div>
              <div class="card-body p-0 win-light-blue">
                <div class="row ml-1">
                  <img class="icon-sm pt-3" src="argon/img/icons/common/place.svg" alt="">
                  <h5 class="pt-3 pr-2">{{ $reservation->room->steam->city->city_name }}, {{ $reservation->room->steam->address }}</h5>
                  <img class="icon-sm pt-3" src="argon/img/icons/common/clock.svg" alt="">
                  <h5 class="pt-3 pr-2">{{ $reservation->date }}, {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }}</h5>
                  <img class="icon-sm pt-3" src="argon/img/icons/common/user.svg" alt="">
                  @if($reservation->event->capacity_left > 0)
                      <h5 class="pt-3 pr-2">{{ $reservation->event->capacity_left }}/{{ $reservation->event->max_capacity }}</h5>
                  @else
                      <h5 class="pt-3 pr-2 text-red">Vietų nėra</h5>
                  @endif
                  <img class="icon-sm pt-3" src="argon/img/icons/common/book.svg" alt="">
                  <h5 class="pt-3">{{ $reservation->event->course->subject->subject }}</h5>
                </div>
                <div class="ml-3">
                    <p>{{ $reservation->event->description }}</p>
                </div>
                <div class="row mt--3 p-0 m-0" id="lecturers">
                  @foreach($lecturers[$reservation->event->id] as $lecturer)
                    <button class="p-0 shadow--hover ml-3 mb-1 pl-1 pr-1 bg-primary rounded border-0">
                      <h6 class="text-white text-center mb-0">{{ $lecturer->lecturer->user->firstname }} {{ $lecturer->lecturer->user->lastname }}</h6>
                    </button>
                  @endforeach
                </div>
              </div>
              <div class="card-footer win-light-blue pb-0 pt-0 mb-0">
                <div class="row justify-content-between mb--2 p-0">
                  <div class="flex-column mt-1">
                  @if(isset($reservation->event->file_id))
                    <div class="form-group">
                      <i class="fa fa-file" style="font-size:24px"></i>
                      <a href = "{{route('downloadFile',$reservation->event->file->id)}}"  id = "hyper">Dėstytojo pridėtas failas</a>
                    </div>
                  @endif
                  </div>
                  <div class="flex-column">
                  @if(Auth::user()->isRole() == 'mokytojas')
                      <button href ="#" data-id="{{$reservation->event->id}}" data-capacity= "{{$reservation->event->capacity_left}}"class="show-modal p-1 btn btn-primary my-2 exampleModalCenter" id="lol" data-name="{{$reservation->event->name}}">Registruotis</button>
                  @else
                      <button disabled href ="#" data-id="{{$reservation->event->id}}" data-capacity= "{{$reservation->event->capacity_left}}"class="show-modal p-1 btn btn-light my-2 exampleModalCenter" id="lol" data-name="{{$reservation->event->name}}">Registruotis</button>
                  @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        @if(Auth::user()->isRole()=="admin")
        <div class="col-" style="position: absolute; margin-left:1140px;">
          @if(date($reservation->date) < date('Y-m-d'))
            <button class="btn btn-dark" style="width: 130px; opacity: 1;" disabled>Praejęs</button>
          @elseif($reservation->event->capacity_left == 0)
            <button class="btn btn-primary" style="width: 130px" disabled>No Capacity</button>
          @elseif(date($reservation->date) > date('Y-m-d', strtotime(date('Y-m-d'). ' + 2 days')) || (date($reservation->date) == date('Y-m-d', strtotime(date('Y-m-d'). ' + 2 days')) && date($reservation->start_time) > date('h:i:s')))
            <input data-id="{{ $reservation->event->id }}" class="promote-class" @if($reservation->event->is_manual_promoted) checked @endif type="checkbox" data-onstyle="warning" data-toggle="toggle" data-on="Neiškelti" data-off="Iškelti">
          @else
            <button class="btn btn-warning" style="width: 130px; opacity: 1;" disabled>Iškeltas</button>
          @endif
          <br>
          <button class="btn btn-success mt-2 show-edit-event" style="width: 95%" data-toggle = "modal" data-target = "#editEventModal"
            data-id="{{ $reservation->id }}" data-name="{{ $reservation->event->name }}" data-course_title="{{ $reservation->event->course->course_title }}" data-subject_title="{{ $reservation->event->course->subject->subject }}" data-city="{{ $reservation->room->steam->city->city_name }}" data-steam_center="{{ $reservation->room->steam->steam_name }}" data-room="{{ $reservation->room->room_number }}({{$reservation->room->capacity }}) {{ $reservation->room->subject->subject }}" data-reservation_date="{{ $reservation->date }}" data-reservation_time="{{ substr($reservation->start_time, 0, 5) }}-{{ substr($reservation->end_time, 0, 5) }}" data-event_capacity="{{ $reservation->event->max_capacity }}" data-event_description="{{ $reservation->event->description }}" @if(isset($reservation->event->file->name)) data-event_file={{ $reservation->event->file->name }} @endif">
            Redaguoti
          </button>
          
          <form action="{{ url('/paskaitos', [$reservation->event->id]) }}" method="post">
            <button class="btn btn-danger mt-2" style="width: 95%">Ištrinti</button>
            <input type="hidden" name="_method" value="delete" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
          </form>
        </div>
        @endif

      </div>
      {{ csrf_field() }}
      @endforeach

    </div>

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
                    <select class="form-control dropdown-menu-arrow room" name="room_id" id="room_id">
                      <option selected disabled>Kambarys</option>
                    </select>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <div class="form-group">
                    <input class=" form-group form-control input-group update-time" name="date" placeholder="Data" id="datepicker" />
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <select name="time" id="time" class="form-control dropdown-menu-arrow">
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
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

    <!-- Registration to Event Modal -->
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
        {{-- Modal Form Show POST
        <div id="show" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"></h4>
                          </div>
                            <div class="modal-body">

                            <div class="form-group">
                              <label for="">Title :</label>
                              <b id="name"/>
                              <a href="#" class="show-modal btn btn-info btn-sm" data-id="{{$event->id}}" data-name="{{$event->name}}">
                                <i class="fa fa-eye"></i>
                              </a>
                            </div>

                            </div>
                            </div>
                          </div>
        </div> --}}
<script type="text/javascript">
  new GijgoDatePicker(document.getElementById('dateOneDay'), { calendarWeeks: true, uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });
  new GijgoDatePicker(document.getElementById('dateFrom'), { calendarWeeks: true, uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });
  new GijgoDatePicker(document.getElementById('dateTill'), { calendarWeeks: true, uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });

  window.onload=function(){
    //get the divs to show/hide
    oneDay = document.getElementById("dateOneDayDiv");
    dateFrom = document.getElementById("dateFromDiv");
    dateTill = document.getElementById("dateTillDiv");
  }

  function showHide(elem) {
    if(elem.value == "oneDay") {
      oneDay.style.display = 'flex';
      dateFrom.style.display = 'none';
      dateTill.style.display = 'none';
    } else if(elem.value == "from"){
      oneDay.style.display = 'none';
      dateFrom.style.display = 'flex';
      dateTill.style.display = 'none';
    } else if(elem.value == "till"){
      oneDay.style.display = 'none';
      dateFrom.style.display = 'none';
      dateTill.style.display = 'flex';
    } else if(elem.value == "interval"){
      oneDay.style.display = 'none';
      dateFrom.style.display = 'flex';
      dateTill.style.display = 'flex';
    } else {
      oneDay.style.display = 'none';
      dateFrom.style.display = 'none';
      dateTill.style.display = 'none';
    }
    showDeleteButton(deleteDateButton);
  }
</script>

@if (Auth::user()->isRole()=="admin")

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
    $('#edit-capacity').attr("max", room_capacity);
    if(parseInt($('#edit-capacity').val()) > parseInt($('#edit-capacity').attr("max"))){
        $('#edit-capacity').val($('#edit-capacity').attr("max"));
    }
  })

  $('.update-time').change(function update_time(){
    if ($("#time option:selected" ).text() == ""){
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
      }
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

{{--Editing modal script--}}
<script type="text/javascript">
  $(document).on('click', '.show-edit-event', function() {
  var id = $(this).data('id');
  $('#editing_id').val(id);
  var route = '{{ route("paskaitos.update", ":id") }}';
  route = route.replace(':id', id);
  document.getElementById('eventEditingModalForm').setAttribute("action", route);
  $('#editing_name').val($(this).data('name'));

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
  $('#room_id option').filter(function() { return ($(this).text() == room_selected); }).prop('selected', 'selected'); 
  document.querySelector("#room_id").dispatchEvent(new Event("change"));

  $('#datepicker').val($(this).data('reservation_date'));
  document.querySelector("#datepicker").dispatchEvent(new Event("change"));

  $('#datepicker')[0].options[0].innerHTML = "Laikas: " + $(this).data('reservation_time');
  document.querySelector("#time").dispatchEvent(new Event("change"));

  $('#edit-capacity').val($(this).data('event_capacity'));
  document.querySelector("#edit-capacity").dispatchEvent(new Event("change"));
  
  $('#description').val($(this).data('event_description'));
  $('#file-name').text($(this).data('event_file'));
  })
</script>
@endif

@endsection

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
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
    })
</script>