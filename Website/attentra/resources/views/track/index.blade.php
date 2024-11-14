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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@stop
@section('content')

    @if(count($errors) > 0)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif
    @if(session('message'))
        @if (in_array(session('message')->Code, \App\OperationMessage::RedMessages))
            <div class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @else
            <div class="alert alert-success alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif

    <div >
        <span class="showmessage"></span>

    </div>
    <div class="row" style="margin:1px">
        <div class="table-responsive" >
            <table id="example" class="display nowrap full-width"  cellspacing="0" width="100%" >
                <thead class="noprint">
                    <tr>
                        @if(Auth::user()->user_type_id ==0)
                            <th>@lang('messages.lbl_CompanyName')</th>
                        @endif
                        {{--@if(Auth::user()->user_type_id <= 2)--}}
                            {{--<th>@lang('messages.lbl_Name') <i class="fa fa-user" aria-hidden="true"></i></th>--}}
                            {{--<th>@lang('messages.lbl_Family') <i class="fa fa-user" aria-hidden="true"></i></th>--}}
                        {{--@endif--}}
                        <th>@lang('messages.lbl_FromDay') <span class="fa fa-calendar"></span></th>
                        <th>@lang('messages.lbl_FromDate') <span class="fa fa-calendar"></span></th>
                        <th>@lang('messages.lbl_FromTime') <span class="fa fa-clock-o"></span></th>
                        <th>@lang('messages.lbl_Tracking') <span class="fa fa-map-marker"></span></th>
                            @if(Auth::user()->user_type_id!= UserTypeRepository::Employee)
                                <th>@lang('messages.lbl_Delete') <i class="fa fa-trash-o" aria-hidden="true"></i></th>
                            @endif
                    </tr>
                </thead>
                <tbody>
                @foreach($tracks as $track)
                    <tr>
                        @if(Auth::user()->user_type_id ==0)
                            <th>@lang('messages.lbl_CompanyName')</th>
                        @endif
                        {{--@if(Auth::user()->user_type_id <= 2)--}}
                        {{--<th>@lang('messages.lbl_Name') <i class="fa fa-user" aria-hidden="true"></i></th>--}}
                        {{--<th>@lang('messages.lbl_Family') <i class="fa fa-user" aria-hidden="true"></i></th>--}}
                        {{--@endif--}}

                            <?php


                                $Date=$track->track_group;
                                $year = substr($Date, -14,4);
                                $mounth=substr($Date, -10,2);
                                $day=substr($Date, -8,2);
                                $hours=substr($Date, -6,2);
                                $minute=substr($Date, -4,2);
                                $second=substr($Date, -2,2);
                                $date=$year.'/'.$mounth.'/'.$day;
                                $hour=$hours.':'.$minute.':'.$second;




                            $carbon = new Carbon($date);
                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($date));
                            $convertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                            $temp=\Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($hour));
                            $convertedToTime=\Morilog\Jalali\jDateTime::convertNumbers($temp);
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
                            ?>
                        <th>{{$day}} </th>
                        <th> {{$convertedTodate}}</th>
                        <th> {{$convertedToTime}}</th>
                        <td>
                            <a href='/map/{{$user_id}}/{{$user_guid}}/{{$track->track_group}}'>
                                <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_Tracking')">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </button>
                            </a>
                        </td>
                            @if(Auth::user()->user_type_id!= UserTypeRepository::Employee)
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#confirm-delete{{$track->track_group}}">
                                        <button class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_Remove')">
                                            <i class="fa fa-trash-o "></i>
                                        </button>
                                    </a>
                                    <div class="modal fade" id="confirm-delete{{$track->track_group}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    @lang('messages.lbl_DeleteTrack')
                                                </div>
                                                <div class="modal-body">
                                                    @lang('messages.lbl_DescriptionForDeleteAttendance')
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.lbl_Cancel')</button>
                                                    <a href="/deleteTrack/{{$track->track_group}}" class="btn btn-danger btn-ok">@lang('messages.lbl_Delete')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>







@endsection
@section('script')
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
            $('#example').DataTable( {
                "order": [[ 1, "desc" ],[ 2, "desc" ]],
                dom: 'Bfrtip',

                buttons: [
                    {
                        extend: 'print',
                        text:"@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        text:"@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        text:"@lang('messages.lbl_Copy')",
                    }
                ],
                "pageLength": 50,
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
                    { responsivePriority: 2, targets: -1 }
                ],
            } );


            // DataTable
            var table = $('#example').DataTable();

            // Apply the search
            table.columns().every( function () {
                var that = this;

                $( 'input', this.footer() ).on( 'keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that
                            .search( this.value )
                            .draw();
                    }
                } );
            } );

        } );


    </script>
@stop
