@extends('layouts.profile')
<?php
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
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>@lang('messages.lbl_PaymentList')</li>
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




    <div class="row" style="margin:1px">
        <div class="table-responsive" >
            <table id="example" class="display nowrap full-width"  cellspacing="0" width="100%" >
                <thead class="noprint">
                <tr>
                    @if(Auth::user()->user_type_id ==0)
                        <th>@lang('messages.lbl_Manager')</th>
                    @endif
                    <th>@lang('messages.lbl_FromDay') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_FromDate') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_CompanyName')<span class="fa fa-university"></span></th>
                    <th> @lang('messages.lbl_TitleOfModule')<span class="fa fa-info-circle"></span></th>
                    <th>@lang('messages.lbl_Amount') <span class="fa fa-money"></span></th>
                    <th>@lang('messages.lbl_ٍٍExpirationDateOfModule') <span class="fa fa-calendar"></span></th>
                    <th>@lang('messages.lbl_number_of_purchases')<span class="fa fa-free-code-camp"></span></th>
                </tr>

                </thead>
                <tbody>
                @foreach($company_user_module as $module)
                    <tr>
                        @if(Auth::user()->user_type_id ==0)
                            <td>{{$payment->payment_id}}</td>
                        @endif

                        <?php
                        $day='';
                        $converted='';
                        $convertedTodate='';
                        $convertedToTime='';
                        $enddateconvertedTodate='';
                        $enddateconvertedToTime='';
                        $createdconvertedTodate='';
                        $editconvertedTodate='';
                        $carbon = new Carbon($module->created_at);
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
                            $carbon = new Carbon($module->created_at);
                            $convertedTodate=$carbon->toDateString();
                            $convertedToTime=$carbon->toTimeString();
                        }
                        elseif (App::isLocale('pr')){
                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($module->created_at));
                            $convertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                        }
                            if (App::isLocale('en') && $module->end_date!=null) {
                                $carbon = new Carbon($module->end_date);
                                $enddateconvertedTodate=$carbon->toDateString();
                                $enddateconvertedToTime=$carbon->toTimeString();
                            }
                            elseif (App::isLocale('pr') && $module->end_date!=null){
                                $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($module->end_date));
                                $enddateconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                            }

                        ?>

                        <td >{{$day}}</td>
                        <td>{{$convertedTodate}}</td>
                            <td>
                                <?php $flager=false; ?>
                                @for($i=0;$i<session('CompanyCount');$i++)
                                    @if($module->company_id==session('companiesId' . $i))
                                        {{session('companiesName' . $i)}}
                                        <?php $flager=true; ?>
                                    @endif
                                @endfor
                                    @if(!$flager)
                                         @lang('messages.lbl_All_instituties_(General_Module)')
                                    @endif
                            </td>
                            <td>
                                @foreach($modules as $m)
                                    @if($m->module_id==$module->module_id)
                                        {{$m->title}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @if($module->cost==0)
                                    @lang('messages.lbl_free')
                                @else
                                    {{ROUND($module->cost)}}
                                    @lang('messages.lbl_toman') </td>
                            @endif
                            </td>
                            <td>
                                @if (App::isLocale('en'))
                                    @if($module->end_date!=null)
                                        {{$enddateconvertedTodate}}
                                    @else
                                        Always
                                    @endif

                                @elseif (App::isLocale('pr'))
                                    @if($module->end_date!=null)
                                        {{$enddateconvertedTodate}}
                                    @else
                                        همیشه
                                    @endif
                                @endif
                            </td>

                        <td>

                            @if($module->limit_count!=null){{$module->limit_count}}
                                @if($module->module_id==1 || $module->module_id==2)
                                    @lang('messages.lbl_month')
                                @elseif($module->module_id==3)
                                    @lang('messages.radioButton_employer')
                                @elseif($module->module_id==4)
                                    @lang('messages.lbl_institute')
                                @elseif($module->module_id==5)
                                    @lang('messages.lbl_point')
                                @endif
                            @else
                                -----
                            @endif
                        </td>



                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>




@endsection
@section('script')

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


        $(document).ready(function () {
            $('#example').DataTable({
                "order": [[ 1, "desc" ]],
                dom: 'Bfrtip',

                buttons: [
                    {
                        extend: 'print',
                        text: "@lang('messages.lbl_Print')",
                    },
                    {
                        extend: 'excel',
                        text: "@lang('messages.lbl_Excel')",
                    },
                    {
                        extend: 'copy',
                        text: "@lang('messages.lbl_Copy')",
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
