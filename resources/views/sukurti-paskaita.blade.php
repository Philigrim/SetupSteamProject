@extends('layouts.app')

@section('additional_header_content')

{{--Gijgo--}}
    <script src="/gijgo/dist/modular/js/core.js" type="text/javascript"></script>
    <link href="/gijgo/dist/modular/css/core.css" rel="stylesheet" type="text/css">

{{--Date pickeris--}}
    <link href="/gijgo/dist/modular/css/datepicker.css" rel="stylesheet" type="text/css">
    <script src="/gijgo/dist/modular/js/datepicker.js"></script>


{{--Nedulio skriptai--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
@endsection

@section('content')
    @include('users.partials.header', ['title' => __('Sukurti paskaitą')])

        <form class="mt--5 d-flex justify-content-center" enctype="multipart/form-data" action = "/sukurti-paskaita" method="post">
            @csrf
            <div class="col-xl-6 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-12">
                                @if (session()->has('message'))

                                    @if(session()->get('message')==('Viskas zjbs!!!!!!')||session()->get('message')==('Jūs šiuo metu jau užimtas!'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                             {{ session()->get('message') }}
                                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                        </div>
                                    @else
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session()->get('message') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    @endif

                                @endif
                            </div>
                            <h2 class="col-12 mb-0">{{ __('Informacija apie paskaitą') }}</h2>
                        </div>
                    </div>
                    <div class="card-body">
                    @if(count($errors))
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                        <div class="primary-input-fields">
                            <div class="row d-flex justify-content-start">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Paskaitos pavadinimas" name="name" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select onload="update_dropdown()" class="form-control dropdown-menu-arrow dynamic-lecturers" name="course_id" id="course_id" data-dependent="lecturer_id" >
                                            <option value="" selected disabled>{{ "Kursai" }}</option>
                                            @foreach($courses as $course)
                                                <option value="{{ $course->id }}">{{ $course->course_title }} {{ "(".$course->subject->subject.")" }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-start">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control dropdown-menu-arrow dynamic-ccr" name="city_id" id ="city_id" data-dependent="steam_id" >
                                            <option value="" selected disabled>Miestas</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control dropdown-menu-arrow dynamic-ccr" name="steam_id" id="steam_id" data-dependent="room_id" >
                                            <option value="" selected disabled>STEAM centras</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control dropdown-menu-arrow update-time" name="room_id" id="room_id">
                                            <option selected disabled>Kambarys</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex justify-content-start" id="date-time-capacity0">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="my-date form-group form-control input-group update-time last-date" name="date" placeholder="Data" id="datepicker0" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="time form-control dropdown-menu-arrow" name="time" id="time0" >
                                            <option selected disabled>Laikas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="capacity form-control input-group" id="set-capacity0" type="number" min="1" max="100" name="capacity" value="1" placeholder="Žmonių skaičius">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <button type="button" id="add-item" class="btn btn-facebook"></button>
                            <button type="button" id="remove-item" class="btn btn-danger"></button>
                            <input id="set-row-amount" type="number" min="1" max="30" previous="1">
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control" rows="5" placeholder="Apie paskaitą ..." name="description" maxlength="200" ></textarea>
                                </div>
                            </div>
                        </div>
                <div class="form-group">
                    <input type="file" class="form-control-file" multiple name="file" id="file" style="display:none" aria-describedby="fileHelp">
                    <button  type="button"  class="btn-default"  onclick="document.getElementById('file').click()">Pasirinkite failą</button>
                    <div style ="display: inline-block;" id="file-name">
                </div>
                 <small id="fileHelp" class="form-text text-muted"> Failą pridėti nėra būtina. Leidžiami formatai: doc, docx, pdf, txt, pptx, ppsx, odt, ods, odp, tiff, jpeg, png. Failas negali būti didesnis nei 5MB.</small>
                </div>
                        <div class="text-center">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success mt-4">{{ __('Patvirtinti') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 order-xl-1">
                <div class="card bg-secondary shadow">
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
            </div>
            {{ csrf_field() }}
        </form>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript">
    // new GijgoDatePicker(document.getElementById('datepicker0'), { uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });

    new GijgoDatePicker(document.getElementById('datepicker0'), {
        change: function (e) {
            date_change(e);
        },
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
    });

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
                url:"{{ route('createeventcontroller.fetch') }}",
                method: "POST",
                data:{select:select, value:value, _token:_token, dependent:dependent},
                success:function(result){
                    $('#room_id').html('<option value="" selected disabled>Kambarys</option>')
                    var rows = $(".time").map(function() {
                        return this.id;
                    }).get();

                    for(let i = 0; i < rows.length; i++){
                        $('#' + rows[i]).html('<option value="" selected disabled>Laikas</option>');
                    }
                    $('#set-capacity').attr("max", 1);
                    $('#set-capacity').val(1);
                    $('#'+dependent).html(result);
                }
            })
        }
    })

    $('#room_id').change(function(){
        alert("room has changed");
        var room_value = $(this).val();
        var _token = $('input[name="_token').val();
        if(room_value != null){
            var room_capacity = $('#room_id').find(':selected').data('capacity');
            alert(room_capacity);
            $('.capacity').attr("max", room_capacity);
            $('.capacity').val(1);

            var date_ids = $(".my-date").map(function() {
                return this.id;
            }).get();

            var date_values = [];

            for(let i = 0; i < date_ids.length; i++){
                date_values[i] = ($('#' + date_ids[i]).val());
            }

            $.ajax({
                url: "{{ route('createeventcontroller.fetch_time') }}",
                method: "POST",
                data: {room_value: room_value, date_ids: date_ids, date_values: date_values, _token: _token},
                success: function (result) {
                    result = JSON.parse(result);
                    for (var key in result) {
                        if(result.hasOwnProperty(key)){
                            var id_count = key.substring(10, key.length);
                            $('#time' + id_count).html(result[key]);
                        }
                    }
                }
            })
        }
    })

    function date_change(event) {
        if ($(event.target).val() != '') {
            var room_value = $('#room_id').val();
            var date_value = $(event.target).val();
            var date_id = $(event.target).attr("id");
            var date_count = date_id.substring(10, date_id.length);
            if (room_value != null && date_value != '') {
                var _token = $('input[name="_token').val();
                $.ajax({
                    url: "{{ route('createeventcontroller.fetch_time') }}",
                    method: "POST",
                    data: {room_value: room_value, date_value: date_value, _token: _token},
                    success: function (result) {
                        $('#time' + date_count).html(result);
                    }
                })
            } else if (room_value != null) {
                var room_capacity = $('#room_id').find(':selected').data('capacity');
                $('#set-capacity').attr("max", room_capacity);
                $('#set-capacity').val(1);
            }
        } else {
            $('#time').html('<option value="" selected disabled>Laikas</option>');
        }
    }


    $('.capacity').on('change', function(){
        if(parseInt($('#set-capacity').val()) > parseInt($('#set-capacity').attr("max"))){
            $('#set-capacity').val($('#set-capacity').attr("max"));
        }else if($('#set-capacity').val() < $('#set-capacity').attr("min")){
            $('#set-capacity').val($('#set-capacity').attr("min"));
        }
    })

    function add_item(){
        var last_date_id = $('.last-date').attr('id');
        var last_date_count = last_date_id.substring(10, last_date_id.length);
        var new_date_id = "datepicker" + (parseInt(last_date_count) + 1);
        var new_time_id = "time" + (parseInt(last_date_count) + 1);
        var new_set_capacity_id = "set-capacity" + (parseInt(last_date_count) + 1);
        if($('#room_id').find(':selected').data('capacity')){
            var room_capacity = $('#room_id').find(':selected').data('capacity');
        }
        var new_row_id = "date-time-capacity" + (parseInt(last_date_count) + 1);
        $(".primary-input-fields").append(" <div class=\"row d-flex justify-content-start date-time-capacity\" id=\"" + new_row_id + "\">\n" +
            "                            <div class=\"col-md-4\">\n" +
            "                                <div class=\"form-group\">\n" +
            "                                    <input class=\"my-date form-group form-control input-group update-time last-date\" name=\"date\" placeholder=\"Data\" id=\"" + new_date_id + "\" />\n" +
            "                                </div>\n" +
            "                            </div>\n" +
            "                            <div class=\"col-md-4\">\n" +
            "                                <div class=\"form-group\">\n" +
            "                                    <select name=\"time\" id=\"" + new_time_id + "\" class=\"time form-control dropdown-menu-arrow\" >\n" +
            "                                        <option selected disabled>Laikas</option>\n" +
            "                                    </select>\n" +
            "                                </div>\n" +
            "                            </div>\n" +
            "                            <div class=\"col-md-4\">\n" +
            "                                <div class=\"form-group\">\n" +
            "                                    <input class=\"capacity form-control input-group\" id=\"" + new_set_capacity_id + "\" type=\"number\" min=\"1\" max=\"" + room_capacity + "\" name=\"capacity\" value=\"1\" placeholder=\"Žmonių skaičius\">\n" +
            "                                </div>\n" +
            "                            </div>\n" +
            "                        </div>");
        $('#' + last_date_id).removeClass('last-date');
        $('#' + new_date_id).addClass('last-date');

        new GijgoDatePicker(document.getElementById(new_date_id), {
            change: function (e) {
                date_change(e);
            },
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd'
        });
    }

    $("#add-item").click(add_item);

        function remove_item(){
            var rows = $(".date-time-capacity").map(function() {
                return this.id;
            }).get();

            var last_row_id = rows.pop();
            $("#" + last_row_id).remove();

            if(rows.length > 0){
                var new_last_row_id = rows[rows.length - 1].substring(18, rows[rows.length-1].length);
                var new_last_date_id = "datepicker" + new_last_row_id;

                $("#" + new_last_date_id).addClass("last-date");
            }else{
                $("#datepicker0").addClass("last-date");
            }
        }

        $("#remove-item").click(remove_item);

        $("#set-row-amount").change(function(){
            if(parseInt($('#set-row-amount').val()) > parseInt($('#set-row-amount').attr("max"))){
                $('#set-row-amount').val($('#set-row-amount').attr("max"));
            }else if($('#set-row-amount').val() < $('#set-row-amount').attr("min")){
                $('#set-row-amount').val($('#set-row-amount').attr("min"));
            }

            var rows_set = parseInt($(this).val());
            var previous_rows_set = parseInt($(this).attr("previous"));

            if(rows_set > previous_rows_set){
                let num_rows_to_add = rows_set - previous_rows_set;
                for(let i = 0; i < num_rows_to_add; i++){
                    add_item();
                }
                $(this).attr("previous", rows_set);
            }else if(rows_set < previous_rows_set){
                let num_rows_to_remove = previous_rows_set - rows_set;
                for(let i = 0; i < num_rows_to_remove; i++){
                    remove_item();
                }
                $(this).attr("previous", rows_set);
            }
        });

        $(document).ready(function() {
            $(window).keydown(function(event){
                if(event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });
        })
</script>

@endsection
