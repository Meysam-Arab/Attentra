@extends('layouts.profile')

@section('style')

    <link href="{{URL::to('style/profile/test/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 450px;
        }
    </style>
@stop
@section('content')
    <div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <ul>
            <li>@lang('messages.tlt_Edit_institute')</li>
        </ul>
    </div>
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissable fade in faa-bounce animated">
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
        <form action="{{ url('company/update') }}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">

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
            <input type="hidden" name="company_id" value="{{ $company_id }}">
            <input type="hidden" name="company_guid" value="{{$company_guid  }}">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="name">{{trans('messages.lbl_CompanyName')}} </label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="name"  value="{{$nameOfCompany}}"  >
                </div>
                <span class="error">{{ $errors->first('name') }}</span><br>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="title1">@lang('messages.lbl_Timezone')</label>
                <div class="col-sm-10">
                    <?php
                    $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                    ?>
                    <select name="time_zone" id="time_zone">
                        @for($i=0;$i<count($tzlist);$i++)
                            @if($tzlist[$i] == $time_zone )
                                <option value="{{$tzlist[$i]}}" selected="selected">{{$tzlist[$i]}}</option>
                            @endif
                            <option value="{{$tzlist[$i]}}">{{$tzlist[$i]}}</option>
                        @endfor
                    </select>
                </div>

                <span class="error">{{ $errors->first('time_zone') }}</span><br>
            </div>

            <p class="success">{{ session('message') }}</p>
            <p class="error">{{ session('error') }}</p>



            <div class="form-group">
                <div class="alert alert-warning alert-dismissable fade in faa-bounce animated">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <ul>
                        <li>توجه کنید برای کارایی بهتر محدوده نهاد خود را حداقل به شعاع 100 متر در نظر بگیرید.</li>
                    </ul>
                </div>
                <label class="col-sm-2 col-sm-2 control-label" for="title1">محدوده مکان</label>
                <div class="col-sm-10">
                    <div id="map"></div>
                </div>
            </div>
            <input type="hidden" name="zone" id="zone" value="">


            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    {!! Form::submit(trans('messages.tlt_Edit_institute'),['class' => 'form-control btn btn-success' ]) !!}
                </div>
            </div>
        </form>
















    </div>

@endsection
@section('script')
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMBCC_pf0JtSxH0gvs4_84PXVCL7ufOfE&signed_in=true&libraries=drawing&callback=initMap">

    </script>

    <script>
        var zoom=6;
        var lat=35.6892;
        var lng=51.3890;
        var zonee='{{$zone}}';
        var res = zonee.split(",");
        if(parseFloat(res[3])>0 && parseFloat(res[3])<20){
            zoom=parseFloat(res[3]);
            lat=parseFloat(res[0]);
            lng=parseFloat(res[1]);
        }

        // This example requires the Drawing library. Include the libraries=drawing
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=drawing">

        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: lat, lng: lng},
                zoom: zoom
            });

            var cityCircle = new google.maps.Circle({
                strokeColor: '#FF0000',
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: '#FF0000',
                fillOpacity: 0.35,
                map: map,
                center: {lat: parseFloat(res[0]), lng: parseFloat(res[1])},
                radius: parseFloat(res[2])
            });

            var drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.MARKER,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: ['circle',]
                },
                markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},
                circleOptions: {
                    fillColor: '#4cae4c',
                    fillOpacity: 1,
                    strokeWeight: 5,
                    fillOpacity: 0.35,
                    clickable: false,
                    editable: true,
                    zIndex: 1
                }
            });
            drawingManager.setMap(map);
            google.maps.event.addDomListener(map, 'zoom_changed', function() {
                zoom = map.getZoom();
            });
            google.maps.event.addListener(drawingManager, 'circlecomplete', function(circle) {

                var radius = circle.getRadius();
                var lat= circle.getCenter().lat();
                var lng=circle.getCenter().lng();
                var zone=lat+','+lng+','+radius+','+zoom;
                $('#zone').val(zone);
            });
        }
    </script>


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

            initialPreview: [
                '{{ route('company.image', ['filename' =>$logoPath]) }}'
            ],
            initialPreviewAsData: true,

        });
    </script>
@stop