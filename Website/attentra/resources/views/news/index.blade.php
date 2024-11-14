@extends('layouts.miniMaster')
@section('content')


    <div class="panel-body">
        <div class="container-fluid" style="padding: 0;margin: 0;">
            <div class="row">
                <?php
                    $init_index=0;
                    $last_index=0;
                    $all_page=ceil(count($newses)/12);
                    $current_page=$page_number;
                    if($current_page*12>count($newses)){
                        $last_index=count($newses);
                        $init_index=($current_page-1)*12;
                    }
                    else{
                        $last_index=$current_page*12;
                        $init_index=($current_page-1)*12;
                    }
                ?>
                @if(count($newses)==0)
                    <h1 class="alert alert-info">موردی یافت نشد</h1>
                @endif
        @for($index=$init_index;$index<$last_index;$index++)

                <div style="background-color: white;padding: 0;margin: 10px;border-radius: 5px;" class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <div class="zooomin">
                        <div  style="height:200px">
                            <a style="width: 100%;" href='/articles/show/{{$newses[$index]->news_id}}/{{$newses[$index]->news_guid}}'>
                            <img class="col-xs-12" style="width: 100%;
                                border-radius: 5px;
                                padding: 0;
                                height: 200px;
                                -o-object-fit: cover;
                                object-fit: cover;
                                -webkit-transition: .2s all;
                                transition: .2s all;"  src="{{ route('question.image', ['filename' => $newses[$index]->news_guid]) }}" alt=""/>
                            </a>
                        </div>
                        <div style="height:100px;padding-top: 15px;">
                            <a style="width: 100%;" href='/articles/show/{{$newses[$index]->news_id}}/{{$newses[$index]->news_guid}}'>
                                <div style="padding-right: 5px;padding-left: 5px;" >
                                    <?php
                                    echo $newses[$index]->newsTitle;
                                    ?>
                                </div>
                            </a>
                            <div style="padding-right: 5px;padding-left:5px;
                                    font-size: .8em;
                                    opacity: .7;
                                    line-height: 1.7;">
                                {{\Illuminate\Support\Str::words(strip_tags($newses[$index]->newsDes), 30, '. . . ')}}


                            </div>
                        </div>
                    </div>

                </div>


        @endfor



            </div>
            <div class="paginate">
                <ul class="pagination" style="display: block;margin: 0px auto">
                    @if($current_page==1)
                        <li class="disabled"><a href="#">«</a></li>
                    @else
                        <li><a href="/articles/{{$current_page-1}}">«</a></li>
                    @endif

                    @for($index=1;$index<=ceil(count($newses)/12);$index++)
                        @if($current_page==$index)
                            <li class="active"><a  href="#">{{$index}}</a></li>
                        @else
                            <li><a href="/articles/{{$index}}">{{$index}}</a></li>
                        @endif
                    @endfor
                    @if($current_page==ceil(count($newses)/12))
                        <li class="disabled"><a href="#">«</a></li>
                    @else
                        <li><a href="/articles/{{$current_page+1}}">»</a></li>
                    @endif

                </ul>
            </div>
        </div>
    </div>


@endsection