@extends('layouts.profile')


<style>
    .tab{
    }
    ul.tab {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #424a5d;
    }

    /* Float the list items side by side */
    ul.tab li {float: left;}

    /* Style the links inside the list items */
    ul.tab li a {
        display: inline-block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: 0.3s;
        font-size: 17px;
    }

    /* Change background color of links on hover */
    ul.tab li a:hover {
        background-color: #68dff0;


    }

    /* Create an active/current tablink class */
    ul.tab li a:focus, .active {
        background-color: #68dff0;
    }

    /* Style the tab content */
    .tabcontent {
        display: none;
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-top: none;
    }

    .topright {
        float: right;
        cursor: pointer;
        font-size: 20px;
    }

    .topright:hover {color: red;}
</style>
@section('content')
    <div class="form-panel">
        {{--<p class="success">{{ $message }}</p>--}}

        <p class="error">{{ session('error') }}</p>
        <form action="{{ url('module/update') }}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">


            <ul class="tab">
                @foreach($langs as $lang)
                    <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, '{{$lang->title}}')" id="defaultOpen">{{$lang->title}}</a></li>
                @endforeach

                {{--<li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">فارسی</a></li>--}}
                {{--<li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'Paris')">English</a></li>--}}
            </ul>
            <?php
            $counter=0;
            ?>
            @foreach($langs as $lang)
                <div id="{{$lang->title}}" class="tabcontent" >
                    <?php
                    $desc="Description".$lang->title;
                    $title="title".$lang->title;
                    ?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="{{$title}}">عنوان</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="{{$title}}" placeholder="" value="{{ $moduleRepository[$counter]->title }}" >
                        </div>
                        <span class="error">{{ $errors->first($title) }}</span><br>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 col-sm-2 control-label" for="{{$desc}}">توضیحات</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" type="text" name="{{$desc}}" placeholder="" >{{$moduleRepository[$counter++]->description}}</textarea>
                        </div>
                        <span class="error">{{ $errors->first($desc) }}</span><br>
                    </div>

                    <p class="success">{{ session('message') }}</p>
                    <p class="error">{{ session('error') }}</p>
                </div>
            @endforeach


            <div class="form-group">
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="limit_value">محدودیت</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="limit_value" placeholder="اگر این ماژول محدودیتی در تعداد دارد وارد کنید"  value="{{$moduleRepository[0]->limit_value}}" >
                </div>
                <span class="error">{{ $errors->first('limit_value') }}</span><br>
            </div>

            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="price">قیمت ماژول</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="price" placeholder="به عدد" value="{{$moduleRepository[0]->price}}">
                </div>
                <span class="error">{{ $errors->first('price') }}</span><br>
            </div>

            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="is_active">ماژول فعال باشد</label>
                <div class="col-sm-10">
                    @if($moduleRepository[0]->is_active==1)
                    {{ Form::checkbox('is_active', 'yes','cheched') }}
                    @else($moduleRepository[0]->is_active==0)
                    {{ Form::checkbox('is_active', 'yes') }}
                    @endif
                </div>
                <span class="error">{{ $errors->first('is_active') }}</span><br>
            </div>
            <input class="form-control" type="hidden" name="module_id" placeholder="" value="{{$moduleRepository[0]->module_id}}" >
            <input class="form-control" type="hidden" name="module_guid" placeholder="" value="{{$moduleRepository[0]->module_guid}}" >
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    {!! Form::submit('ویرایش',['class' => 'form-control btn btn-theme' ]) !!}
                </div>
            </div>
        </form>
    </div>





    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>






@endsection