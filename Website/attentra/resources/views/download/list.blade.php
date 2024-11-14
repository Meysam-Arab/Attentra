@extends('layouts.profile')
<?php
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\Grids\Component\Column;
use ViewComponents\Grids\Grid;
use ViewComponents\ViewComponents\Component\Control\PaginationControl;
use ViewComponents\ViewComponents\Input\InputSource;


?>
@section('content')
    <!-- probebly errors feedback -->
    @if(count($errors) > 0)
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif





    <!-- New feedback grid -->
    <div >
        <span class="showmessage"></span>
        <?php
        $input = new InputSource($_GET);
        $columns = [
            new Column('downloadTitle','عنوان'),
            new Column('downloadDes','توضیحات'),
            new Column('languageTitle','زبان'),
            new Column('size','حجم فایل به بایت'),
            new Column('extention','پسوند فایل'),

            (new Column('remove', 'حذفیات'))->setValueFormatter(function($value, $row) {
                return "<a href='delete/{$row->download_id}/{$row->download_guid}'>remove</a>";
            }),

            (new Column('details', 'جزییات'))->setValueFormatter(function($value, $row) {
                return "<a href='/show/{$row->download_id}/{$row->download_guid}'>details</a>";
            }),
            new PaginationControl($input->option('page', 1), 15),
//            new FilterControl('id',FilterOperation::OPERATOR_EQ,$input('id_filter')),
        ];



        $grid = new Grid($provider, $columns);

        $customization = new BootstrapStyling();
        $customization->apply($grid);
        //        $grid->render();

        //        echo $grid;


        ?>
        <?= $grid->render() ?>
    </div>
    {{--<script>--}}
    {{--$(function(){--}}
    {{--$(tr>td>a).click(function(e){--}}

    {{--e.preventDefault();--}}

    {{--var data = {};--}}

    {{--data.name =  $('#text').val()--}}
    {{--data._token = $("meta[name='csrf_token']").attr('content');--}}


    {{--$.ajax({--}}
    {{--url:'http://localhost:8000/feedback/show/{$row->feedback_id}/{$row->feedback_guid}',--}}
    {{--method: 'POST',--}}
    {{--data : data,--}}
    {{--success: function(data) {--}}
    {{--$('.showmessage').text(data);--}}
    {{--} ,--}}
    {{--error : function(data) {--}}
    {{--console.log(data);--}}
    {{--}--}}
    {{--});--}}

    {{--});--}}
    {{--});--}}
    {{--</script>--}}
@endsection
