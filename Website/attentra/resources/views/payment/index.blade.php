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





   <div class="container">
       <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10 hidden-xs">
           <div>
               <span class="showmessage"></span>
               @if(Auth::user()->user_type_id ==1 )
                   <a data-toggle="modal" data-target="#INCREASEaMOUNT" href="#">
                       <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left"
                               title="@lang('messages.lbl_Increase_balance')">
                           <i class="fa fa-usd faa-vertical animated fa-4x" style="font-size:25px;" tooltip="dfdf">
                               <span style="font-size: 23px;font-family: 'Yekan', 'NumberYekan'">@lang('messages.lbl_Increase_balance')</span>
                           </i>
                       </button>
                   </a>
                   <div class="modal fade" id="INCREASEaMOUNT" role="dialog" >
                       <div class="modal-dialog modal-md" style="z-index:2000">
                           <div class="modal-content" >
                               <div class="modal-header" style="background-color: #7cc576;color: white">
                                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                                   <h4 class="modal-title">@lang('messages.lbl_Increase_balance')</h4>
                               </div>
                               <div class="modal-body">
                                   <form class="form-horizontal" role="form" target="_blank" method="POST" action="{{ url('/payment/store') }}">
                                       {{ csrf_field() }}

                                       <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                           <label for="email" class="col-md-2 control-label">@lang('messages.lbl_Amount')</label>

                                           <div class="col-md-10">
                                               <input id="cost" type="text" class="form-control" name="cost"  required autofocus>

                                               <input type="hidden" name="user_id" value="{{Auth::user()->user_id}}">
                                               <input type="hidden" name="user_guid" value="{{Auth::user()->user_guid}}">

                                               @if ($errors->has('user_name'))
                                                   <span class="help-block">
                                                                <strong>{{ $errors->first('user_name') }}</strong>
                                                             </span>
                                               @endif
                                           </div>
                                       </div>
                                       <input type="submit" class="btn btn-success" value="@lang('messages.lbl_Increase_balance')">

                                   </form>
                               </div>
                               <div class="modal-footer">

                               </div>
                           </div>
                       </div>
                   </div>
               @endif
           </div>
       </div>

       <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10 hidden-xs">
           <div class="alert alert-success alert-dismissable fade in">
               <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               <ul>
                   <li>@lang('messages.lbl_Your_payment_list')</li>
               </ul>
           </div>
       </div>
       <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 margin-bottom-10 hidden-xs">
           <div class="alert alert-success alert-dismissable fade in">
               <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               <ul>
                   <li>@lang('messages.lbl_Your_account_balance'){{ROUND(Auth::user()->balance)}} @lang('messages.lbl_toman')</li>
               </ul>
           </div>
       </div>
   </div>



    <div class="row" style="margin:1px">
        <div class="table-responsive" >
            <table id="example" class="display nowrap full-width"  cellspacing="0" width="100%" >
                 <thead class="noprint">
                        <tr>
                            @if(Auth::user()->user_type_id ==0)
                                <th>@lang('messages.lbl_CompanyName')</th>
                            @endif
                            <th>@lang('messages.lbl_Day') <span class="fa fa-calendar"></span></th>
                            <th>@lang('messages.lbl_Date') <span class="fa fa-calendar"></span></th>
                            <th>@lang('messages.lbl_Time') <span class="fa fa-calendar"></span></th>
                            <th>@lang('messages.lbl_Amount') <span class="fa fa-money"></span></th>
                            <th>@lang('messages.lbl_RefrenceCode') <span class="fa fa-binoculars" aria-hidden="true"></span></th>
                            <th>@lang('messages.lbl_Status') <span class="fa fa-money"></span></th>
                        </tr>
                </thead>
                <tbody>
                @foreach($payments as $payment)
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
                        $carbon = new Carbon($payment->created_at);
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
                            $carbon = new Carbon($payment->created_at);
                            $convertedTodate=$carbon->toDateString();
                            $convertedToTime=$carbon->toTimeString();

                            $carbon = new Carbon($payment->created_at);
                            $enddateconvertedTodate=$carbon->toDateString();
                            $enddateconvertedToTime=$carbon->toTimeString();

                            $carbon = new Carbon($payment->created_at);
                            $createdconvertedTodate=$carbon->toDateString();

                            $carbon = new Carbon($payment->created_at);
                            $editconvertedTodate=$carbon->toDateString();
                            //
                        }
                        elseif (App::isLocale('pr')){
                                $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($payment->created_at));
                            $convertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                            $temp=\Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($payment->created_at));
                            $convertedToTime=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($payment->created_at));
                            $enddateconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                            $temp=\Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($payment->created_at));
                            $enddateconvertedToTime=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($payment->created_at));
                            $createdconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                            $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($payment->created_at));
                            $editconvertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

                        }

                        ?>

                        <td >{{$day}}</td>
                        <td>{{$convertedTodate}}</td>
                        <td>{{$convertedToTime}}</td>
                        <td>{{ROUND($payment->amount)}} تومان </td>
                        <td>{{$payment->authority}}</td>
                        <td>{{\App\Repositories\PaymentRepository::getMessage($payment->status)}}</td>
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
                "order": [[ 1, "desc" ],[ 2, "desc" ]],
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


            var table = $('#example').DataTable();

// #myInput is a <input type="text"> element
            $("input[aria-controls='example']").on( 'keyup', function () {
                table.search( this.value ).draw();
            } );

        });


    </script>
@stop
