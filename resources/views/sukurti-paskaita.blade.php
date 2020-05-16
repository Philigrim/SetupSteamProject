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
                                        <input class=" form-group form-control input-group update-time last-date" name="date" placeholder="Data" id="datepicker0" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control dropdown-menu-arrow" name="time" id="time0" >
                                            <option selected disabled>Laikas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control input-group" id="set-capacity0" type="number" min="1" max="100" name="capacity" value="1" placeholder="Žmonių skaičius">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <button type="button" id="add-item" class="btn btn-facebook"></button>
                            <button type="button" id="remove-item" class="btn btn-danger"></button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <textarea class="form-control" rows="5" placeholder="Apie paskaitą ..." name="description" maxlength="200" ></textarea>
                                </div>
                            </div>
                        </div>
                @csrf
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
    new GijgoDatePicker(document.getElementById('datepicker0'), { uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });

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
                    $('#time').html('<option value="" selected disabled>Laikas</option>');
                    $('#set-capacity').attr("max", 1);
                    $('#set-capacity').val(1);
                    $('#'+dependent).html(result);
                }
            })
        }
    })

    $('.update-time').change(function update_time(){
        if($(this).val() != ''){
            var room_value = $('#room_id').val();
            var date_value = $('#datepicker').val();
            if(room_value != null && date_value != ''){
                var _token = $('input[name="_token').val();
                var room_capacity = $('#'+room_value).data('capacity');
                $('#set-capacity').attr("max", room_capacity);
                $.ajax({
                    url:"{{ route('createeventcontroller.fetch_time') }}",
                    method: "POST",
                    data:{room_value:room_value, date_value:date_value, _token:_token},
                    success:function(result){
                        $('#time').html(result);
                    }
                })
            }else if(room_value != null){
                var room_capacity = $('#room_id').find(':selected').data('capacity');
                $('#set-capacity').attr("max", room_capacity);
                $('#set-capacity').val(1);
            }
        }else{
            $('#time').html('<option value="" selected disabled>Laikas</option>');
        }
    })

    $('#set-capacity').change(function(){
        if(parseInt($('#set-capacity').val()) > parseInt($('#set-capacity').attr("max"))){
            $('#set-capacity').val($('#set-capacity').attr("max"));
        }else if($('#set-capacity').val() < $('#set-capacity').attr("min")){
            $('#set-capacity').val($('#set-capacity').attr("min"));
        }
    })

    $(document).ready(function(){
        $("#add-item").click(function(){
            var last_date_id = $('.last-date').attr('id');
            var last_date_count = last_date_id.substring(10, last_date_id.length);

            var new_date_id = "datepicker" + (parseInt(last_date_count) + 1);
            var new_time_id = "time" + (parseInt(last_date_count) + 1);
            var new_set_capacity_id = "set-capacity" + (parseInt(last_date_count) + 1);
            var new_row_id = "date-time-capacity" + (parseInt(last_date_count) + 1);
            $(".primary-input-fields").append(" <div class=\"row d-flex justify-content-start date-time-capacity\" id=\"" + new_row_id + "\">\n" +
                "                            <div class=\"col-md-4\">\n" +
                "                                <div class=\"form-group\">\n" +
                "                                    <input class=\" form-group form-control input-group update-time last-date\" name=\"date\" placeholder=\"Data\" id=\"" + new_date_id + "\" />\n" +
                "                                </div>\n" +
                "                            </div>\n" +
                "                            <div class=\"col-md-4\">\n" +
                "                                <div class=\"form-group\">\n" +
                "                                    <select name=\"time\" id=\"" + new_time_id + "\" class=\"form-control dropdown-menu-arrow\" >\n" +
                "                                        <option selected disabled>Laikas</option>\n" +
                "                                    </select>\n" +
                "                                </div>\n" +
                "                            </div>\n" +
                "                            <div class=\"col-md-4\">\n" +
                "                                <div class=\"form-group\">\n" +
                "                                    <input class=\"form-control input-group\" id=\"" + new_set_capacity_id + "\" type=\"number\" min=\"1\" max=\"100\" name=\"capacity\" value=\"1\" placeholder=\"Žmonių skaičius\">\n" +
                "                                </div>\n" +
                "                            </div>\n" +
                "                        </div>");
            $('#' + last_date_id).removeClass('last-date');
            $('#' + new_date_id).addClass('last-date');
            new GijgoDatePicker(document.getElementById(new_date_id), { uiLibrary: 'bootstrap4', format: 'yyyy-mm-dd' });
        });

        $("#remove-item").click(function(){
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
        });
    });
</script>

@endsection
