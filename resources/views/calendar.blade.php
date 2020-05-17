@extends('layouts.app', ['title' => __('User Profile')])


@section('content')
@include('users.partials.header', [
    'title' => __('Kalendorius'),
    'description' => __('Šiame puslapyje galite apžvelgti praėjusias ir būsimas paskaitas, taip pat matyti, kur ir kada jos vyko.'),
    'class' => 'col-lg-7'
])
<div class="container-fluid mt--7" style="left:0">
    <div class="col-xl-13 order-xl-1 mt-3">
        <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">

                <div class="row align-items-center">

                    <div id='calendar'style="width: 100%;padding: 0 1px;    white-space: normal;   max-height:none!important;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css' />
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/locale/lt.js"></script>


<script>


            // page is now ready, initialize the calendar...
        $('#calendar').fullCalendar({
            displayEventEnd:true,
            eventColor: '#356ACC',
            eventTextColor: '#fff',
            timeFormat: 'h:mm', // uppercase H for 24-hour clock
            header: {
            center: 'month,agendaWeek' // buttons for switching between views
            },
            // put your options and callbacks here
            events : [
                @foreach($reservations as $reservation)
                {
                    title : ' {{ $reservation->event->name }}',
                    start : ' {{ $reservation->date.' '.$reservation->start_time }}',
                    end: ' {{ $reservation->date.' '.$reservation->end_time }}',
                    center: ' STEAM centras: {{ $reservation->event->room->steam->steam_name}}',
                    room: ' Kambarys: {{ $reservation->event->room->room_number}}',
                    city: ' Miestas: {{ $reservation->event->room->steam->city->city_name}}'
                },
                @endforeach
            ],
            eventRender: function(event, element, view) {
                var limit = 27;       
                 element.find('.fc-title')[0].insertAdjacentHTML('beforebegin', "<br>");


},


        eventMouseover: function(calEvent, jsEvent) {
                
    var tooltip = '<div class="tooltipevent" style="border-radius: 10px; background:#ccc;position:absolute;z-index:10001;">' + '<b>' + calEvent.title +'</b>' +'<br>'+ calEvent.city + '<br>'+ calEvent.center  +'<br>'+ calEvent.room+'</div>';
    var $tooltip = $(tooltip).appendTo('body');

    $(this).mouseover(function(e) {
        $(this).css('z-index', 10000);
        $tooltip.fadeIn('500');
        $tooltip.fadeTo('10', 1.9);
    }).mousemove(function(e) {
        $tooltip.css('top', e.pageY + 10);
        $tooltip.css('left', e.pageX + 20);
    });
},

eventMouseout: function(calEvent, jsEvent) {
    $(this).css('z-index', 8);
    $('.tooltipevent').remove();
},
            
        })
        

</script>


@endsection