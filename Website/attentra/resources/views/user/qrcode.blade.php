@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
?>
@section('style')
    {{--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">--}}

    {{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">--}}

    {{--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">--}}

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
            <div class="alert alert-danger alert-dismissable fade in someClass noPrint">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @else
            <div class="alert alert-success alert-dismissable fade in someClass noPrint">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif

    <div>
        <button type="button" class="btn btn-success setPrint" onclick="PushPrint()">@lang('messages.lbl_Print')</button>
        <span  class="showmessage someClass noPrint"></span>


                <div id="print" class="Print">

                    <div>
                        <div>
                            <div class="leftcard">
                                <?php
                                $user_companies = DB::select( DB::raw('SELECT * from user_company where deleted_at is null and user_company.company_id IN(select MIN(user_company.company_id) from user_company where user_company.user_id = :muser_id and user_company.deleted_at IS null) and user_company.user_id = :muser_id2'), array(
                                    'muser_id' => $cur_user->user_id,
                                    'muser_id2' =>  $cur_user->user_id,
                                ));
                                if(count($user_companies) != 0){
                                    $user_company_id = $user_companies[0]->user_company_id;
                                    $string = openssl_encrypt($user_company_id, "AES-128-ECB", \App\Repositories\AttendanceRepository::ENCRYPTION_PASSWORD);
                                    $png = QrCode::format('png')->size(200)->generate($string);
                                    $png = base64_encode($png);
                                    echo "<img src='data:image/png;base64," . $png . "'>";
                                }



//                                $png =QrCode::format('png')->size(200)->geo(37.822214, -122.481769);
//                                $string = openssl_encrypt ("","AES-128-ECB",\App\Repositories\AttendanceRepository::ENCRYPTION_PASSWORD,0,null);
//                                $png = QrCode::format('png')->size(200)->generate($string);
//                                $png = base64_encode($png);
//                                echo "<img src='data:image/png;base64," . $png . "'>";
                                ?>
                            </div>
                            <div class="Rightcard">
                                <div>
                                    <img src="{{ route('avatars.image', ['filename' =>$cur_user->user_guid]) }}" class="img-circle" style="margin-top:5px" height='128' width='128'/>
                                </div>
                                <div>@lang('messages.lbl_Name'):  {{$cur_user->name}}</div>
                                <div>@lang('messages.lbl_Family'):  {{$cur_user->family}}</div>
                                <?php
                                $JobPosition='';
                                if($cur_user->user_type_id=="2")$JobPosition=trans('messages.lbl_midleManager');
                                else if($cur_user->user_type_id=="3")$JobPosition=trans('messages.lbl_Employee');
                                else $JobPosition=trans('messages.lbl_Manager');
                                ?>


                                <div>@lang('messages.lbl_JobTitle'):  {{$JobPosition}}</div>

                            </div>
                        </div>
                        <div class="cardFooter" style="margin-top: 14px">
                            <div>@lang('messages.lbl_CompanyName'):  {{session('companiesName0')}}</div>
                        </div>
                    </div>

                </div>

    </div>
@endsection
@section('script')
    {{--<script src="{{URL::to('style/profile/flipflop/js/flipclock.min.js')}}" type="text/javascript"></script>--}}
    {{--<script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" type="text/javascript"></script>--}}
    {{--//code.jquery.com/jquery-1.12.4.js--}}
    {{--https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js--}}
    {{--<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" type="text/javascript"></script>--}}

    {{--<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js" type="text/javascript"></script>--}}

    {{--<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>--}}

    {{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>--}}

    {{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>--}}

    {{--<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js" type="text/javascript"></script>--}}

    {{--<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js" type="text/javascript"></script>--}}

    {{--<script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js" type="text/javascript"></script>--}}



    <script>
        function PushPrint() {
            window.print();
        }

//        $(document).ready(function() {
//            var prtContent = document.getElementById("#print");
//            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
//            WinPrint.document.write(prtContent.innerHTML);
//            WinPrint.document.close();
//            WinPrint.focus();
//            WinPrint.print();
//            WinPrint.close();
//        } );


    </script>
@stop
