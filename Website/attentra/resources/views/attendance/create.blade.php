@extends('layouts.profile')
<?php

?>
@section('style')
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/mddatetimepicker/css/jquery.Bootstrap-PersianDateTimePicker.css')}}">
@stop


@section('content')
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>افزودن حضور و غیاب</li>
        </ul>
    </div>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('message'))
        @if (in_array(session('message')->Code, \App\OperationMessage::RedMessages))
            <div class="alert alert-danger alert-dismissable fade in faa-bounce animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @else
            <div class="alert alert-success fade in faa-horizontal faa-float animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif
    <div class="form-panel">

        <form action="{{ url('attendance/store') }}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">




                {{--<div class="form-group" style="margin: 20px">--}}
            {{--<div class="input-group">--}}
            {{--<div class="input-group-addon" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#fromDate1" data-groupid="group1" data-fromdate="true" data-enabletimepicker="true" data-placement="left">--}}
            {{--<span class="glyphicon glyphicon-calendar"></span>--}}
            {{--</div>--}}
            {{--<input type="text"  name="start" class="form-control" id="fromDate1" placeholder="از تاریخ" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#fromDate1" data-groupid="group1" data-fromdate="true" data-enabletimepicker="true" data-placement="right"/>--}}
            {{--</div>--}}

            {{--<div class="input-group">--}}
            {{--<div class="input-group-addon" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#toDate1" data-groupid="group1" data-todate="true" data-enabletimepicker="true" data-placement="left">--}}
            {{--<span class="glyphicon glyphicon-calendar"></span>--}}
            {{--</div>--}}
            {{--<input type="text"  name="end" class="form-control" id="toDate1" placeholder="تا تاریخ" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#toDate1" data-groupid="group1" data-todate="true" data-enabletimepicker="true" data-placement="right"/>--}}

            {{--</div>--}}
            {{--</div>--}}
                <div class="form-group" style="direction: ltr;">
                    <div class="input-group col-lg-4 " style="margin:0px 20px 0px 20px;float:right;">
                        <div class="input-group-addon" style="background-color: #7CC576;color: white;">
                            <span class="">تاریخ شروع</span>
                        </div>
                        <input type="text"  name="start" class="form-control ALAKI12" id="fromDate1" placeholder="@Lang('messages.lbl_FromDate')"/>
                    </div>
                    <div class="input-group col-lg-4" style="margin:0px 20px 0px 20px;float:right;">
                        <div class="input-group-addon" style="background-color: #7CC576;color: white;">
                            <span class="">تاریخ پایان</span>
                        </div>

                        <input type="text"  name="end" class="form-control ALAKI13" id="toDate1" placeholder="@Lang('messages.lbl_ToDate')" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label" for="title1">نام کاربری کارمند</label>
                    <div class="input-group">
                        <div class="col-sm-10">
                            <select name="user_name">
                                @foreach($users as $user)
                                    <option value="{{$user->user_id}},{{$user->user_guid}}">{{$user->name}} {{$user->family}}({{$user->user_name}})</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="error">{{ $errors->first('name') }}</span><br>
                    </div>
                </div>

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="{{ $company_id }}">
            <input type="hidden" name="company_guid" value="{{ $company_guid }}">



            <p class="success">{{ session('message') }}</p>
            <p class="error">{{ session('error') }}</p>




            <div class="form-group">

                <div class="col-sm-12">
                    {!! Form::submit('افزودن حضور وغیاب',['class' => 'form-control btn btn-success' ]) !!}
                </div>
            </div>
        </form>

    </div>

@endsection
@section('script')
    {{--<script src="{{URL::to('style/profile/mddatetimepicker/js/jquery-3.1.0.min.js')}}" type="text/javascript"></script>--}}
    <script src="{{URL::to('style/profile/mddatetimepicker/js/jalaali.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/profile/mddatetimepicker/js/jquery.Bootstrap-PersianDateTimePicker.js')}}" type="text/javascript"></script>
    <script>

        (function($){
            $(window).on("load",function(){
                $(".ALAKI12").pDatepicker({
                    altField: '.ALAKI12',
                    altFormat: "YYYY/MM/DD HH:mm:ss",
                    format: "YYYY/MM/DD HH:mm:ss",
                    timePicker: {
                        enabled: true
                    },
                });
                $(".ALAKI13").pDatepicker({
                    altField: '.ALAKI13',
                    altFormat: "YYYY/MM/DD HH:mm:ss",
                    format: "YYYY/MM/DD HH:mm:ss",
                    timePicker: {
                        enabled: true
                    },
                });
                document.getElementById("toDate1").value = "";
                document.getElementById("fromDate1").value = "";
            });
        })(jQuery);

    </script>
@stop