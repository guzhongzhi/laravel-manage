@extends("layout-1")


@section('meta')
<title>

{{$food->getProvince()->name}} - {{$food->title}} -  

{{$controller->getConfig()["site_name"]}}

</title>
<meta name="title" content="{{$controller->getConfig()['site_name']}}" />
<meta name="keywords" content="{{$food->getMetaKeywords()}}" />
<meta name="description" content="{{$food->getMetaDescription()}}" />
@endsection


@section("content")
    @parent
    <link rel="stylesheet" href="/skin/food/css/comm_v7.css">
    <link rel="stylesheet" href="/skin/food/css/food_detail.css">
    <script src="/skin/food/js/food.js"></script>
    <div class="sp"></div>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="/">首页</a></li>
            <li><a href="/food">美食</a></li>
            @if($city)
                <li><a href="{{url('/food/p'.$province->id.'.html')}}">{{$province->name}}</a></li>
                <li><a href="{{url('/food/c'.$city->id.'.html')}}">{{$city->name}}</a></li>
            @elseif($province)
                <li><a href="{{url('/food/p'.$province->id.'.html')}}">{{$province->name}}</a></li>
            @endif
            <li class="active">{{$food->title}}</li>
        </ol>

        <div class="gg930 mt20"></div>
        <div class="fleft two-left-big">
                <div class="ctd_controls cf">
                   
                    <span class="fr">
                       
                        
                    </span>
                </div>
                <div class="ctd_content">
                    <div class="ctd_content_controls cf">
                        <div class="side_main" style="width:100%">
                            <div class="produce_info">
                                <dl>
                                    <dt><a><img src="{{$food->getPic()}}" alt="{{$food->title}}" border="0" width="210" height="140"></a></dt>
                                    <dd>
                                        <div class="title">
                                            <h1>{{$food->title}}</h1>
                                            <span class="fr" style="margin:0;">
                                            <a id="TitleFoodLike" href="javascript:;" class="{{$likeClass}}" data-likeid="{{$food->id}}" data-likecategory="0" title="喜欢就点击一下" rel="nofollow">
                                                <i></i>喜欢<span  style="margin:0;" id="food_like_{{$food->id}}">{{$food->like}}</span>
                                            </a>
                                            </span>
                                        
                                        </div>{!!$food->content!!}
                                    </dd>
                                </dl>
                            </div>
                        
                        
                            <div class="gg930 mt20"></div>
                            @if($food->getStores())
                            <div class="box_con mt20">
                                <div class="tit"><strong>哪里吃{{$food->title}}</strong></div>
                                <div class="txt_tw">
                                    <ul>
                                        @foreach($food->getStores() as $store)
                                        <li><a href="/store/d-{{$store->id}}.html"><img src="{{$store->getPic()}}" width="210" height="140" alt="{{$store->title}}"><div class="text_con"><strong>{{$store->title}}</strong></div></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div> 
                      
                    </div>
                    <div class="ctd_theend">
                    </div>
                </div>
            
        </div>


        <div class="fright two-right-small">
            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">推荐美食</div>
                <div class="sight-relation-right">

                    <ul class="sight-relation">
                        @foreach(\App\Helper\TravelHelper::getRandFoodList($cityId, $provinceId, 10) as $itemFood)
                            <li><span class=""></span><a href="/food/d-{{$itemFood->id}}.html">{{$itemFood->title}}</a></li>
                        @endforeach
                    </ul>
                    <div class="sp"></div>
                </div>
            </div>

            <div class="sp"></div><div class="sp"></div>

            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">推荐景区</div>
                <div class="sight-relation-right">

                    <ul class="sight-relation">
                        @foreach(\App\Helper\TravelHelper::getNewsList($cityId, $provinceId, \App\Model\News::CATEGORY_ID_SIGHT, 10) as $itemSight)
                            <li><span class=""></span><a href="/sight/s-{{$itemSight->id}}.html">{{$itemSight->title}}</a></li>
                        @endforeach
                    </ul>
                    <div class="sp"></div>
                </div>
            </div>
        </div>
        
        <div class="sp"></div>
    </div>
    
@endsection