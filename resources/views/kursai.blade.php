@extends('layouts.app', ['title' => __('Kursai')])
@section('additional_header_content')
    {{-- JQUERY --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
@endsection
@section('content')
    @include('users.partials.header', ['title' => __('Kursai'),
             'description' => __("Čia matote vedamus kursus STEAM centruose. Paspaudę ant 'Paskaitos',
             matysite pasirinkto kurso paskaitas.")])
    <div class="container">
    @foreach ($courses as $course)
        <div class="row">
            <div class="col">
                <div class="card border shadow rounded card-stats mt-3">
                    <div class="card-header text-center p-1">
                        <h1 class="p-0 m-0">{{ $course->course_title }}</h1>
                    </div>
                    <div class="card-body">
                        <div class="p-1 pl-1 pr-1 mb-1 bg-darker rounded col-1">
                            <h6 class="text-white text-center mb-0">{{ $course->subject->subject }}</h6>
                        </div>
                        <div class="">
                            <p class="">{{ $course->description }}</p>
                        </div>
                        <div class="row p-0 m-0" id="lecturers">
                            @foreach($lecturers[$course->id] as $lecturer)
                                <button class="p-1 shadow--hover mr-1 mb-1 pl-2 pr-2 bg-darker rounded border-0">
                                    <h6 class="text-white text-center mb-0">{{ $lecturer->lecturer->user->firstname }} {{ $lecturer->lecturer->user->lastname }}</h6>
                                </button>
                            @endforeach
                        </div>
                        @if(Auth::user()->isRole()=="admin" || Auth::user()->isRole()=="paskaitu_lektorius")
                        <div class="col">
                            <div class="row justify-content-center">
                                <h4>Papildoma informacija administratoriams/dėstytojams</h4>
                            </div>
                            <div class="row">
                                <p>{{ $course->comments }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="card-footer p-2 text-center">
                        <form action = "{{route('coursecontroller.index_reservations')}}" method="post">
                            @csrf
                            <div class="form-group p-0 m-0 col-2 center">
                                <button type="submit" href="#" class="form-control btn-primary btn" name="course_id" value="{{ $course->id }}">Paskaitos</button>
                            </div>
                            {{ csrf_field() }}
                        </form>
                    </div>
                </div>
            </div>
            @if(Auth::user()->isRole()=="admin")
            <div class="col-" style="position: absolute; margin-left:1140px;">
            <button class="btn btn-success mt-3" style="width: 50%">Redaguoti</button>
            <button class="btn btn-danger mt-2" style="width: 50%">Ištrinti</button>
            </div>
            @endif
        </div>
    @endforeach
        <script>
            {{--$(document).on('click', '.show_lectures', function(){--}}
            {{--    var course_id = $(this).val();--}}
            {{--    var _token = $('input[name="_token').val();--}}
            {{--    $.ajax({--}}
            {{--        url:"{{ route('coursecontroller.index_reservations') }}",--}}
            {{--        method: "POST",--}}
            {{--        data:{course_id:course_id, _token:_token},--}}
            {{--        success:function(result){--}}

            {{--        },--}}
            {{--        error:function(x, e){--}}
            {{--            alert(x);--}}
            {{--            alert(e);--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
        </script>
@endsection
