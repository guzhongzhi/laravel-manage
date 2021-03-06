@extends("layout-1")

@section('meta')
<title>

{{$hotel->getProvince()->name}} - {{$hotel->title}} -  

{{$controller->getConfig()["site_name"]}}

</title>
<meta name="title" content="{{$controller->getConfig()['site_name']}}" />
<meta name="keywords" content="{{$hotel->getMetaKeywords()}}" />
<meta name="description" content="{{$hotel->getMetaDescription()}}" />
@endsection

@section("breadcrumb")
    <div class="sp"></div>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="/">首页</a></li>
            <li><a href="/hotel">酒店/住宿</a></li>
            <li><a href="{{url('/hotel/p'.$hotel->getProvince()->id.'.html')}}">{{$hotel->getProvince()->name}}</a></li>
            <li class="active">{{$hotel->title}}</li>
        </ol>
    </div>
@endsection
@section("content")
    @parent
    <script language="javascript" src="/js/jquery.sliderPro.min.js"></script>
    <script language="javascript" src="/skin/hotel/js/hotel.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/slider/slider-pro.min.css" media="screen"/>
    <link rel="stylesheet" type="text/css" href="/skin/hotel/css/hotel.css" media="screen"/>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=bTmOXFz6sEkPnLXGp07M6FOgvlUGPwfF"></script>
    <div class="sp"></div>
    <div class="container">
        <div class="fleft two-left-big">
            <div class="hotel-detail-title">
            <h1>{{$hotel->title}}</h1>
            <span class="star"><i><s style="width:{{$hotel->getStar()}}%;"></s></i><b>{{$hotel->rate}}</b>分</span>
            </div>
            <div class="sp"></div>
            
            
            <div class="short-desc-view-on-detail">
            {{$hotel->address}}
            </div>
            
            
            <div class="sp"></div>
            <div style="border:solid 1px #e2e2e2">
            
                <div id="example5" class="slider-pro">
                    <div class="sp-slides">

                    @foreach($hotel->getImages() as $image)
                    <div class="sp-slide">
                        <img class="sp-image" src="/skin/images/blank.gif" data-src="{{$image['src']}}" data-retina="{{$image['src']}}"/>
                        <div class="sp-caption"></div>
                    </div>
                    @endforeach
                    </div>

                    <div class="sp-thumbnails">

                    @foreach($hotel->getImages() as $image)
                        <div class="sp-thumbnail">
                          <div class="sp-thumbnail-image-container"> <img class="sp-thumbnail-image" src="{{$image['thumb']}}"/> </div>
                          <div class="sp-thumbnail-text">
                            <div class="sp-thumbnail-title"> {{$hotel->title}}</div>
                            <div class="sp-thumbnail-description"></div>
                          </div>
                        </div>
                        @endforeach

                    </div>
                </div>
             </div>

            <div class="sp"></div>
            <div class="sp"></div>
            <div class="sp"></div>
            <div class="tab tab-section">
                <ul>
                    <li class="active" rel=".tab-1">酒店介绍</li>
                    <li rel=".tab-2">酒店政策</li>
                    <li rel=".tab-3">设施服务</li>
                    <li rel=".tab-4">游记/攻略</li>
                    <li rel=".tab-5">附近景点</li>
                </ul>
            </div>
            <div class="hotel-detail-tab-content tab-content tab-hotel" style="clear:both;border:solid 1px #e1e1e1;padding:10px;border-top:solid 2px #f33b7a">
                <div class="tab-1 tab-section-content">
                    <div class="hotel_introduction_body">
                        {!!$hotel->description!!}
                        <div class="e10 clearfix">
                            <span class="f1 fleft">
                                <i></i><label>酒店地图</label>
                            </span>
                            <div id="allmap"></div>
                        </div>
                    </div>
                </div>

                <div class="tab-2  tab-section-content hidden  hotel-hotel-list">
                    <ul>
                        <div class="hotel_introduction_body">
                            {!!$hotel->policy!!}
                        </div>
                    </ul>
                    <div class="sp"></div>
                </div>

                <div class="tab-3  tab-section-content hidden  hotel-hotel-list">
                    <ul>
                        <div class="hotel_introduction_body">
                            {!!$hotel->service!!}
                        </div>
                    </ul>
                    <div class="sp"></div>
                </div>

                
                <div class="tab-4 tab-section-content hidden  hotel-hotel-list">
                 
                 <ul>
                 @foreach($travelNews as $sight)
                    <li class="hotel-hotel-list-item">
                    <a href="{{$sight->getTravelUrl()}}" title="{{$sight->title}}">
                    <div class="img"><img src="{{$sight->pic}}"/></div>
                    <div class="title">{{$sight->title}}</div>
                    <div class="sp"></div>
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="sp"></div>
                </div>
                <div class="tab-5 tab-section-content hidden hotel-hotel-list">
                <ul>
                 @foreach($sights as $sight)
                    <li class="hotel-hotel-list-item">
                    <a href="{{$sight->getSightUrl()}}" title="{{$sight->title}}">
                    <div class="img"><img src="{{$sight->pic}}"/></div>
                    <div class="title">{{$sight->title}}</div>
                    <div class="sp"></div>
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="sp"></div>
                </div>
                
            </div>
            
        </div>
        <script language="javascript">
        $(".tab-section li").click(function(){
            $(".tab-section-content").addClass("hidden");
            $(($(this).attr("rel"))).removeClass("hidden");
            
            $("li",this.parentNode).removeClass("active");
            $(this).addClass("active");
        });
        </script>
        
        <div class="fright two-right-small">
            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">周边推荐</div>
                <div class="sight-relation-right">
                
                <ul class="sight-relation">
                <li><span class="glyphicon glyphicon-question-sign"></span> <a href="/hotel/c{{$hotel->city_id}}.html">周边酒店</a></li>
                <li><span class="glyphicon glyphicon-warning-sign"></span> 注意事项</li>
                <li><span class="glyphicon glyphicon-flash"></span> 旅游佳季</li>
                <li><span class="glyphicon glyphicon-thumbs-up"></span> <a href="/food/c{{$hotel->city_id}}.html">特色美食</a></li>
                <li><span class="glyphicon glyphicon-screenshot"></span> <a href="/sight/c{{$hotel->city_id}}.html">景点介绍</a></li>
                <li><span class="glyphicon glyphicon-plane"></span> 交通工具</li>
                <li><span class="glyphicon glyphicon-tree-deciduous"></span> 风景图片</li>
                <li><span class="glyphicon glyphicon-fire"></span> <a href="/travel/c{{$hotel->city_id}}.html">旅游攻略</a></li>
                </ul>
                <div class="sp"></div>
                </div>
            </div>
            
            <div class="sp"></div><div class="sp"></div>
            <style>
            .sight-relation-hotels li {
                list-style:none;
            }
            </style>
            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">热门目的地</div>
                <div class="sight-relation-right">
                
                <ul class="sight-relation-hotels" style="padding:0px;">
                @foreach($recItems as $item) 
                    <li>
                    <div style="float:left;width:150px;overflow:hidden;height:100px;">
                        <a href="{{$item->gethotelUrl()}}"><img src="{{$item->pic}}" width="150"/></a>
                    </div>
                    <div style="float:right;width:calc(100% - 160px)">
                        <a href="{{$item->gethotelUrl()}}">{{$item->title}}</a>
                        <div style="height:65px;font-size:12px;overflow:hidden;color:#999">
                        {{$item->getShortDesc()}}
                        </div>
                    </div>
                    <div class="sp"></div>
                    </li>
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
        local.search("{{$mapCity}} {{$hotel->title}}");
    </script>
    
@endsection