<!DOCTYPE html>
<?php
use Illuminate\Support\Facades\Auth;
?>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <link rel="icon" href="{!! asset('ic_launcher.png') !!}"/>
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

    <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit&hl=fa" async defer></script>
    <script>

        //    var recaptcha1;
        var recaptcha2;
        var recaptcha3;
        var recaptcha4;
        var myCallBack = function() {
            //Render the recaptcha1 on the element with ID "recaptcha1"
//        recaptcha1 = grecaptcha.render('recaptcha1', {
//            'sitekey' : '6LeHzyEUAAAAAE6Sxj-B6x6dUKUPUkjQLy9d-rw_', //Replace this with your Site key
//            'theme' : 'light'
//        });

            //Render the recaptcha2 on the element with ID "recaptcha2"
            recaptcha2 = grecaptcha.render('recaptcha2', {
                'sitekey' : '6LeHzyEUAAAAAE6Sxj-B6x6dUKUPUkjQLy9d-rw_', //Replace this with your Site key
                'theme' : 'light'
            });

            recaptcha3 = grecaptcha.render('recaptcha3', {
                'sitekey' : '6LeHzyEUAAAAAE6Sxj-B6x6dUKUPUkjQLy9d-rw_', //Replace this with your Site key
                'theme' : 'light'
            });

            //Render the recaptcha2 on the element with ID "recaptcha2"
            recaptcha4 = grecaptcha.render('recaptcha4', {
                'sitekey' : '6LeHzyEUAAAAAE6Sxj-B6x6dUKUPUkjQLy9d-rw_', //Replace this with your Site key
                'theme' : 'light'
            });
        };
    </script>
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
                <h2 class="heading">  چرا آتـنـتـرا؟</h2>
                <h4 class="subheading">ارزانتر,سریعتر,آنلاین,قابلیت ردیابی,استفاده از QrCode</h4>
                {{--<p>--}}
                    {{--دیگه نه لازمه دستگاههای گرون کارت خون بخری نه نگران این باشی که اطلاعاتت پاک بشه چون داره به صورت ابری روی سرورای ما ذخیره میشه فقط یه گوشی اندرویدی لازم داری تا همه این کارارو بکنی,اگه هم--}}
                    {{--<a data-toggle="modal" data-target="#ordered">اینجا</a>--}}
                    {{--سفارش بدی ما میتونیم روی سرورای خودت ذخیرش کنیم تازه با استفاده از سیستم QRCode هر کارمند یه کارت داره که باهمین می تونی موقع فرستادن کارمندات به ماموریت ردیابیشون کنی یا حضور و غیاباشونو توش ثبت کنن همین الان این اپ رو دانلود کن و از امکانات زیاده این نرم افزار لذت ببر یا برای آموزش دکمه پایینو بزن--}}
                {{--</p>--}}
                <p style="text-align: justify">
                    صاحب شرکت یا کارخانه یا کارگاه هستید؟ می خواهید وضعیت حضور و غیاب کارکنان رو به صورت آنلاین ببینید؟ در نظر دارید کارمندان را ردیابی کنید؟ خانه دار هستید و می خواهید فرزندان خود را ردیابی کنید؟ در هر شغلی که مشغول به فعالیت هستید با استفاده از برنامه آتنترا می توانید ردیابی و حضور و غیاب افراد مورد نظر را انجام دهید و یا این که ثبت حضور و غیاب را به خود کارکنان و کارمندان بسپارید( توسط گوشی همراه خود آنان و مشخص کردن محدوده ی محل کار و استفاده از مکان یابی تلفن همراه کارمندان). دیگر لازم نیست هزینه ای بابت خرید و پشتیبانی و تعمیرات دستگاه های ساعت زن را پرداخت کنید و نگران پاک شدن و از دست رفتن اطلاعات ذخیره شده باشید زیرا اطلاعات در سرورهایی با امنیت بالا ذخیره شده که در هر مکان و زمان قابل دسترس می باشد. برای ثبت ورود و خروج تنها به یک گوشی دوربین دار متصل به اینترنت نیاز دارید که لزوما گوشی شخصی شما نخواهد بود. در صورتی که درخواستی مبنی بر سفارشی سازی برنامه یا افزودن قابلیت های خاص مانند تشخیص هویت با اثر انگشت، تشخیص هویت چشمی و ... می توانید با ما تماس بگیرید.
                </p>
                <a href="{{URL::to('style/main/tutorial/tutorial.zip')}}" class="scrollto btn btn-white">
                   آموزش کار
                </a>
                <a href="{{URL::to('https://cafebazaar.ir/app/ir.fardan7eghlim.attentra/?l=fa')}}" target="_blank" class="btn btn-green">دانلود نرم افزار</a>
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
                            <li class="addNewLink-main-nav2" id="">
                                    <a href="#" data-toggle="modal" data-target="#loginToPro">ورود</a>
                            </li>
                     @endif




                    <li class="addNewLink-main-nav"  >
                        {{--<a href="#logintoProfileSection" id="azxs" class="scrollto" data-toggle="modal" data-target="#register">ثبت نام</a>--}}
                        <a href="#" data-toggle="modal" data-target="#register">ثبت نام</a>
                    </li>
                    <li><a href="#author" class="scrollto">سفارش</a></li>
                    <li><a href="#book" class="scrollto">چرا آتنترا</a></li>
                    <li><a href="#reviews" class="scrollto">ویژگی های نرم افزار</a></li>
                    <li><a href="#contact" class="scrollto">تماس با ما</a></li>
                    <li><a href="{{URL::to('/articles')}}" class="scrollto">مقالات</a></li>
                    <li><a href="<?php echo e(URL::to('/getAPK')); ?>" class="btn btn-green">دانلود مستقیم</a></li>

                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
             {{--content-----------------------------------------------------------------------------------------------}}
    @yield('content')

             {{--popup menu for user register-----------------------------------------------------------------------------------------------}}
    @include('user.login')
   {{--@include('user.register')--}}

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-8 text-right text-center-mobile">
                    <p class="copyleft small muted"> تمام حقوق مادی و معنوی این سایت متعلق به
                            <a href="http://www.fardan7eghlim.ir">شرکت هوش مصنوعی فردان هفت اقلیم</a>
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
    $(document).ready(function(){
        $("#password_reset").click(function(){
            // action goes here!!
            $("#loginToPro").modal("hide");
            $("#forgetpass_modal").modal("show");
        });
    });
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
                    cuboidsCount : 3,
                    autoplay : true,
                    interval:8000,
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
            $(':input[type="submit"]').prop('disabled', true);
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
            data.captcha = grecaptcha.getResponse(recaptcha3)
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
                        grecaptcha.reset(recaptcha3);
                        $( "#hiddenMessageFeedback" ).empty();
                        var maData;
                        for(maData in data.message){
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#hiddenMessageFeedback").append(errorMessageTag);
                        }

                    }

                },
                error : function(data) {
                    grecaptcha.reset(recaptcha3);
                    var errorMessageTag = '<div class="alert alert-danger">'+"متاسفانه ارتباط با سرور برقرار نشد لطفا بعدا به طور مجدد تلاش کنید."+' </div>';
                    $("#hiddenMessageFeedback").append(errorMessageTag);
                },
                complete: function(){
                    $('#imgLoaderForFeedback').hide();
                    $(':input[type="submit"]').prop('disabled', false);
                }
            });


        });
    });
    $(function(){

        $("#OrderFormData").click(function(e){
            $(':input[type="submit"]').prop('disabled', true);
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
            data.captcha = grecaptcha.getResponse(recaptcha4)
//
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
                        grecaptcha.reset(recaptcha4);
                        $( "#hiddenMessageOrder" ).empty();
                        var maData;
                        for(maData in data.message){
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#hiddenMessageOrder").append(errorMessageTag);
                        }

                    }




                },
                error : function(data) {
                    grecaptcha.reset(recaptcha4);
                    var errorMessageTag = '<div class="alert alert-danger">'+"متاسفانه ارتباط با سرور برقرار نشد لطفا بعدا به طور مجدد تلاش کنید."+' </div>';
                    $("#hiddenMessageOrder").append(errorMessageTag);
                },
                complete: function(){
                    $('#imgLoaderForOrder').hide();
                    $(':input[type="submit"]').prop('disabled', false);
                }
            });


        });
    });
    $(function(){

        $("#forget_pass").click(function(e){
            $(':input[type="submit"]').prop('disabled', true);
            $("#add_err_forgetpass" ).empty();
            $("#imgLoaderForforgetpass").show();
            $("#imgLoaderForforgetpass").removeClass('hidden');
            e.preventDefault();

            var data = {};

            data.email =  $('#email_forget').val();
            data._token = $("meta[name='csrf_token']").attr('content');


            $.ajax({
                url:'{{URL::to('/forgetpassword')}}',
                method: 'POST',
                data : data,
                success: function(data) {
                    if (data.success == true) {
                        $( "#add_err_forgetpass" ).empty();
                        var errorMessageTag = '<div class="alert alert-success">'+"یک ایمیل حاوی رمز جدید شما برایتان ارسال شد لطفا پست الکترونیکی خود را بررسی کنید"+' </div>';
                        $("#add_err_forgetpass").append(errorMessageTag);


                        document.getElementById('email_forget').value = '';
                    } else if (data.success == false) {

                        $( "#add_err_forgetpass" ).empty();
                        var maData;
                        for(maData in data.message){
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#add_err_forgetpass").append(errorMessageTag);
                        }

                    }

                },
                error : function(data) {
                    var errorMessageTag = '<div class="alert alert-danger">'+"متاسفانه ارتباط با سرور برقرار نشد لطفا بعدا به طور مجدد تلاش کنید."+' </div>';
                    $("#add_err_forgetpass").append(errorMessageTag);
                },
                complete: function(){
                    $('#imgLoaderForforgetpass').hide();
                    $(':input[type="submit"]').prop('disabled', false);
                }
            });


        });
    });
    {{--$(function(){--}}
        {{--$("#userFormData").click(function(e){--}}
            {{--$("#hiddenMessageUser" ).empty();--}}
            {{--$("#imgLoader").show();--}}
            {{--$("#imgLoader").removeClass('hidden');--}}
            {{--e.preventDefault();--}}

            {{--var data = {};--}}
            {{--data.name=$('#name').val();--}}
            {{--data.family=$('#family').val();--}}
            {{--data.user_name=$('#user_name1').val();--}}
            {{--data.password=$('#password1').val();--}}
            {{--data.password_confirmation=$('#password_confirmation').val();--}}
            {{--data.email =  $('#email').val();--}}
            {{--if (document.getElementById('licenseRead').checked) {--}}
                {{--data.licenseRead='yes';--}}
            {{--} else {--}}
                {{--data.licenseRead='no';--}}
            {{--}--}}
            {{--data._token = $("meta[name='csrf_token']").attr('content');--}}

            {{--data.captcha = grecaptcha.getResponse(recaptcha2)--}}
            {{--$.ajax({--}}
                {{--url:'{{URL::to('/user/store')}}',--}}
                {{--method: 'POST',--}}
                {{--data : data,--}}
                {{--success: function(data) {--}}
                    {{--if (data.success == true) {--}}
                        {{--var maData;--}}
                        {{--for(maData in data.message){--}}
                            {{--$( "#hiddenMessageUser" ).empty();--}}
                            {{--var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';--}}
                            {{--$("#hiddenMessageUser").append(errorMessageTag);--}}
                        {{--}--}}
                        {{--document.getElementById('name').value = '';--}}
                        {{--document.getElementById('family').value = '';--}}
                        {{--document.getElementById('user_name').value = '';--}}
                        {{--document.getElementById('password').value = '';--}}
                        {{--document.getElementById('password_confirmation').value = '';--}}
                        {{--document.getElementById('email').value = '';--}}
                        {{--$('#hiddenMessageUser').delay(5000).fadeOut('slow');--}}
                    {{--} else if (data.success == false) {--}}
                        {{--grecaptcha.reset(recaptcha2);--}}
                        {{--$( "#hiddenMessageUser" ).empty();--}}
                        {{--var maData;--}}
                        {{--for(maData in data.message){--}}
                            {{--var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';--}}
                            {{--$("#hiddenMessageUser").append(errorMessageTag);--}}
                        {{--}--}}
                    {{--}--}}
                {{--},--}}
                {{--error : function(data) {--}}
                    {{--grecaptcha.reset(recaptcha2);--}}
                    {{--document.getElementById("hiddenMessageUser").innerHTML = data.message;--}}
{{--//                    $('#hiddenMessageUser').text("متاسفانه اطلاعات کاربر ثبت نشد");--}}
                {{--},--}}
                {{--complete: function(){--}}
                    {{--$('#imgLoader').hide();--}}
                {{--}--}}
            {{--});--}}


        {{--});--}}
    {{--});--}}
    $(document).ready(function(){

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

            data.captcha = grecaptcha.getResponse(recaptcha2)
            $.ajax({
                url:'{{URL::to('/user/store')}}',
                method: 'POST',
                data : data,
                success: function(data) {
                    if (data.success == true) {
                        var maData;
                        $( "#hiddenMessageUser" ).empty();
                        var errorMessageTag = '<div class="alert alert-success">'+data.message+' </div>';
                        $("#hiddenMessageUser").append(errorMessageTag);
//                        for(maData in data.message){
//
//                        }
                        document.getElementById('name').value = '';
                        document.getElementById('family').value = '';
                        document.getElementById('user_name').value = '';
                        document.getElementById('password').value = '';
                        document.getElementById('password_confirmation').value = '';
                        document.getElementById('email').value = '';
                        $('#hiddenMessageUser').delay(5000).fadeOut('slow');
                    } else if (data.success == false) {
                        grecaptcha.reset(recaptcha2);
                        $( "#hiddenMessageUser" ).empty();
                        var maData;
                        for(maData in data.message){
                            var errorMessageTag = '<div class="alert alert-danger">'+data.message[maData]+' </div>';
                            $("#hiddenMessageUser").append(errorMessageTag);
                        }
                    }
                },
                error : function(data) {
                    grecaptcha.reset(recaptcha2);
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

{{--//AJAX FORM FOR USERS--}}



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
          $('#demo11').skdslider({'animationType': 'fading','delay':5000});
    });
    $(window).on('load resize', function () {
        var w = $('.background-device').width();
        $("#demo11").width(w*.8+'px');
    });
    $(window).on('load resize', function () {
        if($(window).width()<780){
            var w = $('.background-device').width();
            var dif=$('#highparent').width()-$('#middleParent').width()+20;
//            alert(w);
            $(".setImageAndMobule").width(w*.8+'px')
            $(".setImageAndMobule").css({ top: '87px' });
            $(".setImageAndMobule").css({ left:'1px' });
            $(".setImageAndMobule").css({ height: '390px' });
        }
    });
</script>


<!-- Modal -->
<div class="modal fade" id="ordered" role="dialog" >
    <div class="modal-dialog modal-md" style="z-index:2000">
        <div class="modal-content" >
            <div class="modal-header" style="background-color: #7cc576;color: white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ثبت سفارش</h4>
            </div>
            <div class="modal-body">
                @include('feedback.createOrder')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-green" data-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal login-->
<div class="modal fade" id="loginToPro" role="dialog" style="z-index: 99999;">
    <div class="modal-dialog modal-md" style="z-index:2000">
        <div class="modal-content" >
            <div class="modal-header" style="background-color: #7cc576;color: white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ورود به سامانه</h4>
            </div>
            <div class="modal-body">

                <div class="" id="add_err" style="margin: 10px auto;display: block;"> </div>
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
                <br/>
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                    <div class="col-md-pull-2 col-md-push-2">

                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-2 control-label">نام کاربری</label>

                            <div class="col-md-6">
                                <input id="user_name" type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-2 control-label">رمز عبور</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-2 control-label"></label>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> مرا به خاطر بسپار
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8">
                                <button type="" class="btn btn-green" style="margin:0px">
                                    ورود به سامانه
                                </button>

                                <a style="margin:0px" id="password_reset" class="btn btn-link" data-dismiss="modal" data-toggle="#forgetpass_modal" href="#forgetpass_modal" >
                                    آیا رمز خود را فراموش کردید ؟
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-green" data-dismiss="modal" style="margin:0px">بستن</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal forget pass-->
<div class="modal fade" id="forgetpass_modal" role="dialog" >
    <img id="imgLoaderForforgetpass" class="hidden" src="{{URL::to('style/profile/test/img/loader.gif')}}" style="z-index: 3000;position:absolute;top:100px;margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;display:block;margin:0 auto;">
    <div class="modal-dialog modal-md" style="z-index:2000">
        <div class="modal-content" >
            <div class="modal-header" style="background-color: #7cc576;color: white">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">فراموشی رمز عبور</h4>
            </div>
            <div class="modal-body">

                <div class="" id="add_err_forgetpass" style="margin: 10px auto;display: block;"> </div>

                <br/>
                <form class="form-horizontal" role="form" method="POST" action="#" >

                    <div class="col-md-pull-2 col-md-push-2">

                        {{ csrf_field() }}

                        <div class="form-group">

                            <div class="col-md-2">
                                <label for="email" class="control-label">ایمیل</label>
                            </div>
                            <div class="col-md-6">
                                <input id="email_forget" type="text" data-validation="email" class="error form-control" name="email" value="{{ old('user_name') }}" required autofocus>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-8">
                                <button type="submit" id="forget_pass" class="btn btn-green" style="margin:0px">
                                    بررسی
                                </button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-green" data-dismiss="modal" style="margin:0px">بستن</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="register" role="dialog" style="z-index: 99999;">
    <div class="modal-dialog modal-md" style="z-index:20000">
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
</body>
</html>
