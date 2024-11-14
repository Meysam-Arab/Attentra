<!-- resources/views/aboutuses/index.blade.php -->

@extends('layouts.profile')
<?php


use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;

?>
@section('content')
    @if(session('message'))
        @if (in_array(session('message')->Code, \App\OperationMessage::RedMessages))
            <div class="alert alert-danger alert-dismissable fade in faa-bounce animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @else
            <div class="alert alert-success alert-dismissable fade in faa-float animated">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    <li>{{ session('message')->Text}}{{ session()->forget('message')}}</li>
                </ul>
            </div>
        @endif
    @endif
    <!-- Bootstrap Boilerplate... -->
    @if(count($errors) > 0)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif

    <div >
        <?php


        $columns = [

//                'user_id' => new Column('user_id','آی دی'),
                'name' => new Column('name','نام'),
                'description' => new Column('family','نام خانوادگی'),
//                (new Column('img_file', 'image'))->setValueFormatter(function($value) {
//                    return "<img src = '/images/$value'/>";
//                }),
                (new Column('edit', ''))->setValueFormatter(function($value, $row) {
                    return "<a href='edit/{$row->user_id}/{ $row->user_guid }'>edit</a>";
                }),
                (new Column('remove', ''))->setValueFormatter(function($value, $row) {
                    return "<a href='destroy/{$row->user_id}/{$row->user_guid}'>remove</a>";
                }),
                (new Column('details', ''))->setValueFormatter(function($value, $row) {
                    return "<a href='show/{$row->user_id}/{$row->user_guid}'>details</a>";
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

    <!--  Current Aboutuses -->
@endsection