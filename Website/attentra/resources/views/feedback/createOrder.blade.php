<div class="row">
    <div class="col-md-12 text-right text-center-mobile animated">
        <div class="" id="hiddenMessageOrder">

        </div>
        <img id="imgLoaderForOrder" class="hidden" src="{{URL::to('style/profile/test/img/loader.gif')}}" style="z-index: 3000;position:absolute;top:100px;margin-left: auto;
            margin-right: auto;
            left: 0;
            right: 0;display:block;margin:0 auto;">
        {!! Form::open(['url' => '#','class'=>'mail-form has-validation-callback','id'=>'OrderForm']) !!}

        <div class="row">
            <div class="col-sm-12">
                {{ Form::text('title', null, array('class'=>'form-control','data-validation'=>'required','id'=>'OrderTitle', 'placeholder'=>'عنوان')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{ Form::textarea('description', null, array('class'=>'form-control','data-validation'=>'required','id'=>'OrderDes', 'placeholder'=>'لطفا توضیحاتی در مورد سفارشتان به ما بدهید')) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                {{ Form::text('mobile', null, array('class'=>'form-control','id'=>'OrderMob', 'placeholder'=>'موبایل')) }}
            </div>
            <div class="col-sm-4">
                {{ Form::text('tel', null, array('class'=>'form-control','id'=>'OrderTel', 'placeholder'=>'تلفن')) }}
            </div>
            <div class="col-sm-4">
                {{ Form::text('email', null, array('class'=>'form-control','data-validation'=>'email','id'=>'OrderEmail', 'placeholder'=>'ایمیل')) }}
            </div>
            <div class="col-sm-4">
                <div id="recaptcha4"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12.text-right">
                <input type="submit" class="btn btn-green contact-button" id="OrderFormData" value="ارسال">
            </div>
        </div>

        {{ Form::close() }}

    </div>
</div>