@extends("layout-1")


@section('meta')
<title>

{{$store->getProvince()->name}} -  
{{$store->title}} -  

{{$controller->getConfig()["site_name"]}}

</title>
<meta name="title" content="{{$controller->getConfig()['site_name']}}" />
<meta name="keywords" content="{{$store->getMetaKeywords()}}" />
<meta name="description" content="{{$store->getMetaDescription()}}" />
@endsection


@section("content")
    @parent
    <link rel="stylesheet" href="/skin/food/css/comm_v7.css">
    <link rel="stylesheet" href="/skin/food/css/common_city.css">
    <link rel="stylesheet" href="/skin/food/css/food_detail.css">
    <script src="/skin/food/js/food.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=bTmOXFz6sEkPnLXGp07M6FOgvlUGPwfF"></script>
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
            <li class="active">{{$store->title}}</li>
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
                                    <dt><a><img src="{{$store->getPic()}}" alt="{{$store->title}}" border="0" width="210" height="140"></a></dt>
                                    <dd>
                                        <div class="title">
                                            <h1>{{$store->title}}</h1>
                                            <span class="star"><i><s style="width:{{$store->getStar()}}%;"></s></i><b>{{$store->rate}}</b>分</span>
                                            <span class="fr" style="margin:0;">
                                            <a id="TitleStoreLike" href="javascript:;" class="{{$likeClass}}" data-likeid="{{$store->id}}" data-likecategory="0" title="喜欢就点击一下" rel="nofollow">
                                                <i></i>喜欢<span  style="margin:0;" id="store_like_{{$store->id}}">{{$store->like}}</span>
                                            </a>
                                            </span>

                                        </div>{!!$store->content!!}
                                    </dd>
                                </dl>
                            </div>


                            <div class="gg930 mt20"></div>
                            <div class="box_con mt20">
                                <div class="tit"><strong>餐厅简介</strong></div>
                                <div class="produce_con">
                                    {!!$store->description!!}

                                </div>
                            </div>
                            
                            <div class="gg930 mt20"></div>
                                <div class="book_imgli"><div class="top"><div class="tab j_tab"><a href="javascript:;" class="on" id="recommand_travel">推荐攻略</a><a href="javascript:;" id="new_travel">最新发布</a></div><a href="/travel/p{{$provinceId}}.html" target="_blank" class="more">更多旅游攻略&gt;</a></div>

                                    <ul class="j_tab_con recommand_travel" style="display: block;">
                                        @foreach(\App\Helper\TravelHelper::getRandTravelList($cityId, $provinceId, 10, 'recommand') AS $itemTravel)
                                            <li><div class="biaoqian"><i class="icon_bq1">推荐</i></div><a href="{{$itemTravel->getTravelUrl()}}" target="_blank"><img src="{{$itemTravel->pic}}"></a><div class="txt"><div class="t"><a href="{{$itemTravel->getTravelUrl()}}" target="_blank">{{$itemTravel->title}}</a></div><div class="time">发表于：{{$itemTravel->created_at}}</div><a href="{{$itemTravel->getTravelUrl()}}" target="_blank"><span>{{$itemTravel->getShortDesc(80)}}</span></a></div><div class="i"><div><i class="icon_kan"></i>{{$itemTravel->click}}</div></div></li>
                                        @endforeach
                                    </ul>

                                    <ul class="j_tab_con new_travel" style="display: none;">
                                        @foreach(\App\Helper\TravelHelper::getRandTravelList($cityId, $provinceId, 10, 'new') AS $itemTravel)
                                            <li><div class="biaoqian"><i class="icon_bq1">最新</i></div><a href="{{$itemTravel->getTravelUrl()}}" target="_blank"><img src="{{$itemTravel->pic}}"></a><div class="txt"><div class="t"><a href="{{$itemTravel->getTravelUrl()}}" target="_blank">{{$itemTravel->title}}</a></div><div class="time">发表于：{{$itemTravel->created_at}}</div><a href="{{$itemTravel->getTravelUrl()}}" target="_blank"><span>{{$itemTravel->getShortDesc(80)}}</span></a></div><div class="i"><div><i class="icon_kan"></i>{{$itemTravel->click}}</div></div></li>
                                        @endforeach
                                    </ul>

                                </div>

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
                        @foreach(\App\Helper\TravelHelper::getRandSightList($cityId, $provinceId, 10) as $itemSight)
                            <li><span class=""></span><a href="/sight/s-{{$itemSight->id}}.html">{{$itemSight->title}}</a></li>
                        @endforeach
                    </ul>
                <div class="sp"></div>
                </div>
            </div>
        </div>

        <div class="sp"></div>
    </div>
    <script type="text/javascript">
        // 百度地图API功能
        var map = new BMap.Map("allmap");
        map.centerAndZoom(new BMap.Point(116.404, 39.915), 11);
        var local = new BMap.LocalSearch(map, {
            renderOptions:{map: map}
        });
        local.search("{{$mapCity}} {{$store->title}}");
    </script>

@endsection



