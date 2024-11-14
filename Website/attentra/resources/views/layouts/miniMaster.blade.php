<!DOCTYPE html>
<?php
use Illuminate\Support\Facades\Auth;
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/normalize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/bootstrap.css')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf_token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/owl.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/animate.css')}}">
    <link href="{{URL::to('style/main/font-awesome-4.1.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/et-icons.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/tooltip.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/lightbox.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/LoginStyle.css')}}">
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/PriceTableStyles.css')}}">

    <link rel="stylesheet" type="text/css" href="{{URL::to('style/Slicebox/css/slicebox.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/Slicebox/css/custom.css')}}" />
    <script type="text/javascript" src="{{URL::to('style/Slicebox/js/modernizr.custom.46884.js')}}"></script>

    {{--for mobile slider--}}
    <link src="{{URL::to('style/Responsive-skdslider-mobile/src/skdslider.css')}}" rel="stylesheet"/>



    @if (App::isLocale('pr'))
        <link href="{{URL::to('style/profile/test/css/rtlMaster.css')}}" rel="stylesheet">
    @endif
    <link id="main" rel="stylesheet" type="text/css" href="{{URL::to('style/main/css/publisher.css')}}">
    <style>
        .showmessage {
            color: darkgreen;
            position: relative;
            top: 10px;
            font-size: 13px;
        }
    </style>
    <script>
        var winxhieght=screen.height;
    </script>

</head>
<body>

<div id="wrapper" class="behind">
    <header>
        <div class="container">
            <div class="col-md-6 col-sm-12 wow animated fadeInUp">
                <div class="intro-book">
                    <img src="{{URL::to('style/main/img/book1.png')}}" alt="">
                </div>
            </div>
            <div class="col-md-6 intro-text hidden-sm hidden-xs wow animated fadeInUp">
                <h2 class="heading"> چرا آتـنـتـرا؟</h2>
                <h4 class="subheading">ارزانتر,سریعتر,آنلاین,قابلیت ردیابی,استفاده از QrCode</h4>
                <p style="text-align: justify">
                    صاحب شرکت یا کارخانه یا کارگاه هستید؟ می خواهید وضعیت حضور و غیاب کارکنان رو به صورت آنلاین ببینید؟ در نظر دارید کارمندان را ردیابی کنید؟ خانه دار هستید و می خواهید فرزندان خود را ردیابی کنید؟ در هر شغلی که مشغول به فعالیت هستید با استفاده از برنامه آتنترا می توانید ردیابی و حضور و غیاب افراد مورد نظر را انجام دهید و یا این که ثبت حضور و غیاب را به خود کارکنان و کارمندان بسپارید( توسط گوشی همراه خود آنان و مشخص کردن محدوده ی محل کار و استفاده از مکان یابی تلفن همراه کارمندان). دیگر لازم نیست هزینه ای بابت خرید و پشتیبانی و تعمیرات دستگاه های ساعت زن را پرداخت کنید و نگران پاک شدن و از دست رفتن اطلاعات ذخیره شده باشید زیرا اطلاعات در سرورهایی با امنیت بالا ذخیره شده که در هر مکان و زمان قابل دسترس می باشد. برای ثبت ورود و خروج تنها به یک گوشی دوربین دار متصل به اینترنت نیاز دارید که لزوما گوشی شخصی شما نخواهد بود. در صورتی که درخواستی مبنی بر سفارشی سازی برنامه یا افزودن قابلیت های خاص مانند تشخیص هویت با اثر انگشت، تشخیص هویت چشمی و ... می توانید با ما تماس بگیرید.
                </p>
                <a href="#book" class="scrollto btn btn-white">
                    آموزش کار
                </a>
                <a href="{{URL::to('style/main/img/logo.png')}}" class="btn btn-green">دانلود نرم افزار</a>
            </div>
        </div>
    </header>

    <nav class="navbar navbar-default">
        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img src="{{URL::to('/ic_launcher.png')}}" alt=""></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div>
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
                </div>
                <ul class="nav navbar-nav navbar-left main-nav">
                    @if(Auth::check())
                        <li class="">
                            <a href="{{ url('/index') }}" class="">داشبورد</a>
                        </li>
                    @endif

                    @if(!Auth::check())
                        <li class="addNewLink-main-nav2" id="tesst">
                            <a href="#" data-toggle="modal" data-target="#loginToPro">ورود</a>
                        </li>

                        <!-- Modal -->
                        <div class="modal fade" id="loginToPro" role="dialog" >
                            <div class="modal-dialog modal-md" style="z-index:2000">
                                <div class="modal-content" >
                                    <div class="modal-header" style="background-color: #7cc576;color: white">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">ورود به سامانه</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                            {{ csrf_field() }}

                                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                                <label for="email" class="col-md-4 control-label">نام کاربری</label>

                                                <div class="col-md-6">
                                                    <input id="user_name" type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" required autofocus>
                                                    {{Log::info('old :'.old('user_name'))}}
                                                    {{Log::info('errors :'.json_encode($errors))}}

                                                    @if ($errors->has('user_name'))
                                                        <span class="help-block">
                                                                <strong>{{ $errors->first('user_name') }}</strong>
                                                             </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <label for="password" class="col-md-4 control-label">رمز عبور</label>

                                                <div class="col-md-6">
                                                    <input id="password" type="password" class="form-control" name="password" required>

                                                    @if ($errors->has('password'))
                                                        <span class="help-block">
                                                                 <strong>{{ $errors->first('password') }}</strong>
                                                         </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <label for="password" class="col-md-4 control-label"></label>

                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-4">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" name="remember"> مرا به خاطر بسپار
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-8 col-md-offset-4">
                                                    <button type="submit" class="btn btn-green">
                                                        ورود به سامانه
                                                    </button>

                                                    <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                                        آیا رمز خود را فراموش کردید ؟
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-green" data-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @endif




                    <li class="addNewLink-main-nav">
                        <a href="#" data-toggle="modal" data-target="#register">ثبت نام</a>
                    </li>
                    <!-- Modal -->
                    <div class="modal fade" id="register" role="dialog" >
                        <div class="modal-dialog modal-md" style="z-index:2000">
                            <div class="modal-content" >
                                <div class="modal-header" style="background-color: #7cc576;color: white">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">ثبت نام</h4>
                                </div>
                                <div class="modal-body">
                                    @include('user.create')
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-green" data-dismiss="modal">بستن</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <li><a href="{{URL::to('/articles')}}" class="scrollto">مقالات</a></li>
                    <li><a href="#" class="btn btn-green">دانلود رایگان اپ</a></li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    {{--content-----------------------------------------------------------------------------------------------}}
    @yield('content')

    {{--popup menu for user register-----------------------------------------------------------------------------------------------}}
    @include('user.login')
    @include('user.register')

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-8 text-right text-center-mobile">
                    <p class="copyleft small muted"> تمام حقوق مادی و معنوی این سایت متعلق به
                        <a href="http://www.fardan7eghlim.ir">شرکت فردان هفت اقلیم</a>
                        می باشد. </p>
                    <p class="copyleft small muted"> شما می توانید از سایت آتنترا در صورت پذیرش
                        <a href="{{URL::to('/license')}}">موافقت نامه کاربری</a>
                        استفاده کنید. </p>
                </div>
                <div class="col-sm-2 text-left text-center-mobile">
                    <div class="col-sm-12text-left text-center-mobile" style="margin-top: 20px;"></div>
                    <script src="https://www.zarinpal.com/webservice/TrustCode" type="text/javascript"></script>
                </div>
                <div class="col-sm-2 text-left text-center-mobile">
                    <div class="social">
                        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" class="dribbble"><i class="fa fa-dribbble"></i></a>
                        <a href="#" class="vine"><i class="fa fa-vine"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>


<div class="mobile-nav">
    <ul class="menu">

    </ul>
</div>
<script src="{{URL::to('style/main/js/jquery-1.11.1.min.js')}}"></script>
<script src="{{URL::to('style/Slicebox/js/jquery.slicebox.js')}}"></script>
<script type="text/javascript">
    $(function() {

        var Page = (function() {

            var $navArrows = $( '#nav-arrows' ).hide(),
                $navOptions = $( '#nav-options' ).hide(),
                $shadow = $( '#myshadow' ).hide(),
                slicebox = $( '#sb-slider' ).slicebox( {
                    onReady : function() {

                        $navArrows.show();
                        $navOptions.show();
                        $shadow.show();

                    },
                    orientation : 'h',
                    cuboidsCount : 3
                } ),

                init = function() {

                    initEvents();

                },
                initEvents = function() {

                    // add navigation events
                    $navArrows.children( ':first' ).on( 'click', function() {

                        slicebox.next();
                        return false;

                    } );

                    $navArrows.children( ':last' ).on( 'click', function() {

                        slicebox.previous();
                        return false;

                    } );

                    $( '#navPlay' ).on( 'click', function() {

                        slicebox.play();
                        return false;

                    } );

                    $( '#navPause' ).on( 'click', function() {

                        slicebox.pause();
                        return false;

                    } );

                };

            return { init : init };

        })();

        Page.init();

    });
</script>
{{--//AJAX FORM FOR FEEDBACKS--}}
<script>
    @if(session()->has('messagecheck') )
        @if(session('messagecheck')=='login' )
            $(document).ready(function () {
        $('#loginToPro').modal('show');
    });
    {{ session()->forget('messagecheck')}}
@endif
@endif

$(function(){

        $("#feedbackFormData").click(function(e){
            $("#hiddenMessageFeedback" ).empty();
            $("#imgLoaderForFeedback").show();
            $("#imgLoaderForFeedback").removeClass('hidden');
            e.preventDefault();

            var data = {};
            data.title=$('#feedbackTitle').val();
            data.description=$('#feedbackDes').val();
            data.mobile=$('#feedbackMob').val();
            data.tel=$('#feedbackTel').val();
            data.email =  $('#feedbackEmail').val();
            data._token = $("meta[name='csrf_token']").attr('content');


            $.ajax({
                url:'{{URL::to('/feedback/store')}}',
                method: 'POST',
                data : data,
                success: function(data) {
                    if (data.success == true) {
                        $( "#hiddenMessageFeedback" ).empty();
                        var errorMessageTag = '<div class="alert alert-success">'+"اطلاعات با موفقیت ثبت شد"+' </div>';
                        $("#hiddenMessageFeedback").append(errorMessageTag);

                        document.getElementById('feedbackTitle').value = '';
                        document.getElementById('feedbackDes').value = '';
                        document.getElementById('feedbackMob').value = '';
                        document.getElementById('feedbackTel').value = '';
                        document.getElementById('feedbackEmail').value = '';
                    } else if (data.success == false) {
                        $( "#hiddenMessageFeedback" ).empty();
                        var maData;
                        for(maData in data.message){
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#hiddenMessageFeedback").append(errorMessageTag);
                        }

                    }
                },
                error : function(data) {
                    var errorMessageTag = '<div class="alert alert-danger">'+"متاسفانه ارتباط با سرور برقرار نشد لطفا بعدا به طور مجدد تلاش کنید."+' </div>';
                    $("#hiddenMessageFeedback").append(errorMessageTag);
                },
                complete: function(){
                    $('#imgLoaderForFeedback').hide();
                }
            });


        });
    });
    $(function(){

        $("#OrderFormData").click(function(e){
            $("#hiddenMessageOrder" ).empty();
            $("#imgLoaderForOrder").show();
            $("#imgLoaderForOrder").removeClass('hidden');
            e.preventDefault();

            var data = {};
            data.title="  سفارش   "+$('#OrderTitle').val();
            data.description=$('#OrderDes').val();
            data.mobile=$('#OrderMob').val();
            data.tel=$('#OrderTel').val();
            data.email =  $('#OrderEmail').val();
            data._token = $("meta[name='csrf_token']").attr('content');


            $.ajax({
                url:'{{URL::to('/order/store')}}',
                method: 'POST',
                data : data,
                success: function(data) {
                    if (data.success == true) {
                        $( "#hiddenMessageOrder" ).empty();
                        var errorMessageTag = '<div class="alert alert-success">'+"سفارش شما با موفقیت ثبت شد"+' </div>';
                        $("#hiddenMessageOrder").append(errorMessageTag);

                        document.getElementById('OrderTitle').value = '';
                        document.getElementById('OrderDes').value = '';
                        document.getElementById('OrderMob').value = '';
                        document.getElementById('OrderTel').value = '';
                        document.getElementById('OrderEmail').value = '';
                    } else if (data.success == false) {
                        $( "#hiddenMessageOrder" ).empty();
                        var maData;
                        for(maData in data.message){
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#hiddenMessageOrder").append(errorMessageTag);
                        }

                    }




                },
                error : function(data) {
                    var errorMessageTag = '<div class="alert alert-danger">'+"متاسفانه ارتباط با سرور برقرار نشد لطفا بعدا به طور مجدد تلاش کنید."+' </div>';
                    $("#hiddenMessageOrder").append(errorMessageTag);
                },
                complete: function(){
                    $('#imgLoaderForOrder').hide();
                }
            });


        });
    });
</script>

{{--//AJAX FORM FOR USERS--}}
<script>
    $(function(){
        $("#userFormData").click(function(e){
            $("#hiddenMessageUser" ).empty();
            $("#imgLoader").show();
            $("#imgLoader").removeClass('hidden');
            e.preventDefault();

            var data = {};
            data.name=$('#name').val();
            data.family=$('#family').val();
            data.user_name=$('#user_name1').val();
            data.password=$('#password1').val();
            data.password_confirmation=$('#password_confirmation').val();
            data.email =  $('#email').val();
            if (document.getElementById('licenseRead').checked) {
                data.licenseRead='yes';
            } else {
                data.licenseRead='no';
            }
            data._token = $("meta[name='csrf_token']").attr('content');


            $.ajax({
                url:'{{URL::to('/user/store')}}',
                method: 'POST',
                data : data,
                success: function(data) {
                    if (data.success == true) {
                        var maData;
                        for(maData in data.message){
                            $( "#hiddenMessageUser" ).empty();
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#hiddenMessageUser").append(errorMessageTag);
                        }
                        document.getElementById('name').value = '';
                        document.getElementById('family').value = '';
                        document.getElementById('user_name').value = '';
                        document.getElementById('password').value = '';
                        document.getElementById('password_confirmation').value = '';
                        document.getElementById('email').value = '';
                        $('#hiddenMessageUser').delay(5000).fadeOut('slow');
                    } else if (data.success == false) {
                        $( "#hiddenMessageUser" ).empty();
                        var maData;
                        for(maData in data.message){
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#hiddenMessageUser").append(errorMessageTag);
                        }
                    }
                },
                error : function(data) {
                    document.getElementById("hiddenMessageUser").innerHTML = data.message;
//                    $('#hiddenMessageUser').text("متاسفانه اطلاعات کاربر ثبت نشد");
                },
                complete: function(){
                    $('#imgLoader').hide();
                }
            });


        });
    });
</script>




<script src="{{URL::to('style/main/js/owl.carousel.js')}}"></script>
{{--<script src="{{URL::to('style/main/js/lightbox.min.js')}}"></script>--}}
<script src="{{URL::to('style/main/js/wow.min.js')}}"></script>
<script src="{{URL::to('style/main/js/jquery.onepagenav.js')}}"></script>
<script src="{{URL::to('style/main/js/core.js')}}"></script>
<script src="{{URL::to('style/main/js/tooltip.js')}}"></script>
<script src="{{URL::to('style/main/js/jquery.form-validator.js')}}"></script>
<script src="{{URL::to('style/main/js/preloader.js')}}"></script>
<script src="{{URL::to('style/main/js/main.js')}}"></script>
<script src="{{URL::to('style/main/js/bootstrap.min.js')}}"></script>
<script src="{{URL::to('style/main/js/popup.js')}}"></script>



<script src="{{URL::to('style/Responsive-skdslider-mobile/src/skdslider.min.js')}}"></script>
<script type="text/javascript">
    $(function() {
        $('#demo11').skdslider({'animationType': 'fading'});

    });
</script>



</body>
</html>
