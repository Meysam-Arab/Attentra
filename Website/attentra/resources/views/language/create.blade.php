@extends('layouts.profile')


@section('content')


    <div style="background-color: white">

        <form action="{{ url('language/store') }}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">





            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="title">نام زبان</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="title" placeholder=""  >
                </div>
                <span class="error">{{ $errors->first('name') }}</span><br>
            </div>

            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="lang_dir">زبان راست چین است</label>
                <div class="col-sm-10">
                    {{ Form::checkbox('lang_dir', 'yes') }}
                </div>
                <span class="error">{{ $errors->first('name') }}</span><br>
            </div>






            <p class="success">{{ session('message') }}</p>
            <p class="error">{{ session('error') }}</p>




            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    {!! Form::submit('افزودن زبان',['class' => 'form-control btn btn-theme' ]) !!}
                </div>
            </div>
        </form>

    </div>

@endsection