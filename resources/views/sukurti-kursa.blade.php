@extends('layouts.app')
@section('additional_header_content')
{{-- JQUERY --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
@endsection
@section('content')
    @include('users.partials.header', ['title' => __('Sukurti kursą'),
             'description' => __('Kursų kurimo puslapis. Sukurti kursai bus naudojami kuriant paskaitas.')])
    <div class="container-fluid mt--7 row d-flex justify-content-center">
        <div class="col-xl-6 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-12">
                            @if (session()->has('message'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session()->get('message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <h2 class="col-12 mb-0">{{ __('Informacija apie kursą') }}</h2>
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
                    <form action = "/sukurti-kursa" method="post">
                        @csrf
                        <div class="form-group{{ $errors->has('course_title') ? ' has-danger' : '' }}">
                        <div class="col-md-12  ">
                            <div class="form-group">
                                <input class="form-control" placeholder="Kurso pavadinimas" value="{{old('course_title')}}" name="course_title" >
                            </div>
                </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('course_title') ? ' has-danger' : '' }}">
                                <div class="form-group">
                                    <select class="form-control dropdown-menu-arrow dynamic" name="subject_id" id ="subject_id" data-dependent="lecturer_id">
                                        <option value="" selected disabled>{{ "Dalykai" }}</option>
                                        @foreach($subjects as $subject)
                                            <option value="{!!$subject->id !!}" @if (old('subject_id')==$subject->id) selected="selected"@endif>{!! $subject->subject !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <table onload="update_dropdown()" class="table table-sm align-items-center table-scroll" id="lecturer_id">
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('course_title') ? ' has-danger' : '' }}">
                                <div class="form-group">
                                    <textarea class="form-control" rows="5" placeholder="Apie kursą ..." name="description"  maxlength="1500">{{old('description')}}</textarea>
                                </div>
                            </div>
                                </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('course_title') ? ' has-danger' : '' }}">
                                <div class="form-group">
                                    <textarea class="form-control" rows="5" placeholder="Papildoma informacija ..." name="comments">{{old('comments')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="form-group">
                                <button type="submit"  id="priimk" class="btn btn-success mt-4">{{ __('Patvirtinti') }}</button>
                            </div>
                        </div>
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
            @include('layouts.footers.auth')
        </div>
    </div>

    <script type="text/javascript">
        $('.dynamic').change(function update_dropdown(){
            if($('#subject_id').val() != ''){
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
        $('.table-scroll').DataTable({
            "scrollY": "200px",
            "scrollCollapse": true,
        })
    </script>
@endsection
