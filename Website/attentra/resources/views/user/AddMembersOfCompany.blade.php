    @extends('layouts.profile')

    @section('style')
        {{--<link  href="{{URL::to('style/profile/css/jquery-ui-1.8.14.css')}}" rel="stylesheet">--}}
        <link href="{{URL::to('style/profile/test/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
    @endsection

    @section('content')<?php
        /**
         * Created by PhpStorm.
         * User: Meysam
         * Date: 12/4/2016
         * Time: 3:04 PM
         */

        ?>
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
                <form action="{{url('company/storeAddMembers')}}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">

                    <input type="hidden" name="company_id" value="{{ Session::get('company_id') }}">
                    <input type="hidden" name="company_guid" value="{{ Session::get('company_guid')  }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label">{{trans('messages.lbl_EmployeeAvatar')}}</label>
                        <div class="col-sm-10">
                            {{--<input type="file" name="fileLogo"/>--}}
                            <span class="btn btn-default btn-file">
                                <input id="input-img" type="file" multiple class="file-loading" name="fileLogo">
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="name">@lang('messages.lbl_Name')</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" id="name"  data-validation="required" value="{{ old('name')}}" class="form-control " placeholder="{{trans('messages.lbl_Name')}}">
                        </div>
                        <span class="error">{{ $errors->first('name') }}</span><br>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="family">@lang('messages.lbl_Family')</label>
                        <div class="col-sm-10">
                            <input type="text" name="family" id="family"  data-validation="required" value="{{ old('family')}}" class="form-control " placeholder="{{trans('messages.lbl_Family')}}">
                        </div>
                        <span class="error">{{ $errors->first('family') }}</span><br>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="user_name">@lang('messages.lbl_UserName')</label>
                        <div class="col-sm-10">
                            <input type="text" name="user_name" id="user_name"  data-validation="required" value="{{ old('user_name')}}" class="form-control " placeholder="{{trans('messages.lbl_UserName')}}">
                        </div>
                        <span class="error">{{ $errors->first('user_name') }}</span><br>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="code">@lang('messages.lbl_EmployeeCode')</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" id="code"  data-validation="required" value="{{ old('code')}}" class="form-control " placeholder="{{trans('messages.lbl_EmployeeCode')}}">
                        </div>
                        <span class="error">{{ $errors->first('code') }}</span><br>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="password">@lang('messages.lbl_Password')</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" id="password"  data-validation="required" value="{{ old('password')}}" class="form-control " placeholder="{{trans('messages.lbl_Password')}}">
                        </div>
                        <span class="error">{{ $errors->first('password') }}</span><br>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="password_confirmation">@lang('messages.lbl_ConfirmPassword')</label>
                        <div class="col-sm-10">
                            <input type="password" name="password_confirmation" id="password_confirmation"  data-validation="required" value="{{ old('password_confirmation')}}" class="form-control " placeholder="{{trans('messages.lbl_ConfirmPassword')}}">
                        </div>
                        <span class="error">{{ $errors->first('password_confirmation') }}</span><br>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="email">@lang('messages.lbl_Email')</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" id="email"  data-validation="required" value="{{ old('email')}}" class="form-control " placeholder="{{trans('messages.lbl_Email')}}">
                        </div>
                    </div>

                    {{--<div class="form-group">--}}
                        {{--<label class="col-sm-2 col-sm-2 control-label" for="email">@lang('messages.lbl_Email')</label>--}}
                        {{--<div class="col-sm-10">--}}
                            {{--</p><input type="text" id="datepicker0" class="hasDatepicker">--}}
                        {{--</div>--}}
                        {{--<span class="error">{{ $errors->first('email') }}</span><br>--}}
                    {{--</div>--}}

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="user_Sex">{{trans('messages.lbl_Sex')}}</label>
                        <div class="col-sm-10">
                            <div class="radio">

                                <input type="radio" name="user_Sex" id="user_Sex" value="male" title="{{trans('messages.radioButton_employer')}}" checked="">
                                <label>
                                    {{trans('messages.lbl_Male')}}
                                </label>
                            </div>
                            <div class="radio">

                                    <input type="radio" name="user_Sex" id="user_Sex" value="female" >
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

                                    <input type="radio" name="user_type_id" id="user_type_id" value="employer" checked="">
                                <label>
                                    {{trans('messages.radioButton_employer')}}
                                </label>
                            </div>
                            <div class="radio">

                                    <input type="radio" name="user_type_id" id="user_type_id" value="midleManager" >

                                <label>
                                    {{trans('messages.radioButton_midleManager')}}
                                </label>
                            </div>

                        </div>
                    </div>





                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label"></label>
                    <div class="col-sm-10">
                        <button type="submit" class="form-control btn btn-success" id="userFormData">
                            @lang('messages.btn_AddMember')
                        </button>
                    </div>
                </div>
            </form>
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

            browseClass: "btn btn-success btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            maxFileSize: 200,

            @if (App::isLocale('pr'))
                language: "fa",
            @endif
            allowedFileExtensions: ["jpg", "png", "gif"],

            initialPreview: [
                '{{URL::to('style/profile/img/avatars/female.png')}}'
            ],
            initialPreviewAsData: true,

        });
    </script>
@stop

