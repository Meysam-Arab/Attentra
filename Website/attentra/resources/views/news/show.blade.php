@extends('layouts.miniMaster')
@section('content')

    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-lg-push-3 col-lg-pull-3">
                    <img style="width: 100%;height: 300px;"  src="{{ route('news.image', ['filename' => $news->news_guid]) }}" alt=""/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    {{$news->newsTitle}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php
                        echo(stripslashes($news->newsDes));
                    ?>
                </div>
                
            </div>

        </div>




    </div>
@endsection