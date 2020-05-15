@extends('layouts.app', ['title' => __('Kursai')])

@section('content')
    @include('layouts.headers.cards')
    <div class="container">
    @foreach ($courses as $course)
        <div class="row">
            <div class="col">
                <div class="card card-stats mt-3">
                    <div class="card-body border border-primary rounded">
                        <div class="col">
                            <h1>
                                <span class="h2 font-weight-bold mb-0 d-flex justify-content-center"><td>{{ $course->course_title }}</td></span>
                            </h1>
                        </div>

                        <div class="d-flex flex-r ml-2 mt-3 mt">
                            <div class="mb-0 text-muted text-sm ">
                                <h3>Aprašymas:</h3>
                            </div>
                            <div class="ml-3">{{ $course->description }}</div>
                        </div>
                        <div class="d-flex flex-r ml-2 mt-3 mt">
                            <div class="mb-0 text-muted text-sm ">
                                <h3>Papildoma info:</h3>
                            </div>
                            <div class="ml-3">{{ $course->comments }}</div>
                        </div>
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
@endsection
