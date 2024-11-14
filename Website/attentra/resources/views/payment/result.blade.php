@extends('layouts.paymentMaster')
@section('style')
    {{--<link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/mddatetimepicker/css/jquery.Bootstrap-PersianDateTimePicker.css')}}">--}}


    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">


@stop

@section('content')
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>اطلاعات پرداخت</li>
        </ul>
    </div>
    <div class="col-sm-8 col-sm-pull-2 col-sm-push-2">
        <button class="btn btn-success alert alert-success" onClick="window.print()">گرفتن پرینت</button>
    </div>
    <div class="col-sm-8 col-sm-pull-2 col-sm-push-2">
        <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
            <ul>
                <li>وضعیت:       {{\App\Repositories\PaymentRepository::getMessage($payment->status)}}</li>
                </br>
                <li>شماره پیگیری:{{$payment->authority}}</li>
                </br>
                <li>رسید دیجیتالی :{{$payment->payment_guid}}</li>
                </br>
                <li>تاریخ و ساعت       {{$payment->date_time}}</li>
                </br>
                <li>مبلغ:       {{round($payment->amount)}}تومان</li>
                </br>
            </ul>
        </div>
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
    <div style="margin-bottom: 400px;"></div>
@endsection

@section('script')
    {{--<script src="{{URL::to('style/profile/mddatetimepicker/js/jalaali.js')}}" type="text/javascript"></script>--}}
    {{--<script src="{{URL::to('style/profile/mddatetimepicker/js/jquery.Bootstrap-PersianDateTimePicker.js')}}" type="text/javascript"></script>--}}

    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" type="text/javascript"></script>

    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js" type="text/javascript"></script>

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
            });
        })(jQuery);

    </script>

    <script>


        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Bfrtip',


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