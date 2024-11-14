<?php

$menu = new \App\Menu(Auth::user()->user_type_id, true);

?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <!-- Bootstrap core CSS -->

    <meta name="description" content="The HTML5 Herald">
    <meta name="author" content="SitePoint">

    <link rel="icon" href="{!! asset('ic_launcher.png') !!}"/>

    {{--<link href="{{URL::to('style/profile/test/css/bootstrap.min.css')}}" rel="stylesheet">--}}
    <link href="{{URL::to('style/profile/test/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{URL::to('style/profile/test/css/style.css')}}" rel="stylesheet">

    <link href="{{URL::to('style/profile/test/css/jquery.mCustomScrollbar.min.css')}}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    {{--<link rel="stylesheet" type="text/css" href="{{URL::to('style/font-awesome/font-awesome.min.css')}}">--}}

    <link rel="stylesheet" type="text/css" media="print" href="{{URL::to('style/profile/test/css/print.css')}}">

    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>--}}
    <script src="{{URL::to('style/profile/test/js/jquery.js')}}"></script>

    @yield('style')

    @if (App::isLocale('pr'))
        <link href="{{URL::to('style/profile/test/css/rtl.css')}}" rel="stylesheet">
    @endif

    {{--<style>--}}
        {{--.body_content{height:900px;}--}}
    {{--</style>--}}
    <style>
        html, body{ height: 100%; }
    </style>
    <link rel="stylesheet" href="{{URL::to('style/persianCalender/persian-datepicker-0.4.5.min.css')}}" />


</head>

<body class="body_content" style="background-color:#f2f2f2" >


<section id="container">
    <!-- **********************************************************************************************************************************************************
    TOP BAR CONTENT & NOTIFICATIONS
    *********************************************************************************************************************************************************** -->
    <!--header start-->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">

            </div>
            <div class="sidebar-toggle-box toggleTopSign"  onclick="openNav()">
                <div class="fa fa-bars tooltips" data-placement="left" data-original-title="Toggle Navigation"></div>
            </div>
            <div class="btn-group toggleTopLang" >
                <button type="button" class="btn btn-primary">{{ config()->get('languages')[app()->getLocale()] }}</button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    @foreach (config()->get('languages') as $lang => $language)
                        @if ($lang != app()->getLocale())
                            <li>
                                <a href="{{ route('lang.switch', $lang) }}">{{$language}}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <a class="toggleTopExit" id="exit" href="{{ url('/logout') }}" class=""><button type="button" class="btn btn-danger">@lang('messages.lbl_ٍExit')</button></a>

            <span id="hello" class="label label-primary hi">@lang('messages.lbl_Hello') ,{{(Auth::user()->name == null || Auth::user()->family== null)?trans('messages.msg_HelloUser'):(Auth::user()->name." ".Auth::user()->family)}}</span>
        </div>
    </nav>


    <!-- **********************************************************************************************************************************************************
    MAIN SIDEBAR MENU
    *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <div id="mySidenav" class="sidenav sidenavScroll mCustomScrollbar _mCS_1 mCS-autoHide someClass noPrint" >
        <ul class="sidebar-menu" id="nav-accordion" style="padding-top: 10px;">

            <p class="centered">
                    <img src="{{ route('avatars.image', ['filename' =>Auth::user()->user_guid]) }}" class="img-circle" width="100" height="100" style="margin-top:10px;"/>
                    {{--<img src="{{File::get(storage_path('app\\avatars\\58bffdeb49af09.31354384.png'))}}" class="img-circle" width="60" height="60">--}}
                    {{--<img src="{{ route('company.image', ['filename' =>$logoPath]) }}" class="img-circle"  style="width:60px;"/>--}}

            </p>

            <li class="mt">
                <a href="{{ url('/') }}">
                    <i class="fa fa-home"></i>
                    <span>@lang('messages.lbl_mainPage')</span>
                </a>
            </li>
            <li class="mt">
                <a href="{{ url('/index') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>@lang('messages.lbl_Dashboard')</span>
                </a>
            </li>

            @foreach($menu->links as $link)
                <li class="sub-menu">
                    <a href="{{URL::to($link->rout)}}">
                        <i class="fa fa-book"></i>
                        <span>{{$link->title}}</span>
                    </a>
                    @if(count($link->sub_links)>0)
                        <ul class="sub">
                            @foreach($link->sub_links as $subLink)
                                <li><a href="{{URL::to($subLink->rout)}}">{{$subLink->title}}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach

        </ul>
    </div>

    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <div class="container">
        <section id="main-content">
            <section class="wrapper site-min-height" >

                <div class="row">
                    <div id="main" >
                        <div class="col-md-9" style="margin-top: 70px;">
                            @yield('content')
                        </div>

                        <div class="col-md-3 bestMisAtt someClass noPrint" style="padding-top: 20px;">
                            <div class="form-group">
                                <div class="alert alert-success alert-dismissable fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <ul>
                                        <li>لیست بهترین ها با هر بار لاگین بروز میشود</li>
                                    </ul>
                                </div>
                                <div class="col-sm-10">
                                    <div id="map"></div>
                                </div>
                            </div>
                            <!--COMPLETED ACTIONS DONUTS CHART-->
                            <h3 data-toggle="tooltip" data-placement="left"
                                title="@lang('messages.lbl_MissionsDecsNavbarSide')">@lang('messages.lbl_BestMissioner')</h3>
                            @if(session('top5missionCount')==0)
                                <div class="desc">
                                    <div class="thumb" >
                                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                    <div class="details">
                                        <p>
                                            @lang('messages.lbl_NothingMission')
                                        </p>
                                    </div>
                                </div>
                            @endif
                        <!-- First Action -->
                            @for($index=0;$index<session('top5missionCount');$index++)
                                <div class="desc someClass noPrint" title="@lang('messages.lbl_MissionsDecsNavbarSide')">
                                    <div class="thumb">
                                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                    <div class="details">
                                        <p>{{session('top5missionName'.$index)}} {{session('top5missionFamily'.$index)}}
                                            : <br/>
                                        </p>
                                        <p>
                                        <h4>@lang('messages.lbl_With') {{session('top5missionCount'.$index)}} @lang('messages.lbl_Mission')</h4>
                                        </p>
                                    </div>
                                </div>
                            @endfor


                        <!-- USERS ONLINE SECTION -->
                            <h3 title="@lang('messages.lbl_AttendanceDecsNavbarSide')">@lang('messages.lbl_BestAttendancer')</h3>
                            <!-- First Member -->
                            @if(session('top5attendaceCount')==0)
                                <div class="desc someClass noPrint">
                                    <div class="thumb">
                                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                    <div class="details">
                                        <p>
                                        <h4>
                                            @lang('messages.lbl_NothingAttendance')</h4>
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @for($index=0;$index<session('top5attendaceCount');$index++)
                                <div class="desc" title="@lang('messages.lbl_AttendanceDecsNavbarSide')">
                                    <div class="thumb">
                                        <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                                    </div>
                                    <div class="details">
                                        <p>تماما {{session('top5attendaceName'.$index)}} {{session('top5attendaceFamily'.$index)}}
                                            : <br/>
                                        </p>
                                        <p>
                                        <h4>@lang('messages.lbl_With') {{round(session('top5attendaceCount'.$index)/3600)}} @lang('messages.lbl_HoursOfWork')</h4>
                                        </p>
                                    </div>
                                </div>
                            @endfor


                        <!-- CALENDAR-->
                            <div class="ALAKI11 col-md-12"   style="direction: ltr"></div>
                            <div id="calendar" class="mb">
                                <div class="panel green-panel no-margin">
                                    <div class="panel-body">
                                        <div id="date-popover" class="popover top"
                                             style="cursor: pointer; disadding: block; margin-right: 33%; margin-top: -50px; width: 175px;">

                                        </div>
                                        <div id="my-calendar"></div>
                                    </div>
                                </div>
                            </div><!-- / calendar -->
                            <div style="margin: 20px auto;background-color: red;justify-items:center">
                                <div class="col-md-3">

                                </div>
                                <div class="col-md-6">
                                    <script src="https://www.zarinpal.com/webservice/TrustCode" type="text/javascript"></script>
                                </div>
                                <div class="col-md-3">

                                </div>

                            </div>
                            <div    style="margin: 50px auto"></div>
                        </div>

                    </div>
                </div>
                <! --/row -->
            </section>
        </section>

    </div>




    <script src="{{URL::to('style/profile/test/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::to('style/profile/test/js/jquery.mCustomScrollbar.concat.min.js')}}"></script>


    <script>


        if ($(window).width() < 768) {
            $( "#hello" ).hide();
            $( "#exit" ).hide();
            @if (App::isLocale('pr'))
                        document.getElementById("mySidenav").style.width = "0px";
            document.getElementById("main").style.marginRight = "0px";
            @else
                document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
                    @endif
            var flag = false;
            function openNav() {

                if (!flag) {
                    @if (App::isLocale('pr'))
                        document.getElementById("mySidenav").style.width =window.innerWidth+"px";
                    document.getElementById("main").style.marginRight = window.innerWidth+"px";
                    @else
                        document.getElementById("mySidenav").style.width = window.innerWidth+"px";
                    document.getElementById("main").style.marginLeft = window.innerWidth+"px";
                    @endif
                        flag = true;
                } else {
                    @if (App::isLocale('pr'))
                        document.getElementById("mySidenav").style.width = "0px";
                        document.getElementById("main").style.marginRight = "0px";
                    @else
                        document.getElementById("mySidenav").style.width = "0";
                    document.getElementById("main").style.marginLeft = "0";
                    @endif

                        flag = false;
                }
            }
        }
        else  {
            var flag = true;
            function openNav() {

                if (!flag) {
                    @if (App::isLocale('pr'))
                        document.getElementById("mySidenav").style.width = "210px";
                    document.getElementById("main").style.marginRight = "210px";
                    @else
                        document.getElementById("mySidenav").style.width = "210px";
                    document.getElementById("main").style.marginLeft = "210px";
                    @endif
                        flag = true;
                } else {
                    @if (App::isLocale('pr'))
                        document.getElementById("mySidenav").style.width = "0px";
                    document.getElementById("main").style.marginRight = "0px";
                    @else
                        document.getElementById("mySidenav").style.width = "0";
                    document.getElementById("main").style.marginLeft = "0";
                    @endif

                        flag = false;
                }
            }
        }



        $(document).ready(function () {


            $("li.sub-menu>ul.sub").slideUp("slow");

            $("li.sub-menu>a").bind('click', function () {
                $("li.sub-menu>ul.sub").each(function (index, element) {
                    $(element).removeClass("active").slideUp();
                });
                $("li.sub-menu>a").each(function (index, element) {
                    $(element).removeClass("activeLink");
                });

                jQuery(this).addClass('activeLink');
                jQuery(this).next().addClass('active');
                //if($(li.sub-menu>ul.active).hasClass( "active" ))
                $("li.sub-menu>ul.active").slideDown("slow");
            });
        });


    </script>

</section>
@yield('script')

<script src="{{URL::to('style/persianCalender/persian-date-0.1.8.min.js')}}"></script>
<script src="{{URL::to('style/persianCalender/persian-datepicker-0.4.5.min.js')}}"></script>
<script>

    (function($){
        $(window).on("load",function(){

            $("body").mCustomScrollbar({
                theme:"dark",
            });
            $(".ALAKI11").pDatepicker({
                altField: '.ALAKI11',
                altFormat: "YYYY/MM/DD HH:mm:ss",
                format: "YYYY/MM/DD HH:mm:ss",

                timePicker: {
                    enabled: true
                },
            });
        });
    })(jQuery);

</script>
</body>
</html>





