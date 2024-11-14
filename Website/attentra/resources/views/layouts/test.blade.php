<?php

$menu = new \App\Menu(Auth::user()->user_type_id, true);

?>
        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">
    <meta name="author" content="پنل مدیریت">
    <meta name="keyword" content="پنل مدیریت, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>@yield('title')</title>
    <!-- Bootstrap core CSS -->
    <link href="{{URL::to('style/profile/css/bootstrap.css')}}" rel="stylesheet">
    <!--external css-->
    <link href="{{URL::to('style/profile/font-awesome/css/font-awesome.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/css/zabuto_calendar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/js/gritter/css/jquery.gritter.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/lineicons/style.css')}}">
    @if (App::isLocale('pr'))
        <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/css/test.css')}}">
    @endif


<!-- Custom styles for this template -->
    <link href="{{URL::to('style/profile/css/style.css')}}" rel="stylesheet">
    <link href="{{URL::to('style/profile/css/style-responsive.css')}}" rel="stylesheet">

    <script src="{{URL::to('style/profile/js/chart-master/Chart.js')}}"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>


    <![endif]-->
    @yield('style')
</head>

<body>
<script src="{{URL::to('style/profile/jqchart/js/jquery-1.11.1.min.js')}}" type="text/javascript"></script>
<section id="container">
    <!-- **********************************************************************************************************************************************************
    TOP BAR CONTENT & NOTIFICATIONS
    *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars tooltips" data-placement="left"></div>
        </div>
        <span>سلام {{Auth::user()->name}} {{Auth::user()->family}} {{ App::getLocale()}} </span>
        <!--logo start-->
        <a href="index.html" class="logo"><b>DASHGUM FREE</b></a>
        <!--logo end-->

        <div class="top-menu">
            <ul class="nav pull-left top-menu">
                <li><a href="{{ url('/logout') }}" class="">خروج</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ config()->get('languages')[app()->getLocale()] }}
                    </a>
                    <ul class="dropdown-menu">
                        @foreach (config()->get('languages') as $lang => $language)
                            @if ($lang != app()->getLocale())
                                <li>
                                    <a href="{{ route('lang.switch', $lang) }}">{{$language}}</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
    </header>
    <!--header end-->

    <!-- **********************************************************************************************************************************************************
    MAIN SIDEBAR MENU
    *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse ">
            <!-- sidebar menu start-->
            {{--//super admin--}}

            <ul class="sidebar-menu" id="nav-accordion">
                <p class="centered">
                    <a href="profile.html">
                        <img src="{{URL::to('style/profile/img/ui-sam.jpg')}}" class="img-circle" width="60">
                        {{--<img src="{{ route('company.image', ['filename' =>$logoPath]) }}" class="img-circle"  style="width:60px;"/>--}}
                    </a>
                </p>
                <h5 class="centered">Marcel Newman</h5>
                <li class="mt">
                    <a class="active" href="index.html">
                        <i class="fa fa-dashboard"></i>
                        <span>پنل کاربری</span>
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


            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-md-9 main-chart">
                    @yield('content')

                </div><!-- /col-lg-9 END SECTION MIDDLE -->


                <!-- **********************************************************************************************************************************************************
                RIGHT SIDEBAR CONTENT
                *********************************************************************************************************************************************************** -->

                <div class="col-md-3 ds">
                    <!--COMPLETED ACTIONS DONUTS CHART-->
                    <h3 data-toggle="tooltip" data-placement="left"
                        title="@lang('messages.lbl_MissionsDecsNavbarSide')">@lang('messages.lbl_BestMissioner')</h3>
                    @if(session('top5missionCount')==0)
                        <div class="desc">
                            <div class="thumb">
                                <span class="badge bg-theme"><i class="fa fa-clock-o"></i></span>
                            </div>
                            <div class="details">
                                <p>
                                <h4>
                                    @lang('messages.lbl_NothingMission')
                                </h4>
                                </p>
                            </div>
                        </div>
                    @endif
                <!-- First Action -->
                    @for($index=0;$index<session('top5missionCount');$index++)
                        <div class="desc" title="@lang('messages.lbl_MissionsDecsNavbarSide')">
                            <div class="thumb">
                                <span class="badge bg-theme"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                            <div class="details">
                                <p>{{session('top5missionName'.$index)}} {{session('top5missionFamily'.$index)}} : <br/>
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
                        <div class="desc">
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
                                <p>{{session('top5attendaceName'.$index)}} {{session('top5attendaceFamily'.$index)}} :
                                    <br/>
                                </p>
                                <p>
                                <h4>@lang('messages.lbl_With') {{round(session('top5attendaceCount'.$index)/3600)}} @lang('messages.lbl_HoursOfWork')</h4>
                                </p>
                            </div>
                        </div>
                @endfor


                <!-- CALENDAR-->
                    <div id="calendar" class="mb">
                        <div class="panel green-panel no-margin">
                            <div class="panel-body">
                                <div id="date-popover" class="popover top"
                                     style="cursor: pointer; disadding: block; margin-right: 33%; margin-top: -50px; width: 175px;">
                                    <div class="arrow"></div>
                                    <h3 class="popover-title" style="disadding: none;"></h3>
                                    <div id="date-popover-content" class="popover-content"></div>
                                </div>
                                <div id="my-calendar"></div>
                            </div>
                        </div>
                    </div><!-- / calendar -->

                </div><!-- /col-lg-3 -->
            </div>
            <! --/row -->
        </section>
    </section>

    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
        <div class="text-center">
            2014 - Alvarez.is
            <a href="index.html#" class="go-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--footer end-->
</section>
@yield('script')
<!-- js placed at the end of the document so the pages load faster -->
{{--script aval baraye in ghaleb bud k ba script dovom avaz shod--}}
{{--<script src="{{URL::to('style/profile/js/jquery.js')}}"></script>--}}

{{--<script src="{{URL::to('style/profile/js/jquery-1.8.3.min.js')}}"></script>--}}
<script src="{{URL::to('style/profile/js/bootstrap.min.js')}}"></script>
<script class="include" type="text/javascript"
        src="{{URL::to('style/profile/js/jquery.dcjqaccordion.2.7.js')}}"></script>
<script src="{{URL::to('style/profile/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{URL::to('style/profile/js/jquery.nicescroll.js')}}" type="text/javascript"></script>
<script src="{{URL::to('style/profile/js/jquery.sparkline.js')}}"></script>


<!--common script for all pages-->
<script src="{{URL::to('style/profile/js/common-scripts.js')}}"></script>

<script type="text/javascript" src="{{URL::to('style/profile/js/gritter/js/jquery.gritter.js')}}"></script>
<script type="text/javascript" src="{{URL::to('style/profile/js/gritter-conf.js')}}"></script>

<!--script for this page-->
<script src="{{URL::to('style/profile/js/sparkline-chart.js')}}"></script>
<script src="{{URL::to('style/profile/js/zabuto_calendar.js')}}"></script>


<script type="application/javascript">
    $(document).ready(function () {
        $("#date-popover").popover({html: true, trigger: "manual"});
        $("#date-popover").hide();
        $("#date-popover").click(function (e) {
            $(this).hide();
        });

        $("#my-calendar").zabuto_calendar({
            action: function () {
                return myDateFunction(this.id, false);
            },
            action_nav: function () {
                return myNavFunction(this.id);
            },
            ajax: {
                url: "#",
                modal: true
            },
            legend: [
                {type: "text", label: "Special event", badge: "00"},
                {type: "block", label: "Regular event",}
            ]
        });
    });


    function myNavFunction(id) {
        $("#date-popover").hide();
        var nav = $("#" + id).data("navigation");
        var to = $("#" + id).data("to");
        console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }
</script>

</body>
</html>

