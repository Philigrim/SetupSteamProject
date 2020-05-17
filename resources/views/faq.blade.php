@extends('layouts.app', ['title' => __('Dažniausiai užduodami klausimai')])

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
@include('users.partials.header', ['title' => __('Dažniausiai užduodami klausimai'),
         'description' => __('Šiame puslapyje galite rasti atsakymus į dažniausiai vartotojų užduodamus klausimus.')])

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

<!-- Question Asking Modal -->
<div class = "modal fade" id = "askQuestionModal" tabindex = "-1" role = "dialog" aria-labelledby = "askQuestionLabel" aria-hidden = "true">

    <div class = "modal-dialog modal-lg mt-9">
        <div class = "modal-content">

            <div class = "modal-header">
                <h2 class = "modal-title" id = "askQuestionLabel">
                    Naujo klausimo kūrimas
                </h2>
                <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
                    &times;
                </button>
            </div>

            <form action="{{ route('faq.store.question') }}" method="post">
            @csrf

            <div class = "modal-body">
                <p>Neradote atsakymo į rūpimą klausimą? Pateikite jį žemiau ir mes pasistengsime atsakyti!</p>
                <br>
                <label class="mt-2">Klausimas:</label>
                <textarea class="form-control" rows="3" placeholder="Įveskite norimą klausimą" name="question" required
                oninvalid="this.setCustomValidity('Klausimo langelis negali būti tuščias.')"></textarea>
            </div>

            <div class = "modal-footer">
                <button type = "button" class = "btn btn-danger" data-dismiss = "modal">
                    Atšaukti
                </button>

                <button type = "submit" class = "btn btn-success">
                    Pateikti klausimą
                </button>

            </div>

            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div>

<!-- Question Answering Modal -->
<div class = "modal fade" id = "answerQuestionModal" tabindex = "-1" role = "dialog" aria-labelledby = "answerQuestionLabel" aria-hidden = "true">

    <div class = "modal-dialog modal-lg mt-9">
        <div class = "modal-content">

            <div class = "modal-header">
                <h2 class = "modal-title" id = "answerQuestionLabel">
                    Atsakymo į klausimą pateikimas
                </h2>
                <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
                    &times;
                </button>
            </div>

            <form action="{{ route('faq.store.answer') }}" method="post">
            <input type="hidden" name="_method" value="patch" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class = "modal-body">
                <label class="mt-2">Klausimas:</label>

                <br>

                <div class="row">
                    <div class="col">
                        <select class="form-control" name="question">
                        @foreach ($questions as $question)
                        <option id="{{ $question->faq_id }}" value="{{ $question->question }}">{{ $question->question }}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <label class="mt-2">Atsakymas:</label>
                <textarea class="form-control" rows="3" placeholder="Atsakykite į pasirinktą klausimą" name="answer" required
                oninvalid="this.setCustomValidity('Klausimo langelis negali būti tuščias.')"></textarea>
            </div>

            <div class = "modal-footer">

                <button type = "button" class = "btn btn-danger" data-dismiss = "modal">
                    Atšaukti
                </button>

                <button type = "submit" class = "btn btn-success">
                    Pateikti klausimą
                </button>

            </div>

            </form>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div>

<div class="container-fluid row d-flex justify-content-center">

    <!-- Questions and Answers -->
    <div class="col-xl-6">
    <div clas="row">
        @foreach ($questions_and_answers as $question_and_answer)

        <div class="card card-stats mt-3 xl-7">
            <div class="card-body border border-primary shadow rounded">

                @if(Auth::user()->isRole() === 'admin')
                <div class="row float-right">
                <button 
                    data-id="{{ $question_and_answer->id }}" data-question="{{ $question_and_answer->question }}" data-answer="{{ $question_and_answer->answer }}"
                    class="show-edit-faq btn btn-success ml-3 mb-3" data-toggle = "modal" data-target = "#editfaqModal">
                    Redaguoti
                </button>
                
                <form action="{{ url('/faq', [$question_and_answer->id]) }}" method="post">
                    <Button class="btn btn-danger ml-2" type="submit">Ištrinti</Button>
                    
                    <input type="hidden" name="_method" value="delete" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                </div>

                <br>
                <br>
                @endif
                <div class="border-top mt-2 mb-2"></div>

                <p class="font-weight-bold ml-2">K: {{ $question_and_answer->question }}</p>

                <div class="border-top mt-2 mb-2"></div>

                <p class="ml-2">A: {{ $question_and_answer->answer }}</p>

                <div class="border-top mt-2 mb-2"></div>

            </div>
        </div>
        @endforeach

        <!-- FAQ Editing Modal -->
        <div class="modal fade" id="editfaqModal" tabindex="-1" role="dialog" aria-labelledby = "editfaqLabel" aria-hidden = "true">
            <div class="modal-dialog modal-lg">
                <div class = "modal-content">
                    <div class = "modal-header pb-3">
                        <h2 class = "modal-title" id="editfaqLabel">Klausimo ir atsakymo redagavimas</h2>
                        <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">&times;</button>
                    </div>

                    <form id="faqEditingModalForm"  method="post">
                    <input type="hidden" name="_method" value="patch" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <input type="hidden" id="editing_id" name="edited_id">
                    

                    <div class="modal-body pt-2 pb-0">
                        <div class="border-top mt-2 mb-2"></div>

                        <div>
                            <span class="ml-2">K:</span>
                            <input style="width: 95%" placeholder="Įveskite klausimą" id="editing_question" name="edited_question" required
                            oninvalid="this.setCustomValidity('Klausimo langelis negali būti tuščias.')">
                        </div>

                        <div class="border-top mt-2 mb-2"></div>

                        <div>
                            <span class="ml-2">A:</span>
                            <input style="width: 95%" placeholder="Įveskite atsakymą" id="editing_answer" name="edited_answer" required
                            oninvalid="this.setCustomValidity('Atsakymo langelis negali būti tuščias.')">
                        </div>

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
                            <input data-id="{{ $reservation->event->id }}" class="promote-class" checked type="checkbox" data-onstyle="danger" data-toggle="toggle" data-on="Demote" data-off="Promote">
                        @else
                            <button class="btn btn-primary mt-2" disabled>AutoPromoted</button>
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
                        <h5 class="pt-3 pr-2">Dalyviai: {{ $reservation->event->max_capacity - $reservation->event->capacity_left }} iš {{ $reservation->event->max_capacity }}</h5>
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
    $(document).on('click', '.show-edit-faq', function() {
    var route = '{{ route("faq.update", ":id") }}';
    route = route.replace(':id', $(this).data('id'));
    document.getElementById('faqEditingModalForm').setAttribute("action", route);
    $('#editing_id').val($(this).data('id'));
    $('#editing_question').val($(this).data('question'));
    $('#editing_answer').val($(this).data('answer'));
    })
</script>
@endif

{{--Registration modal scripts--}}
@if (Auth::user()->isRole()=="mokytojas")
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