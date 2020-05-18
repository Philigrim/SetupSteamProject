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

{{--JQUERY Form Validation--}}
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

{{--WIN CSS--}}
    <link href="{{ asset('css/win.css') }}" rel="stylesheet" type="text/css" >
@endsection

@section('content')
    @include('users.partials.header', ['title' => __('Sukurti paskaitą')])

        <form id="form" class="mt--5 d-flex justify-content-center" enctype="multipart/form-data" action = "/sukurti-paskaita" method="post">
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
                                    <div class="form-group win-form-error">
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
                                    <div class="form-group date-group">
                                        <input class="date form-control input-group update-time last-date" data-msg-required="Pasirinkite datą" name="datepicker0" placeholder="Data" id="datepicker0" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="time form-control dropdown-menu-arrow" data-msg-required="Pasirinkite laiką" name="time0" id="time0" >
                                            <option selected disabled>Laikas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="capacity form-control input-group" id="capacity0" type="number" min="1" max="100" data-msg-required="Pasirinkite vietų skaičių" name="capacity0" value="1" placeholder="Žmonių skaičius">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <button type="button" id="add-item" class="btn btn-facebook"></button>
                            <button type="button" id="remove-item" class="btn btn-danger"></button>
                            <input id="set-row-amount" class="ignore" type="number" min="1" max="30" previous="1">
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
                                <button type="submit" class="button next btn btn-success mt-4">{{ __('Patvirtinti') }}</button>
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
        var room_value = $(this).val();
        var _token = $('input[name="_token').val();
        if(room_value != null){
            var room_capacity = $('#room_id').find(':selected').data('capacity');
            $('.capacity').attr("max", room_capacity);
            $('.capacity').val(1);

            var date_ids = $(".date").map(function() {
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
                        if(result[key] == null){
                            var id_count = key.substring(10, key.length);
                            $('#time' + id_count).html('<option value="" selected disabled>Laikas</option>');
                        }else if(result.hasOwnProperty(key)){
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
        var new_capacity_id = "capacity" + (parseInt(last_date_count) + 1);
        if($('#room_id').find(':selected').data('capacity')){
            var room_capacity = $('#room_id').find(':selected').data('capacity');
        }
        var new_row_id = "date-time-capacity" + (parseInt(last_date_count) + 1);
        $(".primary-input-fields").append("<div class=\"row d-flex justify-content-start\" id=\"" + new_row_id + "\">\n" +
            "                                <div class=\"col-md-4\">\n" +
            "                                    <div class=\"form-group date-group\">\n" +
            "                                        <input class=\"date form-control input-group update-time last-date\" data-msg-required=\"Pasirinkite datą\" name=\"" + new_date_id + "\" placeholder=\"Data\" id=\"" + new_date_id + "\" />\n" +
            "                                    </div>\n" +
            "                                </div>\n" +
            "                                <div class=\"col-md-4\">\n" +
            "                                    <div class=\"form-group\">\n" +
            "                                        <select class=\"time form-control dropdown-menu-arrow\" data-msg-required=\"Pasirinkite laiką\" name=\"" + new_time_id + "\" id=\"" + new_time_id + "\" >\n" +
            "                                            <option selected disabled>Laikas</option>\n" +
            "                                        </select>\n" +
            "                                    </div>\n" +
            "                                </div>\n" +
            "                                <div class=\"col-md-4\">\n" +
            "                                    <div class=\"form-group\">\n" +
            "                                        <input class=\"capacity form-control input-group\" id=\"" + new_capacity_id + "\" type=\"number\" min=\"1\" max=\"100\" data-msg-required=\"Pasirinkite vietų skaičių\" name=\"" + new_capacity_id + "\" value=\"1\" placeholder=\"Žmonių skaičius\">\n" +
            "                                    </div>\n" +
            "                                </div>\n" +
            "                            </div>");
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

    // only for demo purposes

    // function addMultiInputNamingRules(form, field, rules){
    //     $(form).find(field).each(function(index){
    //         $(this).attr('alt', $(this).attr('name'));
    //         $(this).attr('name', $(this).attr('name')+'-'+index);
    //         $(this).rules('add', rules);
    //     });
    // }
    //
    // function removeMultiInputNamingRules(form, field){
    //     $(form).find(field).each(function(index){
    //         $(this).attr('name', $(this).attr('alt'));
    //         $(this).removeAttr('alt');
    //     });
    // }

    // addMultiInputNamingRules('#form', 'input[name="dates[]"]', { required:true });

    $("#form").validate({
        focusInvalid: false,
        errorClass: "invalid",
        validClass: "success",
        ignore: ".ignore",
        rules: {
            name: 'required',
            course_id: 'required',
            city_id: 'required',
            steam_id: 'required',
            room_id: 'required',
            'lecturers[]': 'required',
            // 'dates[]': 'required',
            // 'times[]': 'required',
            // 'capacities[]': 'required',
            datepicker0: {
                required: true,

            },
            datepicker1: 'required',
            datepicker2: 'required',
            datepicker3: 'required',
            datepicker4: 'required',
            datepicker5: 'required',
            time0: {
                required: true,
                notDuplicate: true
            },
            time1: {
                required: true,
                notDuplicate: true
            },
            time2: {
                required: true,
                notDuplicate: true
            },
            time3: {
                required: true,
                notDuplicate: true
            },
            time4: {
                required: true,
                notDuplicate: true
            },
            time5: {
                required: true,
                notDuplicate: true
            },
            capacity0: 'required',
            capacity1: 'required',
            capacity2: 'required',
            capacity3: 'required',
            capacity4: 'required',
            capacity5: 'required',
            description: 'required'
        },
        messages: {
            name: 'Įrašykite pavadinimą',
            course_id: 'Pasirinkite kursą',
            city_id: 'Pasirinkite miestą',
            steam_id: 'Pasirinkite STEAM centrą',
            room_id: 'Pasirinkite kambarį',
            'lecturers[]': 'Pasirinkite bent vieną dėstytoją',
            // 'dates[]': 'Pasirinkite datą',
            // 'times[]': 'Pasirinkite laiką',
            // 'capacities[]': 'Pasirinkite vietų skaičių',
            datepicker0: 'Pasirinkite datą',
            datepicker1: 'Pasirinkite datą',
            datepicker2: 'Pasirinkite datą',
            datepicker3: 'Pasirinkite datą',
            datepicker4: 'Pasirinkite datą',
            datepicker5: 'Pasirinkite datą',
            time0: {
                required: "Pasirinkite laiką",
                notDuplicate: "Tokia data ir laikas jau pasirinkti"
            },
            time1: {
                required: "Pasirinkite laiką",
                notDuplicate: "Tokia data ir laikas jau pasirinkti"
            },
            time2: {
                required: "Pasirinkite laiką",
                notDuplicate: "Tokia data ir laikas jau pasirinkti"
            },
            time3: {
                required: "Pasirinkite laiką",
                notDuplicate: "Tokia data ir laikas jau pasirinkti"
            },
            time4: {
                required: "Pasirinkite laiką",
                notDuplicate: "Tokia data ir laikas jau pasirinkti"
            },
            time5: {
                required: "Pasirinkite laiką",
                notDuplicate: "Tokia data ir laikas jau pasirinkti"
            },
            capacity0: 'Pasirinkite vietų skaičių',
            capacity1: 'Pasirinkite vietų skaičių',
            capacity2: 'Pasirinkite vietų skaičių',
            capacity3: 'Pasirinkite vietų skaičių',
            capacity4: 'Pasirinkite vietų skaičių',
            capacity5: 'Pasirinkite vietų skaičių',
            description: 'Įrašykite aprašymą'
        },
        errorPlacement: function(error, element) {
            if (element.attr("name") == "datepicker0" || element.attr("name") == "datepicker1" ||
                element.attr("name") == "datepicker2" || element.attr("name") == "datepicker3" ||
                element.attr("name") == "datepicker4" || element.attr("name") == "datepicker5"){
                error.insertAfter( element.parent("div") );
            }else if(element.attr("name") == "lecturers[]"){
                error.insertBefore( element.parent("div") );
            }else{
                error.insertAfter(element);
            }

        },
        submitHandler: function(form) {
            // do other things for a valid form

            form.submit();
        },
    });

    jQuery.validator.addMethod("notDuplicate", function(value, element) {
        var zip = (a,b) => a.map((x,i) => [x,b[i]]);

        var element_id = element.id;
        var id_value = element_id.substring(4, element_id.length);

        var date_id = "datepicker" + (parseInt(id_value));

        var this_date = $('#' + date_id);

        var dates = $(".date").map(function() {
            return this.id;
        }).get();

        var times = $(".time").map(function() {
            return this.id;
        }).get();

        for(let i = 0; i < dates.length; i++){
            if(this_date.val() == $('#' + dates[i]).val() && this_date.attr("id") != dates[i] && value == $('#' + times[i]).val()){
                return false;
            }
        }

        return true;

    });

    // function check_for_duplicates(current_date) {
    //     var zip = (a,b) => a.map((x,i) => [x,b[i]]);
    //
    //     var current_date_id = current_date.attr('id');
    //     var current_date_id_count = current_date_id.substring(10, current_date_id.length);
    //
    //     var current_time_id = "time" + (parseInt(current_date_id_count));
    //
    //     var current_time = $('#' + current_time_id);
    //
    //     var dates = $(".date").map(function() {
    //         return this.id;
    //     }).get();
    //
    //     var times = $(".time").map(function() {
    //         return this.id;
    //     }).get();
    //
    //     for(let i = 0; i < dates.length; i++){
    //         if(current_date.val() == $('#' + dates[i]).val() && current_date.attr("id") != dates[i] && current_time.val() == $('#' + times[i]).val()){
    //             alert(current_date_id + " and " + date + " has same values");
    //         }
    //     }
    // }

    // $('.date').change(check_for_duplicates($(this)));
    // $(document).on('change', '.time', function(e){
    //     var time_id = $(this).attr("id");
    //     var id = time_id.substring(4, time_id.length);;
    //     var current_date = $('#datepicker' + id);
    //     check_for_duplicates(current_date);
    // });

    // $(document).on('change', 'date', check_for_duplicates());
    // $(document).on('change', 'time', check_for_duplicates());

    // removeMultiInputNamingRules('#form', 'input[alt="dates[]"]');

    // function validateTab(tab) {
    //     var valid = true;
    //     $(tab).find('input').each(function (index, elem) {
    //         var isElemValid = $("#form").validate().element(elem);
    //         if (isElemValid != null) { //this covers elements that have no validation rule
    //             valid = valid & isElemValid;
    //         }
    //     });
    //
    //     return valid;
    // }
    //
    // function moveToNextTab(currentTab) {
    //     var tabs = document.getElementsByClassName("date");
    //     //loop through tabs and validate the current one.
    //     //If valid, hide current tab and make next one visible.
    // }



    // jQuery.validator.addClassRules({
    //     lecturer: {
    //         required: true
    //     },
    //     // date: {
    //     //     required: true
    //     // },
    //     time: {
    //         required: true
    //     },
    //     capacity: {
    //         required: true
    //     }
    // });

    // $('#form').validate();

    // $.validator.messages.lecturer = 'Pasirinkite bent vieną dėstytoją';

    // $.validator.addClassRules({
    //     date: {
    //         required: true,
    //         messages: {required: "Pasirinkite datą"}
    //     },
    //     time: {
    //         required: true,
    //     },
    //     capacity:{
    //         required: true
    //     }
    // });

    // check keyup on quantity inputs to update totals field
    // $("#form").validateDelegate("input.quantity", "keyup", function(event) {
    //     var totals = 0;
    //     $("#orderitems input.quantity").each(function() {
    //         totals += +this.value;
    //     });
    //     $("#totals").attr("value", totals).valid();
    // });

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
