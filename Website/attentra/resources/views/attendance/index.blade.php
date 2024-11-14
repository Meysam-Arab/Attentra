@extends('layouts.profile')
<?php
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
?>

@section('style')
    {{--<link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/mddatetimepicker/css/jquery.Bootstrap-PersianDateTimePicker.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/flipflop/css/flipclock.css')}}">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/mddatetimepicker/css/jquery.Bootstrap-PersianDateTimePicker.css')}}">

    <style>
        .important{
            background-color: #00FF00;
        }
    </style>

@stop
@section('content')
    <?php
    $Company_name_index='';
    $count=null;
    for($index=0;$index< session('CompanyCount');$index++){
        if(session('companiesId'.$index)==$company_id){
            $Company_name_index=$index;
            break;
        }
    }
    ?>
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>@lang('messages.lbl_AttendanceListForThisCompany') {{session('companiesName'.$Company_name_index)}}</li>
        </ul>
        <ul>
            <li>در صورتی که مایل به فیلتر کردن اطلاعات هستید یک بازه زمانی مشخص کنید.</li>
        </ul>
    </div>
    @if(count($errors) > 0)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
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
            <div class="alert alert-success alert-dismissable fade in faa-float animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif
    @if($second<0 && Auth::user()->user_type_id >0)
        <div style="margin-bottom:20px;" class="alert alert-warning alert-dismissable fade in">@lang('messages.lbl_attendanceDescExtra1')</div>
    @elseif($second===null && Auth::user()->user_type_id >0)
        <div style="margin-bottom:20px;" class="alert alert-info alert-dismissable fade in">@lang('messages.lbl_attendanceDescExtra2')</div>
    @elseif($second>0 && Auth::user()->user_type_id >0)
        <div style="margin-bottom:20px;" class="alert-info alert-dismissable fade in">@lang('messages.lbl_attendanceDescExtra3')</div>
    @endif
    @if($second>0 )
        <div class="clock" style="display: table-row-group;zoom: 0.7;
    -moz-transform: scale(0.5);"></div>
    @endif


    <script type="text/javascript">
        var clock;

        $(document).ready(function() {
            // Calculate the difference in seconds between the future and current date
                    @if(true)
            var t='{{$second}}';
            console.log(t);
                    @endif
            var diff = t;

            // Instantiate a coutdown FlipClock
            clock = $('.clock').FlipClock(diff, {
                clockFace: 'DailyCounter',
                countdown: true,
                language: 'fa',
            });
        });
    </script>

    <div >
        <span class="showmessage"></span>
        @if(Auth::user()->user_type_id ==1 || Auth::user()->user_type_id ==2)
            <a  href="{{URL::to('attendance/create/'.$company_id.'/'.$company_guid)}}">
                <button type="button" class="" data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_AddNewAttandance')">
                    <i class="fa fa-calendar-plus-o" style="font-size:48px;color:forestgreen" tooltip="dfdf">
                    </i>
                </button>
            </a>
        @endif
    </div>
    <div class="row" style="margin-bottom: 30px;margin-top: 15px">
        <form action="{{ url('attendance/indexReports') }}"  method="post">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="{{ $company_id }}">
            <input type="hidden" name="company_guid" value="{{ $company_guid }}">
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
                <div class="input-group col-lg-1" style="margin:0px 20px 0px 20px;float:right;">
                    <input type="submit"  class="btn btn-success" id="clacilateWork" value="نمایش حضور های این بازه" placeholder="" />
                </div>
            </div>
        </form>

    </div>
    <div class="row" style="margin:1px">
        <div class="table-responsive" >
            <table id="example" class="display nowrap full-width"  cellspacing="0" width="100%" >
                <thead class="noprint">
                <tr>
                    @if(Auth::user()->user_type_id ==0)
                        <th>@lang('messages.lbl_CompanyName')</th>
                    @endif
                    @if(Auth::user()->user_type_id <= 2)
                        <th>@lang('messages.lbl_Name') <i class="fa fa-user" aria-hidden="true"></i></th>
                        <th>@lang('messages.lbl_Family') <i class="fa fa-user" aria-hidden="true"></i></th>
                    @endif
                    <th>@lang('messages.lbl_FromDay') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_FromDate') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_FromTime') <span class="fa fa-clock-o"></span></th>
                    <th>@lang('messages.lbl_ToDate') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_ToTime') <span class="fa fa-clock-o"></span></th>
                    <th>خود ثبت<span class="fa fa-check"></span></th>
                    @if(Auth::user()->user_type_id ==1 || Auth::user()->user_type_id ==2)
                        <th>@lang('messages.lbl_ٍEdit') <span class="fa fa-pencil noPrint "></span></th>
                    @endif
                    <th>@lang('messages.lbl_CreatedAt') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_UpdatedAt') <span class="fa fa-calendar"></span></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    @if(Auth::user()->user_type_id ==0)
                        <th>@lang('messages.lbl_CompanyName')</th>
                    @endif
                    @if(Auth::user()->user_type_id <= 2)
                        <th>@lang('messages.lbl_Name') <i class="fa fa-user" aria-hidden="true"></i></th>
                        <th>@lang('messages.lbl_Family') <i class="fa fa-user" aria-hidden="true"></i></th>
                    @endif
                    <th>@lang('messages.lbl_FromDay') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_FromDate') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_FromTime') <span class="fa fa-clock-o"></span></th></th>
                    <th>@lang('messages.lbl_ToDate') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_ToTime') <span class="fa fa-clock-o"></span></th></th>
                    <th>@lang('messages.lbl_selfRollTitle')<span class="fa fa-check"></span></th>
                    @if(Auth::user()->user_type_id ==1 || Auth::user()->user_type_id ==2)
                        <th >@lang('messages.lbl_ٍEdit') <span class="fa fa-pencil noPrint "></span></th>
                    @endif
                    <th>@lang('messages.lbl_CreatedAt') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_UpdatedAt') <span class="fa fa-calendar"></span></th>
                    {{--<th tabindex="10">&#1588;&#1605;&#1575;&#1585;&#1607;</th>--}}

                </tr>
                </tfoot>
                <tbody>
                @foreach($AttendanceRepositories as $attendance)
                    <tr>
                        @if(Auth::user()->user_type_id ==0)
                            <td>{{$attendance->cname}}</td>
                        @endif
                        @if(Auth::user()->user_type_id <= 2)
                            <td>{{$attendance->uname}}</td>
                            <td >{{$attendance->family}}</td>
                        @endif

                        <?php
                        $type=$attendance->type;
                        if($type=='1')
                                $type='بله';
                        elseif($type=='0')
                                $type='خیر';

                        $day='';
                        $converted='';
                        $convertedTodate='';
                        $convertedToTime='';
                        $enddateconvertedTodate='';
                        $enddateconvertedToTime='';
                        $createdconvertedTodate='';
                        $editconvertedTodate='';
                        $carbon = new Carbon($attendance->start_date_time);
                        switch ($carbon->dayOfWeek) {
                            case 0:
                                $day=trans('messages.lbl_Sunday');
                                break;
                            case 1:
                                $day=trans('messages.lbl_Monday');
                                break;
                            case 2:
                                $day=trans('messages.lbl_Tuesday');
                                break;
                            case 3:
                                $day=trans('messages.lbl_Wednesday');
                                break;
                            case 4:
                                $day=trans('messages.lbl_Thursday');
                                break;
                            case 5:
                                $day=trans('messages.lbl_Friday');
                                break;
                            case 6:
                                $day=trans('messages.lbl_Saturday');
                                break;
                        }
                        if (App::isLocale('en')) {
                            $carbon = new Carbon($attendance->start_date_time);
                            $convertedTodate=$carbon->toDateString();
                            $convertedToTime=$carbon->toTimeString();

                            $carbon = new Carbon($attendance->end_date_time);
                            $enddateconvertedTodate=$carbon->toDateString();
                            $enddateconvertedToTime=$carbon->toTimeString();

                            $carbon = new Carbon($attendance->created_at);
                            $createdconvertedTodate=$carbon->toDateString();

                            $carbon = new Carbon($attendance->updated_at);
                            $editconvertedTodate=$carbon->toDateString();
                            //
                        }
                        elseif (App::isLocale('pr')){
                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($attendance->start_date_time));
                            $convertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                            $temp=\Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($attendance->start_date_time));
                            $convertedToTime=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                            if($attendance->end_date_time==null){
                                $enddateconvertedTodate="--------";
                                $enddateconvertedToTime="--------";
                            }else{
                                $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($attendance->end_date_time));
                                $enddateconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                                $temp=\Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($attendance->end_date_time));
                                $enddateconvertedToTime=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                            }


                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($attendance->created_at));
                            $createdconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($attendance->updated_at));
                            $editconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                        }
                        ?>

                        <td >{{$day}}</td>
                        <td>{{$convertedTodate}}</td>
                        <td>{{$convertedToTime}}</td>
                        <td>{{$enddateconvertedTodate}}</td>
                        <td>{{$enddateconvertedToTime}}</td>
                        <td title="@lang('messages.lbl_selfRoll')" >{{$type}}</td>
                        @if(Auth::user()->user_type_id ==1 || Auth::user()->user_type_id ==2)
                            <td class="noPrint">
                                <a class="noPrint" href='/editAttendance/{{$attendance->attendance_id}}/{{$attendance->attendance_guid}}/{{$company_id}}/{{$company_guid}}'>
                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_&#1613;Edit')">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>
                                <a class="noPrint" href="#" data-toggle="modal" data-target="#confirm-delete{{$attendance->attendance_id}}">
                                    <button class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_Remove')">
                                        <i class="fa fa-trash-o "></i>
                                    </button>
                                </a>
                                <div class="modal fade noPrint" id="confirm-delete{{$attendance->attendance_id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                @lang('messages.lbl_DeleteAttendance')
                                            </div>
                                            <div class="modal-body">
                                                @lang('messages.lbl_DescriptionForDeleteAttendance')
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.lbl_Cancel')</button>
                                                <a href="/deleteAttendance/{{$attendance->attendance_id}}/{{$attendance->attendance_guid}}" class="btn btn-danger btn-ok">@lang('messages.lbl_Delete')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                        @endif
                        <td>{{$createdconvertedTodate}}</td>
                        <td>{{$editconvertedTodate}}</td>
                        {{--<td>{{$attendance->attendance_id}}</td>--}}
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
    {{----}}





@endsection
@section('script')
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


    <script src="{{URL::to('style/profile/flipflop/js/flipclock.min.js')}}" type="text/javascript"></script>

    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" type="text/javascript"></script>
    {{--//code.jquery.com/jquery-1.12.4.js--}}
    {{--https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js--}}
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js" type="text/javascript"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>

    {{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>--}}

    {{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>--}}

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js" type="text/javascript"></script>



    <script>

        $(document).ready(function() {

            $('#clacilateWork').on('click', function (e) {

                url='{{URL::to('AttendanceList/WorkHourThisUser')}}';
                var form_data = new FormData();
                var start_date=$('#fromDate1').val();
                var end_date=$('#toDate1').val();
                form_data.append("start_date", start_date);
                form_data.append("end_date", end_date);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    method: 'POST',
                    data: form_data     ,
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.success == true) {
                            alert(ids);
                        } else if (data.success == false) {
                            alert(ids,data.messages[0]);
                        }

                    },
                    error: function (data) {
                    }
                });
            });

            $('#example').DataTable( {
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -3 }
                ],
                "order": [[ 3, "desc" ],[ 4, "desc" ]],
                dom: 'Bfrtip',

                buttons: [
                    {
                        extend: 'print',

                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7 ]
                        },
                        text:"@lang('messages.lbl_Print')"
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7  ]
                        },
                        text:"@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,4,5,6,7 ]
                        },
                        text:"@lang('messages.lbl_Copy')",
                    }
                ],
                "pageLength": 25,
                "language": {
                    "lengthMenu": "@lang('messages.lbl_lengthMenu')",
                    "zeroRecords": "@lang('messages.lbl_zeroRecords')",
                    "info": "@lang('messages.lbl_info')",
                    "infoEmpty": "@lang('messages.lbl_infoEmpty')",
                    "infoFiltered": "@lang('messages.lbl_infoFiltered')",
                    "paginate": {
                        "first":      "@lang('messages.lbl_first')",
                        "last":       "@lang('messages.lbl_last')",
                        "next":       "@lang('messages.lbl_next')",
                        "previous":   "@lang('messages.lbl_previous')"
                    },
                    "decimal":        "@lang('messages.lbl_decimal')",
                    "emptyTable":     "@lang('messages.lbl_emptyTable')",
                    "infoPostFix":    "@lang('messages.lbl_infoPostFix')",
                    "thousands":      "@lang('messages.lbl_thousands')",
                    "loadingRecords": "@lang('messages.lbl_loadingRecords')",
                    "processing":     "@lang('messages.lbl_processing')",
                    "search":         "@lang('messages.lbl_search')",

                },



            });

            var table = $('#example').DataTable();
// #myInput is a <input type="text"> element
            $("input[aria-controls='example']").on( 'keyup', function () {
                table.search( this.value ).draw();
            });

        } );


    </script>
@stop
