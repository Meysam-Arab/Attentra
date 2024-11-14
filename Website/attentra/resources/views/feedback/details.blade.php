@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
?>

@section('content')


<div>
    <form>

        <div class="row">
            <div class="col-sm-12">
                <input class="form-control" placeholder="title" value="{!! $feedbackRepository->title !!}" name="title" type="text">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <textarea class="form-control" placeholder="توضیحات. . ." value="{!! $feedbackRepository->description !!}" name="description" cols="50" rows="10">{!! $feedbackRepository->description !!}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <input class="form-control" placeholder="موبایل" value="{!! $feedbackRepository->mobile !!}" name="mobile" type="text">
            </div>
            <div class="col-sm-4">
                <input class="form-control" placeholder="تلفن" value="{!! $feedbackRepository->tel !!}" name="tell" type="text">
            </div>
            <div class="col-sm-4">
                <input class="form-control" placeholder="ایمیل" value="{!! $feedbackRepository->email !!}" name="email" type="text">
            </div>
        </div>

    </form>
</div>

@endsection