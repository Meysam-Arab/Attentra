@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
?>

@section('content')

<div class="form-panel">
        <form class="form-horizontal style-form" method="get">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">name</label>
                <div class="col-sm-10">
                    <input class="form-control" placeholder="name" value="{!! $user->name !!}" name="nameOfUser" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">family</label>
                <div class="col-sm-10">
                    <input class="form-control" placeholder="family" value="{!! $user->family !!}" name="familyOfUser" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">User Name</label>
                <div class="col-sm-10">
                    <input class="form-control" id="disabledInput" placeholder="{!! $user->user_name !!}"   type="text" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">payment</label>
                <div class="col-sm-10">
                    <input class="form-control" placeholder="payment" value="{!! $user->payment !!}" name="payment" type="text">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">email</label>
                <div class="col-sm-10">
                    <input class="form-control" placeholder="email" value="{!! $user->email !!}" name="email" type="text">
                </div>
            </div>
        </form>

</div>







@endsection