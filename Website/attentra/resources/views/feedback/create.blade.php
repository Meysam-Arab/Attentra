<div class="row">
    <div class="col-md-6 text-right text-center-mobile wow animated fadeInUp contact-details">
        <h3 class="heading no-margin wow animated fadeInUp">با ما در تماس باشید</h3>
        <h4 class="subheading muted no-margin wow animated fadeInUp">ما قابلیت این را داریم که هر نرم افزار یا وب سایت که مد نظر شماست را طراحی کنیم. </h4>
        <div class="details">
            <h6 class="heading no-margin">منتظر شما هستیم</h6>
            <p class="small muted wow animated fadeInUp no-margin">
                باعث خوشحالی ما خواهد بود که پیشنهادات یا انتقادات شما را دریافت کنیم.<br>

            </p>
        </div>
        <div class="details">
            <h4 class="subheading accent no-margin wow animated fadeInUp"> 07132221402 <br>
                info@attentra.ir</h4>
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
    <div class="col-md-6 text-right text-center-mobile wow  fadeInUp contact-details animated">
        <div class="" id="hiddenMessageFeedback">

        </div>
        <img id="imgLoaderForFeedback" class="hidden" src="{{URL::to('style/profile/test/img/loader.gif')}}" alt="منیمنبمسنم" style="z-index: 3000;position:absolute;top:100px;margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;display:block;margin:0 auto;">
        {!! Form::open(['url' => '#','class'=>'mail-form has-validation-callback','id'=>'feedbackForm']) !!}

        <div class="row">
            <div class="col-sm-12">
                {{ Form::text('title', null, array('class'=>'form-control','data-validation'=>'required','id'=>'feedbackTitle', 'placeholder'=>'عنوان')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ Form::textarea('description', null, array('class'=>'form-control','data-validation'=>'required','id'=>'feedbackDes', 'placeholder'=>'توضیحات. . .')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                {{ Form::text('mobile', null, array('class'=>'form-control','id'=>'feedbackMob', 'placeholder'=>'موبایل')) }}
            </div>
            <div class="col-sm-4">
                {{ Form::text('tel', null, array('class'=>'form-control','id'=>'feedbackTel', 'placeholder'=>'تلفن')) }}
            </div>
            <div class="col-sm-4">
                {{ Form::text('email', null, array('class'=>'form-control','data-validation'=>'email','id'=>'feedbackEmail', 'placeholder'=>'ایمیل')) }}
            </div>
            <div class="col-sm-4">
                <div id="recaptcha3"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12.text-right">
                <input type="submit" class="btn btn-green contact-button" id="feedbackFormData" value="ارسال">
                <span class="showmessage" id="hiddenMessage"></span>
            </div>
        </div>
        {{ Form::close() }}

    </div>


</div>



