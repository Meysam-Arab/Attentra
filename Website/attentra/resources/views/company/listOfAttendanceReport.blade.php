<!-- resources/views/aboutuses/index.blade.php -->

@extends('layouts.profile')

@section('style')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">

<link rel="stylesheet" type="text/css"
      href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">

<link rel="stylesheet" type="text/css"
      href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.dataTables.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@stop

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
<?php
$Company_name_index = '';
$count = null;
for ($index = 0; $index < session('CompanyCount'); $index++) {
    if (session('companiesId' . $index) == $company_id) {
        $Company_name_index = $index;
        break;
    }
}
?>
<div class="alert alert-info alert-dismissable fade in" style="text-align:center;display:block;margin-top:10px;">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
    <ul>
        <li>@lang('messages.lbl_ComponyEmployeeList'){{session('companiesName'.$Company_name_index)}}</li>
    </ul>
</div>
<!-- Bootstrap Boilerplate... -->
@if(count($errors) > 0)
<ul>
    @foreach($errors->all() as $error)
    <li>{{$error}}</li>
    @endforeach
</ul>
@endif

<div>
    <img src="{{ route('company.image', ['filename' =>$logoPath]) }}" style="width:100px;height:80px;"/>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="example" class="display nowrap full-width" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>@lang('messages.lbl_Name') <i class="fa fa-user" aria-hidden="true"></i></th>
                <th>@lang('messages.lbl_Family') <i class="fa fa-user" aria-hidden="true"></i></th>
                <th>@lang('messages.lbl_JobPosition') <span class="fa fa-user"></span></th>


            </tr>
            </thead>
            <tbody>
            @foreach($UserRepositories as $user)
            @if($user->user_type_id!=1)
            <tr>
                <th>{{$user->name}}</th>
                <th>{{$user->family}}</th>

                <?php
                $JobPosition = '';
                if ($user->user_type_id == "2") $JobPosition = trans('messages.lbl_midleManager');
                else if ($user->user_type_id == "3") $JobPosition = trans('messages.lbl_Employee');
                else $JobPosition = trans('messages.lbl_Manager');
                ?>

                <td>{{$JobPosition}}</td>


            </tr>
            @endif
            @endforeach
            </tbody>

        </table>
    </div>
</div>



@endsection

@section('script')
<script src="{{URL::to('style/profile/flipflop/js/flipclock.min.js')}}" type="text/javascript"></script>
<script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js" type="text/javascript"></script>
{{--//code.jquery.com/jquery-1.12.4.js--}}
{{--https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js--}}
<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js" type="text/javascript"></script>

<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js" type="text/javascript"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js" type="text/javascript"></script>

{{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js" type="text/javascript"></script>--}}

{{--<script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js" type="text/javascript"></script>--}}

<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js" type="text/javascript"></script>

<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js" type="text/javascript"></script>

<script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js" type="text/javascript"></script>



<script>


    $(document).ready(function () {
        $('#example').DataTable({
            "order": [[ 6, "asc" ]],
            dom: 'Bfrtip',

            buttons: [
                {
                    extend: 'print',
                    text: "@lang('messages.lbl_Print')",
                },
                {
                    extend: 'excel',
                    text: "@lang('messages.lbl_Excel')",
                },
                {
                    extend: 'copy',
                    text: "@lang('messages.lbl_Copy')",
                }
            ],
            "pageLength": 50,
            "language": {
                "lengthMenu": "@lang('messages.lbl_lengthMenu')",
                "zeroRecords": "@lang('messages.lbl_zeroRecords')",
                "info": "@lang('messages.lbl_info')",
                "infoEmpty": "@lang('messages.lbl_infoEmpty')",
                "infoFiltered": "@lang('messages.lbl_infoFiltered')",
                "paginate": {
                    "first": "@lang('messages.lbl_first')",
                    "last": "@lang('messages.lbl_last')",
                    "next": "@lang('messages.lbl_next')",
                    "previous": "@lang('messages.lbl_previous')"
                },
                "decimal": "@lang('messages.lbl_decimal')",
                "emptyTable": "@lang('messages.lbl_emptyTable')",
                "infoPostFix": "@lang('messages.lbl_infoPostFix')",
                "thousands": "@lang('messages.lbl_thousands')",
                "loadingRecords": "@lang('messages.lbl_loadingRecords')",
                "processing": "@lang('messages.lbl_processing')",
                "search": "@lang('messages.lbl_search')",
            },
            responsive: {
                details: true
            },
        });


        var table = $('#example').DataTable();

// #myInput is a <input type="text"> element
        $("input[aria-controls='example']").on( 'keyup', function () {
            table.search( this.value ).draw();
        } );

    });


</script>
@stop