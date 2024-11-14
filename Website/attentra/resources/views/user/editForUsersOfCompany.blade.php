@extends('layouts.profile')
@section('style')
    <link href="{{URL::to('style/profile/test/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @if(session('message'))
        @if (in_array(session('message')->Code, \App\OperationMessage::RedMessages))
            <div class="alert alert-danger alert-dismissable fade in faa-bounce animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @else
            <div class="alert alert-success alert-dismissable fade in faa-float animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="form-panel">
        {!! Form::open(['route' => 'user.update','class'=>'form-horizontal style-form','enctype'=>'multipart/form-data',$user]) !!}
        {{--<form action=""  class="form-horizontal style-form" method="get">--}}
        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label">{{trans('messages.lbl_EmployeeAvatar')}}</label>
            <div class="col-sm-10">
                {{--<input type="file" name="fileLogo"/>--}}
                <span class="btn btn-default btn-file">
                        <input name="fileLogo" id="input-img" type="file" accept="image/*" class="file-loading" >
                    </span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label">{{trans('messages.lbl_Name')}}</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="" value="{!! $user->name !!}" name="name" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label">{{trans('messages.lbl_Family')}}</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="" value="{!! $user->family !!}" name="family" type="text">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label">{{trans('messages.lbl_UserName')}}</label>
            <div class="col-sm-10">
                <input class="form-control" id="disabledInput" placeholder="{!! $user->user_name !!}"   type="text" disabled>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label" for="code">@lang('messages.lbl_EmployeeCode')</label>
            <div class="col-sm-10">
                <input type="text" name="code" id="code"  data-validation="required" value="{!! $user->code !!}" class="form-control " placeholder="{{trans('messages.lbl_EmployeeCode')}}">
            </div>
            <span class="error">{{ $errors->first('code') }}</span><br>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label" for="password">@lang('messages.lbl_NewPassword')</label>
            <div class="col-sm-10">
                <input type="password" name="password" id="password"  data-validation="required" value="{{ old('password')}}" class="form-control " placeholder="{{trans('messages.lbl_NewPassword')}}">
            </div>
            <span class="error">{{ $errors->first('password') }}</span><br>
        </div>
        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label" for="password_confirmation">@lang('messages.lbl_NewConfirmPassword')</label>
            <div class="col-sm-10">
                <input type="password" name="password_confirmation" id="password_confirmation"  data-validation="required" value="{{ old('password_confirmation')}}" class="form-control " placeholder="{{trans('messages.lbl_NewConfirmPassword')}}">
            </div>
            <span class="error">{{ $errors->first('password_confirmation') }}</span><br>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label">{{trans('messages.lbl_Email')}}</label>
            <div class="col-sm-10">
                <input class="form-control" placeholder="" value="{!! $user->email !!}" name="email" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label" for="user_Sex">{{trans('messages.lbl_Sex')}}</label>
            <div class="col-sm-10">
                <div class="radio">

                    <input type="radio" name="user_Sex" id="user_Sex" value="male" title="{{trans('messages.radioButton_employer')}}" {{ ($user->gender==0)? "checked=\"\"":"" }}>
                    <label>
                        {{trans('messages.lbl_Male')}}
                    </label>
                </div>
                <div class="radio">

                    <input type="radio" name="user_Sex" id="user_Sex" value="female" {{ ($user->gender==1)? "checked=\"\"":"" }}>
                    <label>
                        {{trans('messages.lbl_female')}}
                    </label>
                </div>

            </div>
        </div>


            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="user_type_id">{{trans('messages.lbl_JobTitle')}}</label>
                <div class="col-sm-10">
                    <div class="radio">

                        <input type="radio" name="user_type_id" id="user_type_id" value="employer" {{ ($user->user_type_id==3)? "checked=\"\"":"" }}>
                        <label>
                            {{trans('messages.radioButton_employer')}}
                        </label>
                    </div>
                    <div class="radio">

                        <input type="radio" name="user_type_id" id="user_type_id" value="midleManager" {{ ($user->user_type_id==2)? "checked=\"\"":"" }}>

                        <label>
                            {{trans('messages.radioButton_midleManager')}}
                        </label>
                    </div>

                </div>
            </div>

        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label" for="user_type_id">{{trans('messages.lbl_changeMobile')}}</label>
            <div class="col-sm-10">
                <div class="radio">
                    <input type="checkbox" name="phone_code" id="phone_code" value="null">    {{trans('messages.radioButton_changeMobileDesc')}}
                </div>


            </div>
        </div>



        {{ Form::hidden('user_id',$user->user_id) }}
        {{ Form::hidden('user_guid',$user->user_guid) }}

        <div class="form-group">
            <label class="col-sm-2 col-sm-2 control-label"></label>
            <div class="col-sm-10">
                {!! Form::submit(trans('messages.َbtn_UpdateUser'),['class' => 'form-control btn btn-success' ]) !!}
            </div>
        </div>
        {{--</form>--}}

        {!! Form::close() !!}
    </div>

@endsection
@section('script')

    <script src="{{URL::to('style/profile/test/js/fileinput.js')}}"></script>
    @if (App::isLocale('pr'))
        <script src="{{URL::to('style/profile/test/js/locales/fa.js')}}"></script>
    @endif


    <script>
        {{--var a={{URL::to(storage_path().'/app/company')}};--}}
        $("#input-img").fileinput({

            browseClass: "btn btn-success",
            previewFileType: "image",
            browseLabel: "انتخاب عکس پروفایل",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            maxFileSize: 200,



            @if (App::isLocale('pr'))
            language: "fa",
            @endif
            allowedFileExtensions: ["jpg", "png", "gif"],

            initialPreview: [
                {{--'{{URL::to('style/profile/img/avatars/female.png')}}'--}}
                    '{{ route('avatars.image', ['filename' =>$user->user_guid]) }}'
            ],
            initialPreviewAsData: true,

        });
    </script>
@stop