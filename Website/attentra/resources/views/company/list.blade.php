@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
?>
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@stop
@section('content')
    <!-- probebly errors feedback -->
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

    <div >
        @if(Auth::user()->user_type_id ==1 || Auth::user()->user_type_id ==0)
            <a  href="{{URL::to('company/create')}}">
                <button type="button" class="" data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_addCompany')">
                    <i class="fa fa-calendar-plus-o faa-horizontal animated fa-4x" style="font-size:48px;color:forestgreen" tooltip="">
                    </i>
                </button>
            </a>
        @endif
        <span class="showmessage"></span>

            <div class="row">
                <div class="table-responsive">
                    <table id="example" class="display nowrap full-width"  cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('messages.lbl_CompanyName') <i class="fa fa-university" aria-hidden="true"></i></th>
                                <th>@lang('messages.lbl_AddMember') <i class="fa fa-user" aria-hidden="true"></i></th>
                                <th>@lang('messages.lbl_MemberList') <i class="fa fa-user" aria-hidden="true"></i></th>
                                <th>@lang('messages.lbl_AttendanceList') <span class="fa fa-check-square"></span></th>
                                <th>@lang('messages.lbl_MissionList') <span class="fa fa-list"></span></th>
                                <th>@lang('messages.lbl_ReportList') <span class="fa fa-file-text-o"></span></th>
                                <th>@lang('messages.lbl_ٍEdit')</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($CompanyRepository as $Company)
                            <tr>
                                <td>{{$Company->name}}</td>
                                <td style="text-align: center"><a href='company/AddMembers/{{$Company->company_id}}/{{$Company->company_guid}}' data-toggle="tooltip" data-placement="left" title=" @lang('messages.lbl_AddMember')"><i class="fa fa-user-plus" aria-hidden="true" style="font-size: 20px"></i></a></td>
                                <td style="text-align: center"><a href='company/ListMembers/{{$Company->company_id}}/{{$Company->company_guid}}' data-toggle="tooltip" data-placement="left" title=" @lang('messages.lbl_MemberList')"><i class="fa fa-list-ol" aria-hidden="true" style="font-size: 20px"></i></a></td>
                                <td style="text-align: center"><a href='/attendaceList/{{$Company->company_id}}/{{$Company->company_guid}}' data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_AttendanceList')"><i class="fa fa-check-square" aria-hidden="true" style="font-size: 20px"></i></a></td>
                                <td style="text-align: center"><a href='/missionList/{{$Company->company_id}}/{{$Company->company_guid}}/null/null' data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_MissionList')"><i class="fa fa-list" aria-hidden="true" style="font-size: 20px"></i></a></td>
                                <td style="text-align: center"><a href='/ReportList/{{$Company->company_id}}/{{$Company->company_guid}}/null/null' data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_ReportList')"><i class="fa fa fa-file-text-o" aria-hidden="true" style="font-size: 20px"></i></a></td>

                                <td>
                                    <a href='companyEdit/{{$Company->company_id}}/{{$Company->company_guid}}'>
                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_ٍEdit')">
                                            <i class="fa fa-pencil"></i>
                                        </button>
                                    </a>
                                    <a href='company/remove/{{$Company->company_id}}/{{$Company->company_guid}}'>
                                        <button class="btn btn-danger btn-xs"  data-toggle="tooltip" data-placement="left" title="@lang('messages.lbl_Remove')">
                                            <i class="fa fa-trash-o "></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
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
            $('.yourClass').tooltip({show: {effect:"none", delay:0}});
            $('#example').DataTable( {
                "order": [[ 5, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0]
                        },
                        text:"@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0]
                        },
                        text:"@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [0]
                        },
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
                responsive: {
                    details: true
                },
            } );

            var table = $('#example').DataTable();
// #myInput is a <input type="text"> element
            $("input[aria-controls='example']").on( 'keyup', function () {
                table.search( this.value ).draw();
            } );

        } );


    </script>
@stop
