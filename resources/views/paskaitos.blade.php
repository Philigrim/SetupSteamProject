@extends('layouts.app', ['title' => __('Paskaitos')])

@section('additional_header_content')
{{--Gijgo--}}
    <script src="/gijgo/dist/modular/js/core.js" type="text/javascript"></script>
    <link href="/gijgo/dist/modular/css/core.css" rel="stylesheet" type="text/css">

{{--Date pickeris--}}
    <link href="/gijgo/dist/modular/css/datepicker.css" rel="stylesheet" type="text/css">
    <script src="/gijgo/dist/modular/js/datepicker.js"></script>
@endsection

@section('content')
    @include('layouts.headers.cards')
    <div class="container-fluid mt-1 ml--5">
      
        @foreach($reservations->split($count)->reverse() as $row)
            @csrf
            <div class="flex-row d-inline-flex">
                @foreach($row as $reservation)
                    <div class="ml-4 bg-gradient-secondary p-2 border-bottom shadow rounded mt-2 col-md-6 win-event" id="{{ $reservation->event->id }}">
                        <div class="col">
                            <h2>{{ $reservation->event->name }}</h2>
                            <div class="flex-row d-inline-flex ml--2 mt--3">
                                <img class="icon-sm pt-3" src="argon/img/icons/common/place.svg" alt="">
                                <h5 class="pt-3 pr-2">{{ $reservation->room->steam->address }}</h5>
                                <img class="icon-sm pt-3" src="argon/img/icons/common/clock.svg" alt="">
                                <h5 class="pt-3 pr-2">{{ $reservation->date }}, {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }}</h5>
                                <img class="icon-sm pt-3" src="argon/img/icons/common/user.svg" alt="">
                                <h5 class="pt-3 pr-2">{{ $reservation->event->capacity_left }}/{{ $reservation->event->max_capacity }}</h5>
                                <img class="icon-sm pt-3" src="argon/img/icons/common/book.svg" alt="">
                                <h5 class="pt-3">{{ $reservation->event->course->subject->subject }}</h5>
                            </div>
                            <p>{{ "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum." }}</p>
                            <div class="flex-row d-inline-flex ml--2 mt--3" id="lecturers">
                                @foreach($lecturers[$reservation->event->id] as $lecturer)
                                    <button class="ml-3 mt-3 p-1 btn btn-dark my-2">
                                        {{ $lecturer->lecturer->user->firstname }} {{ $lecturer->lecturer->user->lastname }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-4">
                                    <a href="" class="align-self-center"><img src="argon/img/icons/common/document-blue.svg" class="icon-sm" alt=""></a>
                                    @if(Auth::user()->isRole() === 'mokytojas')
                                        <button href ="#" data-id="{{$reservation->event->id}}" class="show-modal btn btn-primary my-2 exampleModalCenter" id="lol" data-name="{{$reservation->event->name}}">Registruotis</button>
                                    @endif
                                </div>
                            </div>
                            {{-- <a href="#" class="show-modal btn btn-info btn-sm"  data-name="{{$event->name}}">
                              <i class="fa fa-eye"></i>
                            </a> --}}
                          </div>
                    </div>
                @endforeach
            </div>
            {{ csrf_field() }}
        @endforeach
    </div>
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
             <input name ="pupil_count" class="col-5" type="number" placeholder="0" min="0">
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
    new GijgoDatePicker(document.getElementById('dateFrom'), { calendarWeeks: true, uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });
    new GijgoDatePicker(document.getElementById('dateTo'), { calendarWeeks: true, uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });

    window.onload=function()
    {
    //get the divs to show/hide
    dateFiltersDivs = document.getElementById("dateFilters").getElementsByTagName('div');
    }
     
    function showHide(elem) {
    if(elem.value == "oneDay") {
        //unhide the divs
        dateFiltersDivs[0].style.display = 'flex';
        dateFiltersDivs[2].style.display = 'none';
    } else if(elem.value == "interval"){
        //unhide the divs
        dateFiltersDivs[0].style.display = 'flex';
        dateFiltersDivs[2].style.display = 'flex';
     }
 }
 </script>
@endsection

<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
    $(document).on('click', '.show-modal', function() {
    $('#show').modal('show');
    $('#id').val($(this).data('id'));
    $('#name').text($(this).data('name'));})

    $('.dynamic').change(function update_dropdown(){
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
</script>
