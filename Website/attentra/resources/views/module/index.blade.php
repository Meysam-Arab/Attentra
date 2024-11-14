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

    {{--show tittle--}}
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>@lang('messages.lbl_ModuleListForThisCompany') {{session('companiesName'.$Company_name_index)}}</li>
        </ul>
    </div>

    @if(session('message'))
        @if (in_array(session('message')->Code, \App\OperationMessage::RedMessages))
            <div class="alert alert-danger alert-dismissable fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @else
            <div class="alert alert-success fade in">
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
                    <th>@lang('messages.lbl_TitleOfModule') <span class="fa fa-info"></span></th>
                    <th>@lang('messages.lbl_Description') <span class="fa fa-info"></span></th>
                    <th>@lang('messages.lbl_Price') <i class="fa fa-money" aria-hidden="true"></i></th>
                    <th>@lang('messages.lbl_Status') <span class="fa fa-thumbs-up"></span></th>

                    @if(Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::Admin)
                        <th>@lang('messages.lbl_FromDate') <span class="fa fa-calendar"></span></th>
                    @endif
                    @if(Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::CEO)
                        <th>@lang('messages.lbl_ٍٍExpirationDate') <span class="fa fa-calendar"></span>
                        </th>
                    @endif

                    @if(Auth::user()->user_type_id ==\App\Repositories\UserTypeRepository::Admin)
                        <th>@lang('messages.lbl_ٍEdit') <span class="fa fa-pencil"></span></th>
                    @endif
                </tr>
                </thead>

                <tbody>
                @foreach($moduleRepositories as $module)
                    <?php
                    $status = '';
                    $convertedTodate = '';



                    if (Auth::user()->user_type_id ==  \App\Repositories\UserTypeRepository::CEO || Auth::user()->user_type_id == \App\Repositories\UserTypeRepository::MiddleCEO) {
                        $flag = false;
                        foreach ($company_modules as $company_module) {
                            $flag = false;
                            if ($company_module->module_id == $module->module_id) {
                                $flag = true;
                                if (App::isLocale('en')) {
                                    $carbon = new Carbon($company_module->end_date);
                                    $convertedTodate = $carbon->toDateString();
                                } elseif (App::isLocale('pr')) {
                                    $temp = \Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($company_module->end_date));
                                    $convertedTodate = \Morilog\Jalali\jDateTime::convertNumbers($temp);
                                }
                                break;
                            }

                        }
                        if (!$flag)
                            $convertedTodate = "--";

                    }

                    ?>
                    <tr>
                        <td
                            title="{{$module->title}}">{{\Illuminate\Support\Str::words($module->title, 5, '. . . ')}}</td>

                        <td
                            title="{{$module->description}}">{{\Illuminate\Support\Str::words($module->description, 10, '. . . ')}}</td>

                        <td>{{$module->price}}</td>

                        <td>
                            @if(Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::Admin)
                                @if($module->is_active==\App\Repositories\UserTypeRepository::CEO)

                                    <button type="button" class="btn btn-success btn-xs animateButton faa-float animated">
                                        <span class="label label-success fs10">@lang('messages.lbl_Active')</span>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-danger btn-xs animateButton faa-bounce animated">
                                        <span class="label label-danger fs10">@lang('messages.lbl_Disactive')</span>
                                    </button>

                                @endif
                            @elseif(Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::CEO || Auth::user()->user_type_id==\App\Repositories\UserTypeRepository::MiddleCEO)
                                <?php $flager = true;$notBuy=true; ?>
                                @foreach($company_modules as $company_module)
                                    @if($flager && $company_module->module_id==$module->module_id && strtotime($company_module->end_date) < strtotime(date("Y-m-d h:i:sa")))


                                        {{--<button type="button" class="btn btn-danger btn-xs "--}}
                                                {{--title="@lang('messages.lbl_ٍٍExpirationDate')">--}}
                                            <span class="label label-danger fs10">@lang('messages.lbl_ٍٍExpirationDate')</span>
                                        {{--</button>--}}
                                            <span class="label label-danger fs10">@lang('messages.lbl_Disactive')</span>
                                        <?php $flager = true;$notBuy=false; ?>
                                    @endif

                                    @if($flager && $company_module->module_id==$module->module_id && strtotime($company_module->end_date) > strtotime(date("Y-m-d h:i:sa")))
                                        <span class="label label-success fs10">@lang('messages.lbl_Active')</span>
                                        <?php $flager = false;$notBuy=false; ?>
                                    @endif

                                @endforeach
                                    @if($notBuy)
                                        {{--</button>--}}
                                        <span class="label label-danger fs10">@lang('messages.lbl_Disactive')</span>
                                        <?php $flager = true; ?>
                                    @endif
                                @if($flager)

                                        <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}" class="label label-info fs10">@lang('messages.lbl_Activate')</span>

                                @else
                                        <span data-toggle="modal" data-target="#activeModule{{$module->module_id}}" class="label label-info fs10">@lang('messages.lbl_To_Extend')</span>

                                @endif
                                    <div class="modal fade" id="activeModule{{$module->module_id}}"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    @lang('messages.lbl_Activate')
                                                </div>
                                                <div class="modal-body">
                                                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/activeModule') }}">
                                                        {{ csrf_field() }}

                                                        <input type="hidden" name="module_id" value="{{ $module->module_id }}">
                                                        <input type="hidden" name="module_guid" value="{{ $module->module_guid }}">
                                                        <input type="hidden" name="company_id" value="{{session('companiesId'.$Company_name_index)}}">
                                                        <input type="hidden" name="company_guid" value="{{session('companiesGuid'.$Company_name_index)}}{{ session()->forget('Company_name_index')}}">
                                                        <div class="form-group">
                                                            <label for="email" class="col-md-4 control-label"> @lang('messages.lbl_Extended_Duration') </label>

                                                            <div class="col-md-6">
                                                                <select name="time" id="timelist">
                                                                    <option value="1">@lang('messages.lbl_1mounth')</option>
                                                                    <option value="2">@lang('messages.lbl_2mounth')</option>
                                                                    <option value="3">@lang('messages.lbl_3mounth')</option>
                                                                    <option value="6">@lang('messages.lbl_6mounth')</option>
                                                                    <option value="12">@lang('messages.lbl_12mounth')</option>
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
                                                            <label for="email" class="col-md-4 control-label">@lang('messages.lbl_Calculate')  </label>

                                                            <div id="cost" class="col-md-6 cost">
                                                                ۱۰۰۰ <span >@lang('messages.lbl_toman')</span>
                                                            </div>
                                                        </div>





                                                        <div class="form-group">
                                                            <div class="col-md-8 col-md-offset-4">
                                                                <button type="submit" class="btn btn-success">
                                                                    @lang('messages.lbl_purchases')
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('messages.lbl_Cancel')</button>
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
                            <td>{{$convertedTodate}}</td>
                        @endif


                        @if(Auth::user()->user_type_id ==0)
                            <td>
                                <a href='moduleEdit/{{$module->module_id}}/{{$module->module_guid}}'>
                                    <button type="button" class="btn btn-primary btn-xs" data-toggle="tooltip"
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
        $('select').on('change', function() {
            $(".cost").text(this.value*1000+" تومان ");
        })

        $(document).ready(function () {
            $('#example').DataTable({
                dom: 'Bfrtip',
                "order": [[ 3, "desc" ]],
                buttons: [
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2,4 ]
                        },
                        text: "@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [ 0, 1, 2,4 ]
                        },
                        text: "@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: [ 0, 1, 2,4 ]
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
@stop
