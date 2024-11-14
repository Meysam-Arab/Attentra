@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
?>

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
        <form action="{{ url('form-submit') }}"  method="post" enctype="multipart/form-data" class="form-horizontal style-form">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <input type="file" name="file" />
                </div>
            </div>


        <ul class="tab">
            <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">فارسی</a></li>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openCity(event, 'Paris')">English</a></li>
        </ul>


        <div id="London" class="tabcontent" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="title1">عنوان</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="title1" placeholder="" value="{{ old('title1') }}" >
                </div>
                <span class="error">{{ $errors->first('title1') }}</span><br>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="Description1">توضیحات</label>
                <div class="col-sm-10">
                    <textarea class="form-control" type="text" name="Description1" placeholder="" >{{ old('Description') }}</textarea>
                </div>
                <span class="error">{{ $errors->first('title1') }}</span><br>
            </div>

            <p class="success">{{ session('message') }}</p>
            <p class="error">{{ session('error') }}</p>
        </div>

        <div id="Paris" class="tabcontent">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="title2">Title</label>
                <div class="col-sm-10">
                    <input class="form-control" type="text" name="title2" placeholder="" value="{{ old('title2') }}" >
                </div>
                <span class="error">{{ $errors->first('title2') }}</span><br>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label" for="Description2">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" type="text" name="Description2" placeholder="" >{{ old('Description') }}</textarea>
                </div>
                <span class="error">{{ $errors->first('Description2') }}</span><br>
            </div>


        </div>

            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    {!! Form::submit('update',['class' => 'form-control btn btn-theme' ]) !!}
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