<!-- resources/views/aboutuses/index.blade.php -->

@extends('layouts.profile')
<?php
use Carbon\Carbon;
use App\Repositories\UserTypeRepository;
?>
@section('style')
    {{--<link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/mddatetimepicker/css/jquery.Bootstrap-PersianDateTimePicker.css')}}">--}}
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/flipflop/css/flipclock.css')}}">

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">


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
            <li>@lang('messages.lbl_MissionListForThisCompany') {{session('companiesName'.$Company_name_index)}}</li>
        </ul>
    </div>
    <!-- Bootstrap Boilerplate... -->
    @if(count($errors) > 0)
            <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>
        @endforeach
    </ul>
    @endif
    {{--@if(session('message'))--}}
        {{--@if (in_array(session('message')->Code, \App\OperationMessage::RedMessages))--}}
            {{--<div class="alert alert-danger">--}}
                {{--<ul>--}}
                    {{--<li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--@else--}}
            {{--<div style="background-color: green">--}}
                {{--<ul>--}}
                    {{--<li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>--}}
                {{--</ul>--}}
            {{--</div>--}}
        {{--@endif--}}
    {{--@endif--}}
    @if(session('message'))
        @if (in_array(session('message')->Code, \App\OperationMessage::RedMessages))
            <div class="alert alert-danger alert-dismissable fade in faa-bounce animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @else
            <div class="alert alert-success fade in faa-float animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif
        @if($second<0 && Auth::user()->user_type_id >0)
        <div class="alert alert-warning alert-dismissable fade in">@lang('messages.lbl_misiionDescExtra1')</div>
    @elseif($second===null && Auth::user()->user_type_id >0)
        <div class="alert alert-info alert-dismissable fade in">@lang('messages.lbl_misiionDescExtra2')</div>
    @elseif($second>0 && Auth::user()->user_type_id >0)
        <div class="alert alert-info alert-dismissable fade in">@lang('messages.lbl_misiionDescExtra3')</div>
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
                language: 'fa'
            });
        });
    </script>

    <div style="direction: rtl" >
        @if(Auth::user()->user_type_id ==1 &&$company_id!=0 || Auth::user()->user_type_id ==2)
            <a  href="/addMission/{{$company_id}}/{{$company_guid}}">
                <button type="button" class="" data-toggle="tooltip" data-placement="left" title="@Lang('messages.lbl_AddMission')">
                    <i class="fa fa-plus-circle faa-shake faa-tada animated" style="font-size:48px;color:forestgreen" tooltip="">
                    </i>
                </button>
            </a>
        @endif
        <div class="row" style="margin:1px">
            <div class="table-responsive">
                <table id="example" class="display nowrap full-width"  cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            @if(Auth::user()->user_type_id ==0)
                                <th>@lang('messages.lbl_CompanyName')</th>
                            @endif
                            <th>@lang('messages.lbl_Title') <i class="fa fa-user" aria-hidden="true"></i></th>
                            <th>@lang('messages.lbl_Description') <i class="fa fa-user" aria-hidden="true"></i></th>
                            <th>@lang('messages.lbl_FromDate') <i class="fa fa-calendar-plus-o" aria-hidden="true"></i></th>
                            <th>@lang('messages.lbl_ToDate') <span class="fa fa-calendar-plus-o"></span></th>
                            <th>@lang('messages.lbl_MemberList') <span class="fa fa-list-alt"></span></th>
                            @if(Auth::user()->user_type_id!= UserTypeRepository::Employee)
                               <th>@lang('messages.lbl_ٍEdit') <i class="fa fa-pencil" aria-hidden="true"></i></th>
                            @endif
                            <th>@lang('messages.lbl_CreatedAt') <span class="fa fa-calendar-plus-o"></span></th>
                            <th>@lang('messages.lbl_UpdatedAt') <span class="fa fa-calendar-plus-o"></span></th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($MissionRepositories as $Mission)
                            <tr>
                                @if(Auth::user()->user_type_id ==0)
                                    <td>{{$Mission->name}}</td>
                                @endif

                                <td  data-toggle="tooltip" data-placement="left" title="{{$Mission->title}}">{{\Illuminate\Support\Str::words($Mission->title, 5, '. . . ')}}</td>
                                <td  data-toggle="tooltip" data-placement="left" title="{{$Mission->description}}">{{\Illuminate\Support\Str::words($Mission->description, 5, '. . . ')}}</td>
                                <?php
                                    $enddate_converted_To_date='';
                                    $startdate_converted_To_date='';
                                    $createdconvertedTodate='';
                                    $editconvertedTodate='';
                                    if (App::isLocale('en')) {
                                        $carbon = new Carbon($Mission->start_date_time);
                                        $startdate_converted_To_date=$carbon->toDateString();

                                        $carbon = new Carbon($Mission->end_date_time);
                                        $enddate_converted_To_date=$carbon->toDateString();


                                        $carbon = new Carbon($Mission->created_at);
                                        $createdconvertedTodate=$carbon->toDateString();

                                        $carbon = new Carbon($Mission->updated_at);
                                        $editconvertedTodate=$carbon->toDateString();

                                    }
                                    elseif (App::isLocale('pr')){
                                        $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($Mission->start_date_time));
                                        $startdate_converted_To_date=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                                        $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($Mission->end_date_time));
                                        $enddate_converted_To_date=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                                        $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($Mission->created_at));
                                        $createdconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                                        $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($Mission->updated_at));
                                        $editconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                                    }
                                ?>
                                <td>{{$startdate_converted_To_date}}</td>
                                <td>{{$enddate_converted_To_date}}</td>
                                    <td style="text-align: center"><a href='/userListForThisMission/{{$Mission->mission_id}}/{{$Mission->mission_guid}}' data-toggle="tooltip" data-placement="left" title=" @lang('messages.lbl_MemberList')"><i class="fa fa-list-ol" aria-hidden="true" style="font-size: 20px"></i></a></td>

                                    {{--<td><a href='/userListForThisMission/{{$Mission->mission_id}}/{{$Mission->mission_guid}}'>لیست اعضا</a></td>--}}
                                @if(Auth::user()->user_type_id!= UserTypeRepository::Employee)
                                        <td>
                                            <a href='/editMission/{{$Mission->mission_id}}/{{$Mission->mission_guid}}'>
                                                <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_ٍEdit')">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </a>
                                            <a href="#" data-toggle="modal" data-target="#confirm-delete{{$Mission->mission_id}}">
                                                <button class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_Remove')">
                                                    <i class="fa fa-trash-o "></i>
                                                </button>
                                            </a>
                                            <div class="modal fade" id="confirm-delete{{$Mission->mission_id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            @lang('messages.lbl_DeleteMission')
                                                        </div>
                                                        <div class="modal-body">
                                                            @lang('messages.lbl_DescriptionForDeleteMission')
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.lbl_Cancel')</button>
                                                            <a href="/deleteMission/{{$Mission->mission_id}}/{{$Mission->mission_guid}}" class="btn btn-danger btn-ok">@lang('messages.lbl_Delete')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                @endif

                                <td>{{$createdconvertedTodate}}</td>
                                <td>{{$editconvertedTodate}}</td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>

<!-- Current Aboutuses -->
@endsection

@section('script')

    <script src="{{URL::to('style/profile/flipflop/js/flipclock.min.js')}}" type="text/javascript"></script>

    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js" type="text/javascript"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js" type="text/javascript"></script>

    <script>


        $(document).ready(function() {
            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
            $('#example').DataTable( {
                "order": [[ 2, "desc" ],[ 0, "asc" ]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,6,7 ]
                        },
                        text:"@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,6,7 ]
                        },
                        text:"@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [ 0, 1, 2,3,6,7 ]
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
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -3 }
                ],
            } );


//

            var table = $('#example').DataTable();

// #myInput is a <input type="text"> element
            $("input[aria-controls='example']").on( 'keyup', function () {
                table.search( this.value ).draw();
            } );

        } );


    </script>
@stop
