<?php
/**
 * Created by PhpStorm.
 * User: Meysam
 * Date: 12/4/2016
 * Time: 3:04 PM
 */

?>
@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div>
{{--    {!! Form::open(['url' => '#','class'=>'mail-form has-validation-callback','id'=>'feedbackForm']) !!}--}}
<form class="form-horizontal" id="userForm" role="form" method="POST" action="#">
        {{ csrf_field() }}
    <div class="" id="hiddenMessageUser">

    </div>
    <img id="imgLoader" class="hidden" src="{{URL::to('style/profile/test/img/loader.gif')}}" alt="منیمنبمسنم" style="z-index: 3000;position:absolute;top:100px;margin-left: auto;
margin-right: auto;
left: 0;
right: 0;display:block;margin:0 auto;">
        <!-- Task Name -->
            <div class="form-group">

            <div class="col-md-6 col-md-offset-3">
            <input type="text" name="name" id="name"  data-validation="required" class=" col-sm-6 form-control data-validation error" placeholder="{{trans('messages.lbl_Name')}}">
            </div>
        </div>

            <div class="form-group">


                <div class="col-sm-6  col-md-offset-3">
                    <input type="text" name="family" id="family" data-validation="required" class="col-sm-offset-3 col-sm-6 form-control data-validation error" placeholder="{{trans('messages.lbl_Family')}}">
                </div>
            </div>

            <div class="form-group">
                   <div class="col-sm-6 col-md-offset-3 ">
                        <input type="text" name="user_name1" id="user_name1" data-validation="required" class="col-sm-offset-3 col-sm-6 form-control data-validation error" placeholder="{{trans('messages.lbl_UserName')}}">
                    </div>
            </div>

            <div class="form-group">
                   <div class="col-sm-6 col-md-offset-3">
                           <input type="password" name="password1" id="password1" data-validation="length" data-validation-length="min8" class="col-sm-offset-3 col-sm-6 form-control data-validation error" placeholder="رمز عبور (حداقل 8 حرف)">
                           <input type="password" name="password_confirmation" id="password_confirmation" data-validation="confirmation|length" data-validation-length="min8" class="col-sm-offset-3 col-sm-6 form-control error" placeholder="تکرار رمز عبور">
                    </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-md-offset-3">
                    <input type="text" name="email" id="email" data-validation="email" class="col-sm-offset-3 col-sm-6 form-control data-validation error" placeholder="{{trans('messages.lbl_Email')}}">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="licenseRead" name="licenseRead"><a href="{{URL::to('/license')}}">موافقت نامه کاربری</a>را مطالعه کردم و قبول دارم
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">

                {{--<div class="col-md-6 col-md-offset-3">--}}
                    {{--<div id="recaptcha2"></div>--}}
                     {{--</div>--}}
                <div class="col-sm-offset-3 col-sm-6">
                    <div id="recaptcha2"></div>
                </div>
            </div>

        <!-- Add Task Button -->
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-green" id="userFormData" name="userFormData">
                    @lang('messages.btn_AddUser')
                </button>

            </div>
        </div>
</form>
{{--    {{ Form::close() }}--}}
</div>