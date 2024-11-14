<!-- resources/views/aboutus/index.blade.php -->

@extends('layouts.app')
<?php


use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
?>
@section('content')

    <!-- Bootstrap Boilerplate... -->

    <div class="panel-body">
        <!-- Display Validation Errors -->
    {{--@include('common.errors')--}}
        {{--@for ($i = 0; $i < 10; $i++)--}}
            {{--The current value is {{ $i }}--}}
    {{--@endfor--}}
        {{--{{ isset($aboutUses) ? $aboutUses : 'Default' }}--}}

        @foreach ($aboutUses as $x)
        <p>This is record {{ $x->name }}</p>
        @endforeach

    <!-- New Task Form -->
        <form action="{{ url('aboutuses') }}" method="POST" class="form-horizontal">
        {{ csrf_field() }}
             <!-- Task Name -->
            <div class="form-group">
                <label for="aboutus-name" class="col-sm-3 control-label">درباره ما</label>

                <div class="col-sm-6">
                    <input type="text" name="name" id="aboutus-name" class="form-control">
                </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i> افزودن درباره ما
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div>
    <?php


        $columns = [

                'name' => new Column('name','نام'),
                'description' => new Column('description','توضیحات')
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