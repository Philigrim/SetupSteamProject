@extends('layouts.app', ['class' => 'bg-default'])

@section('content')
    @include('layouts.headers.guest')

    <div class="container mt--8 pb-5">
        <!-- Table -->
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-header bg-transparent pb-5">
                        <p class ="text-center font-weight-bold .display-4 mt-3">Registracija</p>

                    <div class="card-body px-lg-5 py-lg-5">
                        @if(count($errors))
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                        <form role="form" method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="form-group">
                            <div class="d-flex justify-content-center" >
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline1" onclick="javascript:yesnoCheck();" name="usertype" value="mokytojas" class="custom-control-input" autofocus>
                                <label class="custom-control-label" for="customRadioInline1">Mokytojas</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="customRadioInline2" onclick="javascript:yesnoCheck();" name="usertype" value ="paskaitu_lektorius" class="custom-control-input" >
                                <label class="custom-control-label" for="customRadioInline2">Paskaitų lektorius</label>
                              </div>
                            
                            </div>
                            </div>
                            <br>
                           
                            
                            <div class="form-group">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                    </div>
                                    <input class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" placeholder="{{ __('Vardas') }}" type="text" name="firstname" value="{{ old('firstname') }}"  autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="{{ __('Pavardė') }}" type="text" name="lastname" value="{{ old('lastname') }}"  autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="{{ __('El. paštas') }}" type="email" name="email" value="{{ old('email') }}" >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="{{ __('Slaptažodis') }}" type="password" name="password" >
                                </div>
                               
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="{{ __('Pakartokite slaptažodį:') }}" type="password" name="password_confirmation" >
                                </div>
                            </div>
                            <div class="form-group">
                                    <select class="form-control dropdown-menu-arrow" name="city"  >
                                        <option selected disabled>Pasirinkite miestą</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{$city->city_name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="form-group" id = "item" style="visibility:hidden" >
                                <select class="form-control dropdown-menu-arrow" name="subject" >
                                    <option selected disabled >Pasirinkite dėstomą dalyką</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{$subject->subject}}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="d-flex justify-content-center" >
                                <button type="submit" class="btn btn-primary mt-4 text-center">{{ __('Registruotis') }}</button>
                              </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
                
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

<script type="text/javascript">

    function yesnoCheck() {
        if (document.getElementById('customRadioInline2').checked) {
            document.getElementById('item').style.visibility = 'visible';
        }
        else document.getElementById('item').style.visibility = 'hidden';
    
    }
    
    </script>