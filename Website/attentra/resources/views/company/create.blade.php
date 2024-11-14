@extends('layouts.profile')
<?php
?>

@section('style')

    <link href="{{URL::to('style/profile/test/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />

@stop
@section('content')
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>@lang('messages.btn_AddCompany')</li>
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
            <div class="alert alert-success fade in faa-float animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif

    <div style="">


        <form action="{{ url('company/store') }}" method="post" enctype="multipart/form-data"
              class="form-horizontal style-form">


            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">@lang('messages.lbl_companyLogo')</label>
                <div class="col-sm-10">
                    {{--<input type="file" name="fileLogo"/>--}}
                    <span class="btn btn-default btn-file">
                        <input id="input-img" type="file" multiple class="file-loading" name="fileLogo">
                    </span>
                </div>
            </div>




            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="title1">@lang('messages.lbl_CompanyName')</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="name" placeholder="">
                </div>
                <span class="error">{{ $errors->first('name') }}</span><br><br>
            </div>


            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="title1">@lang('messages.lbl_Timezone')</label>
                <div class="col-sm-10">
                    <?php
                    $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
//                    $i = 0;
//                    $file = fopen('D:\alaki.txt', 'a');
//                    foreach ($tzlist as $gem)
//                    {
//                        $data = $gem . PHP_EOL ;
//                        fwrite($file,$data);
//
//
//                    }
//                    fclose($file);
                    ?>
                    <select name="time_zone" id="time_zone">

                        @for($i=0;$i<count($tzlist);$i++)
<!--                            --><?php
//                                Log::info($tzlist[$i]);
//                            ?>
                            @if($tzlist[$i] == 'Asia/Tehran' )
                                <option value="{{$tzlist[$i]}}" selected="selected">{{$tzlist[$i]}}</option>
                            @else if
                                <option value="{{$tzlist[$i]}}">{{$tzlist[$i]}}</option>
                            @endif
                        @endfor
                    </select>
                </div>
                <span class="error">{{ $errors->first('time_zone') }}</span><br>
            </div>


            <p class="success">{{ session('message') }}</p>
            <p class="error">{{ session('error') }}</p>


            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    {!! Form::submit(trans('messages.btn_AddCompany'),['class' => 'form-control btn btn-success' ]) !!}
                </div>
            </div>
        </form>

    </div>

@endsection
@section('script')

    <!-- canvas-to-blob.min.js is only needed if you wish to resize images before upload.
         {{--This must be loaded before fileinput.min.js -->--}}
    {{--<script src="{{URL::to('style/profile/test/js/plugins/canvas-to-blob.min.js')}}" type="text/javascript"></script>--}}
    {{--<!-- sortable.min.js is only needed if you wish to sort / rearrange files in initial preview.--}}
         {{--This must be loaded before fileinput.min.js -->--}}
    {{--<script src="{{URL::to('style/profile/test/js/plugins/sortable.min.js')}}" type="text/javascript"></script>--}}
    {{--<!-- purify.min.js is only needed if you wish to purify HTML content in your preview for HTML files.--}}
         {{--This must be loaded before fileinput.min.js -->--}}
    {{--<script src="{{URL::to('style/profile/test/js/plugins/purify.min.js')}}" type="text/javascript"></script>--}}
    <!-- the main fileinput plugin file -->
    <script src="{{URL::to('style/profile/test/js/fileinput.js')}}"></script>
    <!-- bootstrap.js below is needed if you wish to zoom and view file content
         in a larger detailed modal dialog -->
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>--}}
    <!-- optionally if you need a theme like font awesome theme you can include
        it as mentioned below -->
    {{--<script src="{{URL::to('style/profile/test/js/fa.js')}}"></script>--}}
    <!-- optionally if you need translation for your language then include
        locale file as mentioned below -->
    @if (App::isLocale('pr'))
        <script src="{{URL::to('style/profile/test/js/locales/fa.js')}}"></script>
    @endif


    <script>




        $("#input-img").fileinput({

            browseClass: "btn btn-success btn-block",
            showCaption: false,
            showRemove: false,
            showUpload: false,
            maxFileSize: 200,

            @if (App::isLocale('pr'))
            language: "fa",
            @endif
            allowedFileExtensions: ["jpg", "png", "gif"],



        });
    </script>
@stop