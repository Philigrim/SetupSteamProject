@extends('layouts.app', ['class' => 'bg-default'])

@section('additional_header_content')

{{--Gijgo--}}
{{--Date pickeris--}}
<script src="/gijgo/dist/modular/js/core.js" type="text/javascript"></script>
<link href="/gijgo/dist/modular/css/core.css" rel="stylesheet" type="text/css">
<link href="/gijgo/dist/modular/css/datepicker.css" rel="stylesheet" type="text/css">
<script src="/gijgo/dist/modular/js/datepicker.js"></script>

{{--Drop downas--}}
<link href="/gijgo/dist/modular/css/dropdown.css" rel="stylesheet" type="text/css">
<script src="/gijgo/dist/modular/js/dropdown.js"></script>

{{--Nedulio skriptai--}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

@endsection

@section('content')
        <div class="header bg-gradient-primary py-7 py-lg-8">
        @if (session('status'))
        <div class="d-flex justify-content-center">
        <div class="alert alert-success alert-dismissible fade show" style="width:75%;" role="alert">
            {{ session('status') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        </div>
        @endif
        <table class="d-flex justify-content-center">
            <tr>
                <form action = "/insertion/subject" method="post">
                {{ csrf_field() }}
                <td><p class="text-xl text-white font-weight-bold mr-5 mt-3 mb-3">Pridėti mokomąjį dalyką:</p></td>
                <td><input class="form-control" placeholder="Dalyko pavadinimas" name="nameForSubject" required></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><button type="submit" class="btn btn-success ml-5">{{ __('Patvirtinti') }}</button></td>
                </form>
            </tr>
            <tr class="border-top">
                <form action = "/insertion/city" method="post">
                {{ csrf_field() }}
                <td><p class="text-xl text-white font-weight-bold mr-5 mt-3 mb-3">Pridėti miestą:</p></td>
                <td><input class="form-control" placeholder="Miesto pavadinimas" name="nameForCity" required></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><button type="submit" class="btn btn-success ml-5">{{ __('Patvirtinti') }}</button></td>
                </form>
            </tr>
            <tr class="border-top">
                <form action = "/insertion/steam-center" method="post">
                {{ csrf_field() }}
                <td><p class="text-xl text-white font-weight-bold mr-5 mt-3 mb-3">Pridėti centrą:</p></td>
                <td><input class="form-control" placeholder="Centro pavadinimas" name="nameForCenter" required></td>
                <td><input class="form-control" placeholder="Centro adresas" name="addressForCenter" required></td>
                <td>
                    <select class="form-control dropdown-menu-arrow" name="cityIdForCenter" required>
                    <option value="" selected disabled>Miestas</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id}}">{{ $city->city_name }}</option>
                    @endforeach
                    </select>
                </td>
                <td></td>
                <td></td>
                <td><button type="submit" class="btn btn-success ml-5">{{ __('Patvirtinti') }}</button></td>
                </form>
            </tr>
            <tr class="border-top">
                <form action = "/insertion/room" method="post">
                {{ csrf_field() }}
                <td><p class="text-xl text-white font-weight-bold mr-5 mt-3">Pridėti kambarį:</p></td>
                <td><input class="form-control" placeholder="Kambario numeris" name="nameForRoom" required></td>
                <td><input class="form-control" placeholder="Vietų skaičius" type="number" name="seatsForRoom" required></td>
                <td>
                    <select class="form-control dropdown-menu-arrow dynamic" name="city_id" id ="city_id" data-dependent="steam_id" required>
                    <option value="" selected disabled>Miestas</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id}}">{{ $city->city_name }}</option>
                    @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control dropdown-menu-arrow" name="steam_id" id="steam_id" required>
                        <option value="" selected disabled>STEAM centras</option>
                    </select>
                </td>
                <td>
                    <select class="form-control dropdown-menu-arrow" name="purposeForRoom" required>
                        <option value="" selected disabled>Kambario paskirtis</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id}}">{{ $subject->subject }}</option>
                        @endforeach
                    </select>
                </td>
                <td><button type="submit" class="btn btn-success ml-5">{{ __('Patvirtinti') }}</button></td>
                </form>
            </tr>
            <tr class="border-top" style="vertical-align: top;">
                <form action = "/insertion/equipment" method="post">
                {{ csrf_field() }}
                <td><p class="text-xl text-white font-weight-bold mr-5 mt-3">Pridėti inventorių:</p></td>
                <td><input class="form-control mt-3" placeholder="Inventoriaus pavadinimas" name="nameForInventory" required></td>
                <td><input class="form-control mt-3" placeholder="Vienetų skaičius" type="number" name="quantityOfInventory" required></td>
                <td>
                    <select class="form-control dropdown-menu-arrow dynamic-ccr mt-3" name="city_id" id ="city_id" data-dependent="steam_id2" required>
                    <option value="" selected disabled>Miestas</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id}}">{{ $city->city_name }}</option>
                    @endforeach
                    </select>
                </td>
                <td>
                    <select class="form-control dropdown-menu-arrow dynamic-ccr mt-3" name="steam_id2" id="steam_id2" data-dependent="room_id2" required>
                        <option value="" selected disabled>STEAM centras</option>
                    </select>
                </td>
                <td>
                    <div class="col p-0 p-0">
                    <select class="form-control dropdown-menu-arrow mt-3" name="room_id2" id="room_id2">
                        <option value="0" selected>Kambarys*</option>
                    </select>
                    <p style="color: white">* laukelis neprivalomas</p>
                    <div>
                </td>
                <td><button type="submit" class="btn btn-success ml-5 mt-3">{{ __('Patvirtinti') }}</button></td>
                </form> 
            </tr>

        </table>
        
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>


    <script type="text/javascript">
        $('.dynamic').change(function update_dropdown(){
            if($(this).val() != ''){
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = $('input[name="_token').val();
                $.ajax({
                    url:"{{ route('iterpimas.fetchForRoom') }}",
                    method: "POST",
                    data:{select:select, value:value, _token:_token, dependent:dependent},
                    success:function(result){
                        $('#'+dependent).html(result);
                    }
                })
            }
        })
                
        $('.dynamic-ccr').change(function update_multi_dropdown(){
            if($(this).val() != ''){
                var select = $(this).attr("id");
                var value = $(this).val();
                var dependent = $(this).data('dependent');
                var _token = $('input[name="_token').val();
                $.ajax({
                    url:"{{ route('iterpimas.fetchForInventory') }}",
                    method: "POST",
                    data:{select:select, value:value, _token:_token, dependent:dependent},
                    success:function(result){
                        $('#room_id2').html('<option value="0" selected>Kambarys*</option>');
                        $('#'+dependent).html(result);
                    }
                })
            }
        })
    </script>
    

@endsection
