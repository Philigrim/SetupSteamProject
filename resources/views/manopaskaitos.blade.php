@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Mano paskaitos'),
        'description' => __('Šiame puslapyje galite apžvelgti praėjusias ir būsimas paskaitas, taip pat matyti, kur ir kada jos vyko.'),
        'class' => 'col-lg-7'
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
                        <th><p class="heading-small text-muted mb-4" style="font-weight:bold">{{ __('Būsimos paskaitos') }}</p></th>
 
                        <table class ="table table-hover table-responsive table-sm" cellspacing="0" >
                            
                            
                            <tr>
                                <th>Paskaitos pavadinimas:</th>
                                <th>Destytojas:</th>
                                <th>Data ir laikas:</th>
                                <th>Užrezervuota/Iš viso vietų</th>
                                <th>Miestas, centras, adresas</th>
                                <th>Kabinetas:</th>
                                <th>Veiksmas</th>
                            </tr>
                            @foreach($futureEvents as $reservation)
                            <tr>
                                <th style="font-weight:normal">{{ $reservation->event->name}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->lecturer->lecturer->user->firstname }} {{ $reservation->event->lecturer->lecturer->user->lastname }}</th>
                                <th style="font-weight:normal">{{ $reservation->date }}, {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }}</th>
                                <th style="font-weight:normal">{{ $reservation->event->capacity_left }}/{{ $reservation->event->max_capacity}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->room->steam->city->city_name}}, {{ $reservation->event->room->steam->steam_name}}, {{ $reservation->event->room->steam->address}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->room->room_number}}</th>
                                @if(Auth::user()->isRole()=="mokytojas")
                                @if(date($reservation->date) > date('Y-m-d', strtotime(date('Y-m-d'). ' + 2 days')))
                                <th><button href ="#" class="show-modal btn btn-success mt-2" data-id="{{$reservation->event->id}}" data-pupil_count = "{{ $reservation->event->teacher->pupil_count }}" data-capacity= "{{$reservation->event->capacity_left}}"  style="width: 94%" data-name="{{$reservation->event->name}}">Keisti</button> </th>
                                @else
                                <th><button href ="#" class="show-toolate-modal btn btn-success mt-2" data-id="{{$reservation->event->id}}" style="width: 94%">Keisti</button> </th>
                                @endif
                                @else 
                                <th><button href ="#" class="show-participants btn btn-success mt-2" data-id="{{$reservation->event->id}}" style="width: 94%">Rodyti dalyvius</button> </th>
                                @endif
                            </tr>

                            @endforeach
                            <th><p class="heading-small text-muted mt-5" style="margin-left: -20; font-weight:bold">{{ __('Praėjusios paskaitos') }}</p></th>

                        
                            <tr>
                                <th>Paskaitos pavadinimas:</th>
                                <th>Destytojas:</th>
                                <th>Data ir laikas:</th>
                                <th>Užrezervuota/Iš viso vietų:</th>
                                <th>Miestas, centras, adresas</th>
                                <th>Kabinetas:</th>
                                <th>Veiksmas</th>
                            </tr>
                            @foreach($pastEvents as $reservation)
                            <tr>
                                <th style="font-weight:normal">{{ $reservation->event->name}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->lecturer->lecturer->user->firstname }} {{ $reservation->event->lecturer->lecturer->user->lastname }}</th>
                                <th style="font-weight:normal">{{ $reservation->date }}, {{ substr($reservation->start_time, 0, 5) }} - {{ substr($reservation->end_time, 0, 5) }}</th>
                            
                                <th style="font-weight:normal"> {{ $reservation->event->capacity_left }}/{{ $reservation->event->max_capacity}} </th>
                            
                                <th style="font-weight:normal">{{ $reservation->event->room->steam->city->city_name}}, {{ $reservation->event->room->steam->steam_name}}, {{ $reservation->event->room->steam->address}}</th>
                                <th style="font-weight:normal">{{ $reservation->event->room->room_number}}</th>
                                @if(Auth::user()->isRole()!="mokytojas")
                                <th><button href ="#" class="show-participants btn btn-success mt-2" data-id="{{$reservation->event->id}}" style="width: 94%">Rodyti dalyvius</button> </th>
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
