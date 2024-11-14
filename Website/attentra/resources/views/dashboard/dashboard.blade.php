<?php
use App\Repositories\UserTypeRepository;
use Carbon\Carbon;
?>
@extends('layouts.profile')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/datatable/css/jquery.dataTables.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{URL::to('style/datatable/css/buttons.dataTables.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{URL::to('style/datatable/css/responsive.dataTables.min.css')}}">

    {{--<link rel="stylesheet" href="{{URL::to('style/datatable/css/font-awesome.min.css')}}">--}}

@stop


@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Auth::user()->user_type_id==UserTypeRepository::getCEOCode() || Auth::user()->user_type_id==UserTypeRepository::getMiddleCEOCode())
        <div class="alert alert-info alert-dismissable fade in"
             style="text-align:center;display:block;margin-top:10px;">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
            <ul>
                <li>@lang('messages.msg_list_of_your_institute')</li>
            </ul>
        </div>
        <div class="setPrint">
            @if(Auth::user()->user_type_id ==1 || Auth::user()->user_type_id ==0)
                <div class="container">
                    <div class="row">
                        <div class="col-md-7">
                            <a href="{{URL::to('company/create')}}">
                                <button type="button" class="" data-toggle="tooltip" data-placement="left"
                                        title="@lang('messages.lbl_addCompany')">
                                    <i class="fa fa-calendar-plus-o faa-horizontal animated fa-4x"
                                       style="font-size:48px;color:forestgreen" tooltip="">
                                    </i>

                                </button>
                            </a>
                        </div>
                        <div class="col-md-5">
                            @if(count($CompanyRepository)==1)
                                <a  href="company/ListMembers/{{$CompanyRepository[0]->company_id}}/{{$CompanyRepository[0]->company_guid}}"  style="position: absolute;left: 300px;" title="@lang('messages.lbl_View_Track')">

                                    <i class="fa fa-map faa-horizontal animated fa-5x"
                                       style="font-size:48px;color:#2e6da4" tooltip="">
                                    </i>
                                    <br>
                                    <span  class="fa fa-map-marker  faa-ring animated fa-2x"></span>

                                </a>
                            @else
                                <a data-target="#chooseCompany" data-toggle="modal" href="#" style="float: left"
                                   {{--                   href="'company/ListMembers/{{$Company->company_id}}/{{$Company->company_guid}}'" --}}
                                   title="@lang('messages.lbl_View_Track')">

                                    <i class="fa fa-map-marker faa-horizontal animated fa-4x"
                                       style="font-size:48px;color:#2e6da4" tooltip="">
                                    </i>
                                    <br>
                                    <span  class="fa fa-map-marker  faa-ring animated fa-2x"></span>
                                </a>
                                <div class="modal fade noPrint" id="chooseCompany"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                @lang('messages.lbl_first_choose_company')
                                            </div>
                                            <div class="modal-body" >
                                                @foreach($CompanyRepository as $Company)
                                                    <a style="float: right" href="company/ListMembers/{{$Company->company_id}}/{{$Company->company_guid}}" style="float: left"
                                                       {{--                   href="'company/ListMembers/{{$Company->company_id}}/{{$Company->company_guid}}'" --}}
                                                       title="@lang('messages.lbl_addCompany')">
                                                        {{$Company->name}}
                                                    </a>
                                                    <br>

                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.lbl_Cancel')</button>
                                                {{--<a href='company/remove/{{$Company->company_id}}/{{$Company->company_guid}}' class="btn btn-danger btn-ok">@lang('messages.lbl_Delete')</a>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <span class="showmessage"></span>

            <div class="row">
                <div class="table-responsive">
                    <table id="example1" class="display nowrap full-width" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>@lang('messages.lbl_CompanyName') <i class="fa fa-university" aria-hidden="true"></i>
                            </th>
                            <th>@lang('messages.lbl_AddMember') <i class="fa fa-user" aria-hidden="true"></i></th>
                            <th>@lang('messages.lbl_MemberList') <i class="fa fa-user" aria-hidden="true"></i></th>
                            <th>@lang('messages.lbl_AttendanceList') <span class="fa fa-check-square"></span></th>
                            <th>@lang('messages.lbl_MissionList') <span class="fa fa-list"></span></th>
                            @if(Auth::user()->user_type_id==UserTypeRepository::getCEOCode())
                                <th>@lang('messages.lbl_ٍEdit')</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($CompanyRepository as $Company)
                            <tr>
                                <td>{{$Company->name}}</td>
                                <td style="text-align: center"><a
                                            href='company/AddMembers/{{$Company->company_id}}/{{$Company->company_guid}}'
                                            data-toggle="tooltip" data-placement="left"
                                            title=" @lang('messages.lbl_AddMember')"><i class="fa fa-user-plus"
                                                                                        aria-hidden="true"
                                                                                        style="font-size: 20px"></i></a>
                                </td>
                                <td style="text-align: center"><a
                                            href='company/ListMembers/{{$Company->company_id}}/{{$Company->company_guid}}'
                                            data-toggle="tooltip" data-placement="left"
                                            title=" @lang('messages.lbl_MemberList')"><i class="fa fa-list-ol"
                                                                                         aria-hidden="true"
                                                                                         style="font-size: 20px"></i></a>
                                </td>
                                <td style="text-align: center"><a
                                            href='/attendaceList/{{$Company->company_id}}/{{$Company->company_guid}}'
                                            data-toggle="tooltip" data-placement="left"
                                            title="@lang('messages.lbl_AttendanceList')"><i class="fa fa-check-square"
                                                                                            aria-hidden="true"
                                                                                            style="font-size: 20px"></i></a>
                                </td>
                                <td style="text-align: center"><a
                                            href='/missionList/{{$Company->company_id}}/{{$Company->company_guid}}/null/null'
                                            data-toggle="tooltip" data-placement="left"
                                            title="@lang('messages.lbl_MissionList')"><i class="fa fa-list"
                                                                                         aria-hidden="true"
                                                                                         style="font-size: 20px"></i></a>
                                </td>
                                @if(Auth::user()->user_type_id==UserTypeRepository::getCEOCode())

                                    <td class="noPrint">
                                        <a class="noPrint" href='companyEdit/{{$Company->company_id}}/{{$Company->company_guid}}'>
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_ٍEdit')">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </a>
                                        <a class="noPrint" href="#" data-toggle="modal" data-target="#confirm-delete{{$Company->company_id}}">
                                            <button class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_Remove')">
                                                <i class="fa fa-trash-o "></i>
                                            </button>
                                        </a>
                                        <div class="modal fade noPrint" id="confirm-delete{{$Company->company_id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        @lang('messages.lbl_DeleteCompany')
                                                    </div>
                                                    <div class="modal-body" style="overflow-x: scroll">
                                                        @lang('messages.lbl_DescriptionForDeleteCompany')
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.lbl_Cancel')</button>
                                                        <a href='company/remove/{{$Company->company_id}}/{{$Company->company_guid}}' class="btn btn-danger btn-ok">@lang('messages.lbl_Delete')</a>
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
        </div>
    @endif

    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>@lang('messages.msg_YourIdCard')</li>
        </ul>
    </div>
    <button type="button" class="btn btn-success setPrint" onclick="PushPrint()">@lang('messages.lbl_Print')</button>
    <button data-toggle="modal" data-target="#myModal" type="button" class="btn btn-success setPrint" onclick="">
        @lang('messages.msg_help')
    </button>
    <div id="print" data-toggle="modal" data-target="#myModal" class="Print">

        <div >
            <div >
                <div class="leftcard">
                    <?php
                    //

                    $user_companies = DB::select( DB::raw('SELECT * from user_company where deleted_at is null and user_company.company_id IN(select MIN(user_company.company_id) from user_company where user_company.user_id = :muser_id and user_company.deleted_at IS null) and user_company.user_id = :muser_id2'), array(
                        'muser_id' => Auth::user()->user_id,
                        'muser_id2' => Auth::user()->user_id,
                    ));
                    if(count($user_companies) != 0){
                        $user_company_id = $user_companies[0]->user_company_id;
                        $string = openssl_encrypt($user_company_id, "AES-128-ECB", \App\Repositories\AttendanceRepository::ENCRYPTION_PASSWORD);
                        $png = QrCode::format('png')->size(200)->generate($string);
                        $png = base64_encode($png);
                        echo "<img src='data:image/png;base64," . $png . "'>";
                    }

                    ?>
                </div>
                <div class="Rightcard">
                    <div>
                        <img src="{{ route('avatars.image', ['filename' =>Auth::user()->user_guid]) }}"
                             class="img-circle" style="margin-top:5px" height="128" width="128"/>
                    </div>
                    <div>@lang('messages.lbl_Name'):  {{Auth::user()->name}}</div>
                    <div>@lang('messages.lbl_Family'):  {{Auth::user()->family}}</div>
                    <?php
                    $JobPosition = '';
                    if (Auth::user()->user_type_id == "2") $JobPosition = trans('messages.lbl_midleManager');
                    else if (Auth::user()->user_type_id == "3") $JobPosition = trans('messages.lbl_Employee');
                    else $JobPosition = trans('messages.lbl_Manager');
                    ?>


                    <div>@lang('messages.lbl_JobTitle'):  {{$JobPosition}}</div>

                </div>
            </div>

            <div class="cardFooter">
                <div style="margin-top: 14px;">@lang('messages.lbl_CompanyName'):  {{session('companiesName0')}}</div>
            </div>
        </div>




    </div>



    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div style="background-color: #7CC576" class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">راهنمای ساخت کارت شرکت</h4>
                </div>
                <div class="modal-body">
                    <p>در صورتی که کارت شما عکس ندارد می توانید در قسمت "ویرایش کاربری" در منوی سمت راست سایت عکس خود را
                        وارد کنید دقت کنید که حجم عکستان بیشتر از 200 کیلو بایت نباشد</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">بستن</button>
                </div>
            </div>

        </div>
    </div>





    @if(\Illuminate\Support\Facades\Auth::user()->user_type_id==UserTypeRepository::getCEOCode() || \Illuminate\Support\Facades\Auth::user()->user_type_id==UserTypeRepository::getMiddleCEOCode())
        <div class="alert alert-info alert-dismissable fade in setPrint"
             style="text-align:center;display:block;margin-top:10px;">
            <a href="#" class="close setPrint" data-dismiss="alert" aria-label="close">×</a>
            <ul>
                <li class="setPrint">@lang('messages.msg_list_of_last_attendance')</li>
            </ul>
        </div>
        <div class="row setPrint">
            <div class="table-responsive">
                <table id="example2" class="display nowrap full-width" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>@lang('messages.lbl_CompanyName') <i class="fa fa-university" aria-hidden="true"></i></th>
                        <th>@lang('messages.lbl_Name') <i class="fa fa-user" aria-hidden="true"></i></th>
                        <th>@lang('messages.lbl_Family') <i class="fa fa-user" aria-hidden="true"></i></th>
                        <th>@lang('messages.lbl_JobTitle') <span class="fa fa-check-square"></span></th>
                        <th>@lang('messages.lbl_FromDate') <span class="fa fa-list"></span></th>
                        <th>@lang('messages.lbl_FromTime') <span class="fa fa-list"></span></th>
                        <th>@lang('messages.lbl_ToDate') <span class="fa fa-list"></span></th>
                        <th>@lang('messages.lbl_ToTime') <span class="fa fa-list"></span></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($last_attendances_for_users as $attendance)
                        <?php
                        $day = '';
                        $converted = '';
                        $convertedTodate = '';
                        $convertedToTime = '';
                        $enddateconvertedTodate = '';
                        $enddateconvertedToTime = '';
                        $createdconvertedTodate = '';
                        $editconvertedTodate = '';
                        $JobPosition = '';
                        if ($attendance->user_type == "2") $JobPosition = trans('messages.lbl_midleManager');
                        else if ($attendance->user_type == "3") $JobPosition = trans('messages.lbl_Employee');
                        else $JobPosition = trans('messages.lbl_Manager');

                        if (App::isLocale('en')) {
                            $carbon = new Carbon($attendance->start_date_time);
                            $convertedTodate = $carbon->toDateString();
                            $convertedToTime = $carbon->toTimeString();

                            $carbon = new Carbon($attendance->end_date_time);
                            $enddateconvertedTodate = $carbon->toDateString();
                            $enddateconvertedToTime = $carbon->toTimeString();

//                            $carbon = new Carbon($attendance->created_at);
//                            $createdconvertedTodate=$carbon->toDateString();
//
//                            $carbon = new Carbon($attendance->updated_at);
//                            $editconvertedTodate=$carbon->toDateString();
                            //
                        } elseif (App::isLocale('pr')) {
                            $convertedTodate='';
                            $temp='';
                            if($attendance->start_date_time==null)
                                $convertedTodate='خالی';
                            else{
                                $temp = \Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($attendance->start_date_time));
                                $convertedTodate = \Morilog\Jalali\jDateTime::convertNumbers($temp);
                            }


                            if($attendance->start_date_time==null)
                                $convertedToTime='خالی';
                            else{
                                $temp = \Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($attendance->start_date_time));
                                $convertedToTime = \Morilog\Jalali\jDateTime::convertNumbers($temp);
                            }

                            if($attendance->end_date_time==null)
                                $enddateconvertedTodate='خالی';
                            else{
                                $temp = \Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($attendance->end_date_time));
                                $enddateconvertedTodate = \Morilog\Jalali\jDateTime::convertNumbers($temp);
                                }

                            if($attendance->end_date_time==null)
                                $enddateconvertedToTime='خالی';
                            else{
                                $temp = \Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($attendance->end_date_time));
                                 $enddateconvertedToTime = \Morilog\Jalali\jDateTime::convertNumbers($temp);
                                 }


                        }

                        ?>
                        <tr>
                            <td style="text-align: center">{{$attendance->company_name}}</td>
                            <td style="text-align: center">{{$attendance->user_first_name}}</td>
                            <td style="text-align: center">{{$attendance->user_last_name}}</td>
                            <td style="text-align: center">{{$JobPosition}}</td>
                            <td style="text-align: center">{{$convertedTodate}}</td>
                            <td style="text-align: center">{{$convertedToTime}}</td>
                            <td style="text-align: center">{{$enddateconvertedTodate}}</td>
                            <td style="text-align: center">{{$enddateconvertedToTime}}</td>

                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    @endif



@endsection
@section('script')
    <script src="{{URL::to('style/profile/flipflop/js/flipclock.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/datatable/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/datatable/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/datatable/js/buttons.flash.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/datatable/js/jszip.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/datatable/js/buttons.html5.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/datatable/js/buttons.print.min.js')}}" type="text/javascript"></script>
    <script src="{{URL::to('style/datatable/js/dataTables.responsive.min.js')}}" type="text/javascript"></script>

    <script>


        $(document).ready(function () {
            $('.yourClass').tooltip({show: {effect: "none", delay: 0}});
            $('#example1').DataTable({
                "order": [[ 5, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0]
                        },
                        text: "@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0]
                        },
                        text: "@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0]
                        },
                        text: "@lang('messages.lbl_Copy')",
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
                        "first": "@lang('messages.lbl_first')",
                        "last": "@lang('messages.lbl_last')",
                        "next": "@lang('messages.lbl_next')",
                        "previous": "@lang('messages.lbl_previous')"
                    },
                    "decimal": "@lang('messages.lbl_decimal')",
                    "emptyTable": "@lang('messages.lbl_emptyTable')",
                    "infoPostFix": "@lang('messages.lbl_infoPostFix')",
                    "thousands": "@lang('messages.lbl_thousands')",
                    "loadingRecords": "@lang('messages.lbl_loadingRecords')",
                    "processing": "@lang('messages.lbl_processing')",
                    "search": "@lang('messages.lbl_search')",
                },
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: -1 }
                ],
            });
            $('#example2').DataTable({
                "order": [[ 5, "desc" ],[ 6, "asc" ]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        },
                        text: "@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6,7]
                        },
                        text: "@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                           columns: [0,1,2,3,4,5,6,7]
                        },
                        text: "@lang('messages.lbl_Copy')",
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
                        "first": "@lang('messages.lbl_first')",
                        "last": "@lang('messages.lbl_last')",
                        "next": "@lang('messages.lbl_next')",
                        "previous": "@lang('messages.lbl_previous')"
                    },
                    "decimal": "@lang('messages.lbl_decimal')",
                    "emptyTable": "@lang('messages.lbl_emptyTable')",
                    "infoPostFix": "@lang('messages.lbl_infoPostFix')",
                    "thousands": "@lang('messages.lbl_thousands')",
                    "loadingRecords": "@lang('messages.lbl_loadingRecords')",
                    "processing": "@lang('messages.lbl_processing')",
                    "search": "@lang('messages.lbl_search')",
                },
                responsive: {
                    details: true
                },
            });



            var table = $('#example1').DataTable();

// #myInput is a <input type="text"> element
            $("input[aria-controls='example1']").on( 'keyup', function () {
                table.search( this.value ).draw();
            } );
            var table2 = $('#example2').DataTable();

// #myInput is a <input type="text"> element
            $("input[aria-controls='example2']").on( 'keyup', function () {
                table2.search( this.value ).draw();
            } );

        });


    </script>
    <script>
        function PushPrint() {
            window.print();
        }

        $(document).ready(function () {
//            var prtContent = document.getElementById("#print");
//            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
//            WinPrint.document.write(prtContent.innerHTML);
//            WinPrint.document.close();
//            WinPrint.focus();
//            WinPrint.print();
//            WinPrint.close();
        });


    </script>
@stop