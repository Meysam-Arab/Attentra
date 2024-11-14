@extends('layouts.profile')
<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
?>
@section('style')
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/mddatetimepicker/css/jquery.Bootstrap-PersianDateTimePicker.css')}}">
@stop


@section('content')
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>&#1608;&#1740;&#1585;&#1575;&#1740;&#1588; &#1581;&#1590;&#1608;&#1585; &#1608; &#1594;&#1740;&#1575;&#1576;</li>
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

        <form action="{{ url('/attendance/update') }}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">

            <?php
            $enddate='';
            $startdate='';
            if (App::isLocale('en')) {
//                log::info("ssssssssssssssssssssen");
                $startdate = new Carbon($Attendance[0]->start_date_time);

                $enddate = new Carbon($Attendance[0]->end_date_time);
            }
            elseif (App::isLocale('pr')){

                if($Attendance[0]->end_date_time==null){
//                    log::info("ssssssssssssssssssssenpr");
                    $enddate="";
                }else{
                    $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d H:i:s', strtotime($Attendance[0]->end_date_time));
                    $enddate=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                }
                $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d H:i:s', strtotime($Attendance[0]->start_date_time));
                $startdate=\Morilog\Jalali\jDateTime::convertNumbers($temp);


            }
            ?>


            <div class="form-group" style="direction: ltr;">
                <div class="input-group col-lg-4 " style="margin:0px 20px 0px 20px;float:right;">
                    <div class="input-group-addon" style="background-color: #7CC576;color: white;">
                        <span class="">&#1578;&#1575;&#1585;&#1740;&#1582; &#1588;&#1585;&#1608;&#1593;</span>
                    </div>
                    <input type="text"  name="start" class="form-control ALAKI22" id="fromDate1" placeholder="@Lang('messages.lbl_FromDate')" value="deded"/>
                </div>
                <div class="input-group col-lg-4" style="margin:0px 20px 0px 20px;float:right;">
                    <div class="input-group-addon" style="background-color: #7CC576;color: white;">
                        <span class="">&#1578;&#1575;&#1585;&#1740;&#1582; &#1662;&#1575;&#1740;&#1575;&#1606;</span>
                    </div>
                    <input type="text"  name="end" class="form-control ALAKI13" id="toDate1" placeholder="@Lang('messages.lbl_ToDate')" value=""/>
                </div>
            </div>

            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <input type="hidden" name="attendance_id" value="{{$Attendance[0]->attendance_id}}">
            <input type="hidden" name="attendance_guid" value="{{$Attendance[0]->attendance_guid}}">

            <input type="hidden" name="company_id" value="{{$company_id}}">
            <input type="hidden" name="company_guid" value="{{$company_guid}}">




            <p class="success">{{ session('message') }}</p>
            <p class="error">{{ session('error') }}</p>




            <div class="form-group">
                <div class="col-sm-12">
                    {!! Form::submit('&#1575;&#1601;&#1586;&#1608;&#1583;&#1606; &#1581;&#1590;&#1608;&#1585; &#1608;&#1594;&#1740;&#1575;&#1576;',['class' => 'form-control btn btn-success']) !!}
                </div>
            </div>
        </form>

    </div>

@endsection
@section('script')
    <script>
        var date1="<?php echo $startdate; ?>";
        var date2="<?php echo $enddate; ?>";

        (function($){
            $(window).on("load",function(){
                $(".ALAKI22").persianDatepicker({
                    altField: '.ALAKI22',
                    observer:true,
                    format: "YYYY/MM/DD HH:mm:ss",
                    timePicker: {
                        enabled: true
                    },


                });
                $(".ALAKI13").persianDatepicker({
                    altField: '.ALAKI13',
                    altFormat: "YYYY/MM/DD HH:mm:ss",
                    observer:true,
                    format: "YYYY/MM/DD HH:mm:ss",
                    timePicker: {
                        enabled: true
                    },
                });
                document.getElementById("toDate1").value = date2;
                document.getElementById("fromDate1").value = date1;
            });
        })(jQuery);

    </script>
@stop