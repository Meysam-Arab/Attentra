@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
use ViewComponents\ViewComponents\Component\Control\PaginationControl;
use ViewComponents\ViewComponents\Input\InputSource;


?>
@section('style')
    <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/flipflop/css/flipclock.css')}}">
@stop
@section('content')
    <!-- probebly errors feedback -->
    @if(count($errors) > 0)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif
    <div class="clock" style="margin:2em;zoom: 0.7;
    -moz-transform: scale(0.5);"></div>

    <script type="text/javascript">
        var clock;

        $(document).ready(function() {
            // Calculate the difference in seconds between the future and current date
                    @if(true)
            var t='{{$second}}';
            console.log(t);
                    @endif
            var diff = t;

            // Instantiate a coutdown FlipClock
            clock = $('.clock').FlipClock(diff, {
                clockFace: 'DailyCounter',
                countdown: true,
                language: 'fa'
            });
        });
    </script>


    <div >
        <span class="showmessage"></span>
        <a  href="{{URL::to('attendance')}}">نمایش نمودار بر اساس عملکرد ماهانه</a>
    </div>

    <?php
    $input = new InputSource($_GET);
    $columns = [
        new Column('start_date_time','تاریخ شروع'),
        new Column('end_date_time','زمان پایان'),

        new PaginationControl($input->option('page', 1), 30),
    ];

    $grid = new Grid($provider, $columns);
    $customization = new BootstrapStyling();
    $customization->apply($grid);

    ?>
    <?= $grid->render() ?>



@endsection
@section('script')
    <script src="{{URL::to('style/profile/flipflop/js/flipclock.min.js')}}" type="text/javascript"></script>
@stop
