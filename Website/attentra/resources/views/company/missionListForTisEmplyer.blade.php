<!-- resources/views/aboutuses/index.blade.php -->

@extends('layouts.profile')
<?php



use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
use Illuminate\Support\Facades\Lang;

?>
@section('content')

    <!-- Bootstrap Boilerplate... -->
    @if(count($errors) > 0)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif

    <div style="direction: rtl" >

        <div>@Lang('messages.lbl_MissionList')</div>
        <?php

        $columns = [

            'name' => new Column('title',Lang::get('messages.lbl_MissionTitle')),

            'family' => new Column('description',Lang::get('messages.lbl_Description')),

//        'start_date_time' => new Column('start_date_time','تاریخ شروع'),
            (new Column('start_date_time', 'تاریخ شروع'))->setValueFormatter(function($value, $row) {
                $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($row->start_date_time));
                $converted=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                return $converted;
            }),

            (new  Column('end_date_time','تاریخ اتمام پروژه'))->setValueFormatter(function($value, $row) {
                $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($row->end_date_time));
                $converted=\Morilog\Jalali\jDateTime::convertNumbers($temp);
                return $converted;
            })


        ];
        $grid = new Grid($provider, $columns);

        $customization = new BootstrapStyling();
        $customization->apply($grid);
        //        $grid->render();

        //        echo $grid;


        ?>
        <?= $grid->render() ?>
    </div>


@endsection