@extends('layouts.profile')
<?php
use Illuminate\Support\Facades\Lang;

?>
@section('style')
    {{--<link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/mddatetimepicker/css/jquery.Bootstrap-PersianDateTimePicker.css')}}">--}}


    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">


@stop


@section('content')
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>ویرایش ماموریت</li>
        </ul>
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
    <div  class="form-panel">

        <form action="{{ url('/mission/update/') }}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">




                <div class="form-group">
                    <label class="col-sm-1  control-label" for="title">@Lang('messages.lbl_Title')</label>
                    <div class="col-sm-11">
                        <input class="form-control" type="text" name="title" placeholder="" value="{{$usersOfThisMission[0]->title }}" >
                    </div>
                    <span class="error">{{ $errors->first('title') }}</span><br>
                </div>

                <div class="form-group">
                    <label class="col-sm-1 control-label" for="description">@Lang('messages.lbl_Description')</label>
                    <div class="col-sm-11">
                        <textarea class="form-control" type="text" name="description" placeholder="" >{{$usersOfThisMission[0]->description }}</textarea>
                    </div>
                    <span class="error">{{ $errors->first('description') }}</span><br>
                </div>

                {{--<div class="form-group" style="margin: 10px">--}}
                    {{--<div class="input-group">--}}
                        {{--<div class="input-group-addon" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#fromDate1" data-groupid="group1" data-fromdate="true" data-enabletimepicker="true" data-placement="left">--}}
                            {{--<span class=""></span>--}}
                        {{--</div>--}}
                        {{--<input type="text"  name="start_date_time" class="form-control" id="fromDate1" placeholder="@Lang('messages.lbl_FromDate')" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#fromDate1" data-groupid="group1" data-fromdate="true" data-enabletimepicker="true" data-placement="right" value="{{\Morilog\Jalali\jDateTime::convertNumbers(\Morilog\Jalali\jDateTime::strftime('Y/m/d H:i:s', strtotime($usersOfThisMission[0]->start_date_time)))}}"/>--}}
                    {{--</div>--}}

                    {{--<div class="input-group">--}}
                        {{--<div class="input-group-addon" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#toDate1" data-groupid="group1" data-todate="true" data-enabletimepicker="true" data-placement="left">--}}
                            {{--<span class=""></span>--}}
                        {{--</div>--}}
                        {{--<input type="text"  name="end_date_time" class="form-control" id="toDate1" placeholder="@Lang('messages.lbl_ToDate')" data-mddatetimepicker="true" data-trigger="click" data-targetselector="#toDate1" data-groupid="group1" data-todate="true" data-enabletimepicker="true" data-placement="right"  value="{{\Morilog\Jalali\jDateTime::convertNumbers(\Morilog\Jalali\jDateTime::strftime('Y/m/d H:i:s', strtotime($usersOfThisMission[0]->end_date_time)))}}"/>--}}
                        {{--{!! trans('messages.lbl_Name') !!}--}}

                    {{--</div>--}}
                {{--</div>--}}
                <div class="form-group" style="direction: ltr;">
                    <div class="input-group col-lg-4 " style="margin:0px 20px 0px 20px;float:right;">
                        <div class="input-group-addon" style="background-color: #7CC576;color: white;">
                            <span class="">تاریخ شروع</span>
                        </div>
                        <input type="text"  name="start_date_time" class="form-control ALAKI12" id="fromDate1" placeholder="@Lang('messages.lbl_FromDate')" value="{{\Morilog\Jalali\jDateTime::convertNumbers(\Morilog\Jalali\jDateTime::strftime('Y/m/d H:i:s', strtotime(old('start_date_time'))))}}" />
                    </div>
                    <div class="input-group col-lg-4" style="margin:0px 20px 0px 20px;float:right;">
                        <div class="input-group-addon" style="background-color: #7CC576;color: white;">
                            <span class="">تاریخ پایان</span>
                        </div>
                        <input type="text"  name="end_date_time" class="form-control ALAKI13" id="toDate1" placeholder="@Lang('messages.lbl_ToDate')" value="{{\Morilog\Jalali\jDateTime::convertNumbers(\Morilog\Jalali\jDateTime::strftime('Y/m/d H:i:s', strtotime(old('end_date_time'))))}}"/>
                    </div>
                </div>
                <input type="hidden" name="mission_id" value="{{ $usersOfThisMission[0]->mission_id }}">
                <input type="hidden" name="mission_guid" value="{{ $usersOfThisMission[0]->mission_guid}}">
                <input type="hidden" name="company_id" value="{{ $company_id }}">
                <input type="hidden" name="company_guid" value="{{ $company_guid}}">
                <div class="form-group " style="margin: 30px">
                    <div class="row ">
                        <div class="table-responsive">
                            <table id="example" class="display nowrap full-width"  cellspacing="0" width="100%" >
                                <thead>
                                <tr>
                                    <th>@lang('messages.lbl_Name') <span class=""></span></th>
                                    <th>@lang('messages.lbl_Family') <span class=""></span></th>
                                    <th>@lang('messages.lbl_choosePersonFroMission') <span class=""></span></th>
                                </tr>
                                </thead>

                                <tbody >
                                @foreach($usersOfCompany as $user)
                                    <tr>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->family}}</td>
                                        <td>
                                            <?php
                                                $flag=false;
                                                foreach($usersOfThisMission as $userOfThisMission)
                                                {
                                                    if($user->user_id == $userOfThisMission->user_id){
                                                        $flag=true;break;
                                                    }
                                                }
                                            ?>
                                                @if($flag)
                                                    <input type='checkbox' name='missionperson[{{$user->user_id}}]' value='{{$user->user_id}}'  checked=''  >
                                                @else
                                                    <input type='checkbox' name='missionperson[{{$user->user_id}}]' value='{{$user->user_id}}'    >
                                                @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="_token" value="{{ csrf_token() }}">



                <p class="success">{{ session('message') }}</p>
                <p class="error">{{ session('error') }}</p>




                <div class="form-group">
                    <div class="col-sm-10">
                        <input type="submit"  value="@Lang('messages.َbtn_SaveChanges')" class="btn btn-success" >

                    </div>
                </div>



        </form>

    </div>

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