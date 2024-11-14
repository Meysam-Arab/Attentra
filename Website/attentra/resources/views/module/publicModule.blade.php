@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
use ViewComponents\Grids\Component\TableCaption;
use ViewComponents\ViewComponents\Component\Control\PaginationControl;
use ViewComponents\ViewComponents\Component\Control\FilterControl;
use ViewComponents\ViewComponents\Data\Operation\FilterOperation;
use ViewComponents\ViewComponents\Input\InputSource;
use ViewComponents\ViewComponents\Input\InputOption;
use ViewComponents\Grids\Component\CsvExport;
use ViewComponents\Grids\Component\DetailsRow;
use ViewComponents\ViewComponents\Component\Debug\SymfonyVarDump;
use ViewComponents\ViewComponents\Component\Control\PageSizeSelectControl;
use ViewComponents\ViewComponents\Component\ManagedList\ResetButton;
//use ViewComponents\Grids\Component\ColumnSortingControl;
use Carbon\Carbon;
?>

@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop
@section('content')

    {{--//show tittle--}}
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>@lang('messages.lbl_PublicModuleList')</li>
        </ul>
    </div>

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

    {{--<div class="btn-danger">asasas</div>--}}


    @if( Auth::user()->user_type_id ==0)
        <a href="{{URL::to('module/create/')}}">
            <button type="button" class="" data-toggle="tooltip" data-placement="left" title="افزودن یک ماژول جدید">
                <i class="fa fa-calendar-plus-o" style="font-size:48px;color:forestgreen">
                </i>
            </button>
        </a>
    @endif



    <div class="row" style="margin:1px">
        <div class="table-responsive">
            <table id="example" class="display nowrap full-width" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>@lang('messages.lbl_TitleOfModule') <i class="fa fa-info" aria-hidden="true"></i></th>
                    <th>@lang('messages.lbl_Description') <i class="fa fa-info" aria-hidden="true"></i></th>
                    <th>@lang('messages.lbl_Price') <i class="fa fa-money" aria-hidden="true"></i></th>
                    <th>@lang('messages.lbl_Status') <i class="fa fa-thumbs-up" aria-hidden="true"></i></th>

                    @if(Auth::user()->user_type_id==0)
                        <th>@lang('messages.lbl_FromDate') <span class="glyphicon glyphicon-calendar"></span></th>
                    @endif
                    @if(Auth::user()->user_type_id==1)
                        <th>@lang('messages.lbl_number_of_purchases') <span class="fa fa-space-shuttle"></span>
                        </th>
                    @endif
                    @if(Auth::user()->user_type_id==1)
                        <th>@lang('messages.lbl_number_of_used')<span class="fa fa-rocket"></span>
                        </th>
                    @endif
                    @if(Auth::user()->user_type_id ==0)
                        <th>@lang('messages.lbl_ٍEdit')</th>
                    @endif
                </tr>
                </thead>

                <tbody>
                @foreach($moduleRepositories as $module)

                    <tr>
                        <td
                                title="{{$module->title}}">{{\Illuminate\Support\Str::words($module->title, 5, '. . . ')}}</td>

                        <td
                                title="{{$module->description}}">{{\Illuminate\Support\Str::words($module->description, 10, '. . . ')}}
                        </td>
                        <td>
                            {{$module->price}}
                        </td>

                        <td>
                            @if(Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::Admin)
                                @if($module->is_active==\App\Repositories\UserTypeRepository::CEO)
                                    <button type="button" class="btn btn-success btn-xs animateButton">
                                        <span class="label label-success fs10">@lang('messages.lbl_Active')</span>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger btn-xs animateButton">
                                        <span class="label label-danger fs10">@lang('messages.lbl_Disactive')</span>
                                    </button>

                                @endif
                            @elseif(Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::CEO || Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::MiddleCEO)

                                    @if($module->module_id==3)
                                        @if($sums_of_module_purchase_count[0]->sum>$module_used_count[0])
                                            <span class="label label-success fs10">@lang('messages.lbl_Active')</span>
                                            <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}"
                                                  class="label label-info fs10">@lang('messages.lbl_Increase_employee')</span>
                                        @else
                                            <span class="label label-danger fs10">@lang('messages.lbl_Disactive')</span>
                                            <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}"
                                                  class="label label-info fs10">@lang('messages.lbl_Activate')</span>
                                        @endif
                                    @elseif($module->module_id==4)
                                        @if($sums_of_module_purchase_count[1]->sum>$module_used_count[1])
                                            <span class="label label-success fs10">@lang('messages.lbl_Active')</span>
                                            <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}"
                                                  class="label label-info fs10">@lang('messages.lbl_Increase_institute')</span>
                                        @else
                                            <span class="label label-danger fs10">@lang('messages.lbl_Disactive')</span>
                                            <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}"
                                                  class="label label-info fs10">@lang('messages.lbl_Activate')</span>
                                        @endif
                                    @elseif($module->module_id==5)
                                        @if($sums_of_module_purchase_count[2]->sum>$module_used_count[2])
                                            <span class="label label-success fs10">@lang('messages.lbl_Active')</span>
                                            <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}"
                                                  class="label label-info fs10">@lang('messages.lbl_Increase_point')</span>
                                        @else
                                            <span class="label label-danger fs10">@lang('messages.lbl_Disactive')</span>
                                            <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}"
                                                  class="label label-info fs10">@lang('messages.lbl_Activate')</span>
                                        @endif
                                    @endif

                                <div class="modal fade" id="activeModule{{$module->module_id}}" tabindex="-1"
                                     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                @lang('messages.lbl_Activate')
                                            </div>
                                            <div class="modal-body">
                                                <form class="form-horizontal" role="form" method="POST"
                                                      action="{{ url('/activeModule') }}">
                                                    {{ csrf_field() }}

                                                    <input type="hidden" name="module_id"
                                                           value="{{ $module->module_id }}">
                                                    <input type="hidden" name="module_guid"
                                                           value="{{ $module->module_guid }}">
                                                    <input type="hidden" name="company_id" value="null">
                                                    <input type="hidden" name="company_guid" value="null">
                                                    <input type="hidden" id="price{{ $module->module_id }}" name="price"
                                                           value="{{ $module->price }}">
                                                    <div class="form-group">
                                                        <label for="email" class="col-md-4 control-label">
                                                            @if($module->module_id==3)
                                                                @lang('messages.lbl_Increase_employee')
                                                            @elseif($module->module_id==4)
                                                                @lang('messages.lbl_Increase_institute')
                                                            @elseif($module->module_id==5)
                                                                @lang('messages.lbl_Increase_point')
                                                            @endif
                                                        </label>

                                                        <div class="col-md-6">
                                                            <select name="time" id="timelist{{ $module->module_id }}">
                                                                @if($module->module_id==3)
                                                                    <option value="1"> @lang('messages.lbl_1new_employee')</option>
                                                                    <option value="2"> @lang('messages.lbl_2new_employee')</option>
                                                                    <option value="5">@lang('messages.lbl_5new_employee')</option>
                                                                    <option value="10"> @lang('messages.lbl_10new_employee')</option>
                                                                    <option value="30"> @lang('messages.lbl_30new_employee')</option>
                                                                @elseif($module->module_id==4)
                                                                    <option value="1">@lang('messages.lbl_1new_institute')</option>
                                                                    <option value="2">@lang('messages.lbl_2new_institute')</option>
                                                                    <option value="3">@lang('messages.lbl_3new_institute')</option>
                                                                    <option value="4">@lang('messages.lbl_4new_institute')</option>
                                                                @elseif($module->module_id==5)
                                                                    <option value="500">@lang('messages.lbl_500new_point')</option>
                                                                    <option value="1000">@lang('messages.lbl_1000new_point')</option>
                                                                    <option value="2000">@lang('messages.lbl_2000new_point')</option>
                                                                    <option value="5000">@lang('messages.lbl_5000new_point')</option>
                                                                    <option value="10000">@lang('messages.lbl_10000new_point')</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="form-group" style="">
                                                        <label class="col-md-4 control-label">
                                                            @lang('messages.lbl_Description')
                                                        </label>
                                                        <p class="col-md-8 control-label" >{{trim($module->description)}}
                                                        </p>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" class="col-md-4 control-label">@lang('messages.lbl_Calculate')
                                                             </label>

                                                        <div id="cost{{ $module->module_id }}" class="col-md-6 cost">
                                                            {{ $module->price }}<span>@lang('messages.lbl_toman')</span>
                                                        </div>

                                                    </div>


                                                    <div class="form-group">
                                                        <div class="col-md-8 col-md-offset-4">
                                                            <button type="submit" class="button btn btn-success">
                                                                  @lang('messages.lbl_purchases')
                                                            </button>

                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">@lang('messages.lbl_Cancel')</button>
                                                {{--<a href="/deleteAttendance//" class="btn btn-danger btn-ok">@lang('messages.lbl_Delete')</a>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </td>

                        @if(Auth::user()->user_type_id==0)
                            <td>{{$module->limit_value}}</td>
                        @endif

                        @if(Auth::user()->user_type_id==1)
                            <td>
                                    @if($module->module_id==3)
                                        {{$sums_of_module_purchase_count[0]->sum}}@lang('messages.lbl_Employee')
                                    @elseif($module->module_id==4)
                                        {{$sums_of_module_purchase_count[1]->sum}}@lang('messages.lbl_institute')
                                    @elseif($module->module_id==5)
                                        {{$sums_of_module_purchase_count[2]->sum}}  @lang('messages.lbl_point')
                                    @endif
                            </td>
                        @endif
                        @if(Auth::user()->user_type_id==1)
                            <td>
                                @if($module->module_id==3)
                                    {{$module_used_count[0]}} @lang('messages.lbl_Employee')
                                @elseif($module->module_id==4)
                                    {{$module_used_count[1]}} @lang('messages.lbl_institute')
                                @elseif($module->module_id==5)
                                    {{$module_used_count[2]}}  @lang('messages.lbl_point')
                                @endif
                            </td>
                        @endif

                        @if(Auth::user()->user_type_id ==0)
                            <td>
                                <a href='moduleEdit/{{$module->module_id}}/{{$module->module_guid}}'>
                                    <button type="button" class="button btn btn-primary btn-xs" data-toggle="tooltip"
                                            data-placement="left" title="@lang('messages.lbl_ٍEdit')">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </a>
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

    <script src="{{URL::to('style/profile/mddatetimepicker/js/jalaali.js')}}" type="text/javascript"></script>

    <script src="{{URL::to('style/profile/mddatetimepicker/js/jquery.Bootstrap-PersianDateTimePicker.js')}}"
            type="text/javascript"></script>

    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js" type="text/javascript"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js" type="text/javascript"></script>

    <script>


        $(document).ready(function () {
            $('#example').DataTable({
                "order": [[ 3, "desc" ]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2,4,5 ]
                        },
                        text: "@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2,4,5 ]
                        },
                        text: "@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [ 0, 1, 2,4,5 ]
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


            // DataTable
            var table = $('#example').DataTable();

// #myInput is a <input type="text"> element
            $("input[aria-controls='example']").on( 'keyup', function () {
                table.search( this.value ).draw();
            } );

        });


    </script>
    <script>
        $(document).ready(function () {
            $('select').on('change', function () {
                var myId = this.id;
                var res = myId.slice(8);
                $(".cost").text(this.value * document.getElementById("price" + res).value + " تومان ");
            })
        });
    </script>

@stop
