@extends('layouts.profile')
    <?php
        use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
        use ViewComponents\Grids\Component\Column;
        use ViewComponents\Grids\Grid;
        use ViewComponents\ViewComponents\Component\Control\PaginationControl;
        use ViewComponents\ViewComponents\Input\InputSource;
        use Carbon\Carbon;
    ?>

    @section('style')
        <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/jqchart/css/jquery.jqChart.css')}}">
        <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/jqchart/css/jquery.jqRangeSlider.css')}}">
        <link rel="stylesheet" type="text/css" href="{{URL::to('style/profile/jqchart/css/jquery-ui-1.10.4.css')}}">
    @stop

    @section('content')
        <div>
            <div id="jqChart" style="width: 800px; height: 500px;">
            </div>
        </div>
        <?php
//            $test= $AttendanceRepositories[0]->hourStart;
//            echo $test;

        // set some things
        ?>
        <!-- probebly errors feedback -->
        @if(count($errors) > 0)
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        @endif
        @if(true)

        @endif

    @endsection

    @section('script')
        <script>

                var data1 = [['A', new Date(0,0,0,6,24,0), new Date(0,0,0,10,24,0)], ['B', new Date(0,0,0,6,24,0), new Date(0,0,0,13,24,0)], ['C', new Date(0,0,0,6,24,0), new Date(0,0,0,6,24,0)],
                        ['D', new Date(0,0,0,6,24,0), new Date(0,0,0,6,24,0)], ['E', new Date(0,0,0,6,24,0), new Date(0,0,0,6,24,0)], ['F', new Date(0,0,0,6,24,0), new Date(0,0,0,6,24,0)], ['G', new Date(0,0,0,6,24,0), new Date(0,0,0,6,24,0)]];
                var days=['یکم','دوم','سوم','چهارم','پنجم','ششم','هفتم','هشتم','نهم','دهم','یازدهم','دوازدهم','سیزدهم','چهاردهم','پانزدهم','شانزدهم','هفدهم','هجدهم','نوزدهم','بیستم','بیست و یکم','بیست و دوم','بیست وسوم','بیست و چهارم','بیست وپنجم','بیست و ششم','بیست و هفتم','بیست و هشتم','بیست و نهم','سیم','سی و یکم'];
                var mounth=['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];



                    {{$index=0}}
                    var data2= [];//initialize array

                    @for ($index = 0 ; $index <count($AttendanceRepositories, COUNT_RECURSIVE)/5; $index++) {
                        var i={{$index}};
                        data2[i] = [];//initialize inner array
                        data2[i][0]=days[i];
                        data2[i][1]= new Date(0,0,0,Number({{$AttendanceRepositories[$index]['hourStart']}}),Number({{$AttendanceRepositories[$index]['minuteStart']}}),0);
                        data2[i][2]= new Date(0,0,0,Number({{$AttendanceRepositories[$index]['hourEnd']}}),Number({{$AttendanceRepositories[$index]['minuteEnd']}}),0);


                    }@endfor
//                    alert(data2[4][1]);


        </script>

        <script src="{{URL::to('style/profile/jqchart/js/jquery.mousewheel.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('style/profile/jqchart/js/jquery.jqChart.min.js')}}" type="text/javascript"></script>
        <script src="{{URL::to('style/profile/jqchart/js/jquery.jqRangeSlider.min.js')}}" type="text/javascript"></script>
        <script>

            var text="نمودار عملکرد ساعتی این کارمند در شهریور ماه";


            $(document).ready(function () {

                $('#jqChart').jqChart({
                    title: text,
                    animation: { duration: 1 },
                    shadows: {
                        enabled: true
                    },
                    tooltips: {
                        disabled: false,
                        highlighting: true
                    },
                    axes: [
                        {
                            type: 'dateTime',
                            location: 'bottom',
                            minimum: new Date(0,0,0,0,0,0),
                            maximum: new Date(0,0,0,23,59,0),
                            interval: 1,
                            intervalType: 'minutes' // 'years' |  'months' | 'weeks' | 'days' | 'minutes' | 'seconds' | 'millisecond'
                        },
                    ],
                    series: [
                        {
                            type: 'rangeBar',
                            title: 'ساعت کاری',
                            toolTipContent: "<a href = {name}> {label}</a><hr/>Views: {y}",
                            data: data2
                        }
                    ]

                });

                $('#jqChart').bind('tooltipFormat', function (e, data) {

                    var str = data.dataItem[1].toString();
                    var minres = str.split(":");
                    var hourRes=minres[0].split(" ");

                    var str1 = data.dataItem[2].toString();
                    var minres1 = str1.split(":");
                    var hourRes1=minres1[0].split(" ");
                     return "<b>" + data. series.title + "</b><br />" +
                        "روز "+ data.x + " <br />" +
                        "از ساعت " +hourRes[hourRes.length-1]  +":" +minres[1]+ " <br />" +
                        "تا ساعت "+hourRes1[hourRes1.length-1]  +":" +minres1[1]+ "<br />";
                });

            });
        </script>

    @stop