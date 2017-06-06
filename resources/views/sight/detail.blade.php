@extends("layout-1")

@section('meta')
<title>

{{$sight->getProvince()->name}} - {{$sight->title}} -  

{{$controller->getConfig()["site_name"]}}

</title>
<meta name="title" content="{{$controller->getConfig()['site_name']}}" />
<meta name="keywords" content="{{$sight->getMetaKeywords()}}" />
<meta name="description" content="{{$sight->getMetaDescription()}}" />
@endsection

@section("content")
    @parent
    
    <div class="sp"></div>
    <div class="container">
        <div class="fleft two-left-big">
            <div class="sight-detail-title">
                <h1>{{$sight->title}}</h1>
                <span class="star"><i><s style="width:{{$sight->getStar()}}%;"></s></i><b>{{$sight->rate}}</b>分</span>
            </div>
            <div class="sp"></div>
            <div style="border:solid 1px #e2e2e2">
            <script language="javascript" src="/js/jquery.sliderPro.min.js"></script>
            <script language="javascript" src="/skin/sight/js/sight.js"></script>
            <link rel="stylesheet" type="text/css" href="/skin/sight/css/sight.css" media="screen"/>
            <link rel="stylesheet" type="text/css" href="/css/slider/slider-pro.min.css" media="screen"/>
            
            <div id="example5" class="slider-pro">
              <div class="sp-slides">

                @foreach($sight->getImages() as $image)
                <div class="sp-slide">
                    <img class="sp-image" src="/skin/images/blank.gif" data-src="{{$image['src']}}" data-retina="{{$image['src']}}"/>
                    <div class="sp-caption"></div>
                </div>
                @endforeach
              </div>

            <div class="sp-thumbnails">

            @foreach($sight->getImages() as $image)

                <div class="sp-thumbnail">
                    <div class="sp-thumbnail-image-container"> <img class="sp-thumbnail-image" src="{{$image['thumb']}}"/> </div>
                  <div class="sp-thumbnail-text">
                    <div class="sp-thumbnail-title"> {{$sight->title}}</div>
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
                    <li class="active" rel=".tab-1">景点介绍</li>
                    <li rel=".tab-2">游记/攻略</li>
                    <li rel=".tab-3">酒店</li>
                    <li rel=".tab-4">交通</li>
                </ul>
            </div>
            <div class="sight-detail-tab-content tab-content tab-sight" style="clear:both;border:solid 1px #e1e1e1;padding:10px;border-top:solid 2px #f33b7a">
                <div class="tab-1 tab-section-content">
                    {!!$sight->content!!}
                </div>
                
                <div class="tab-2 tab-section-content hidden sight-hotel-list">
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
                <div class="tab-3 tab-section-content hidden sight-hotel-list">
                <ul>
                 @foreach($hotels as $hotel)
                    <li class="sight-hotel-list-item">
                    <a href="{{$hotel->getUrl()}}" title="{{$hotel->title}}">
                    <div class="img"><img src="{{$hotel->pic}}"/></div>
                    <div class="title">{{$hotel->title}}</div>
                    <div class="sp"></div>
                    </a>
                    </li>
                @endforeach
                </ul>
                <div class="sp"></div>
                </div>
                <div class="tab-4 tab-section-content hidden sight-hotel-list">
                    {!!$sight->traffic_info!!}{{$sight->traffic_info}}
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
                <li><span class="glyphicon glyphicon-question-sign"></span> <a href="/sight/c{{$sight->city_id}}.html">周边景点</a></li>
                <li><span class="glyphicon glyphicon-warning-sign"></span> 注意事项</li>
                <li><span class="glyphicon glyphicon-flash"></span> 旅游佳季</li>
                <li><span class="glyphicon glyphicon-thumbs-up"></span> <a href="/food/c{{$sight->city_id}}.html">特色美食</a></li>
                <li><span class="glyphicon glyphicon-screenshot"></span> <a href="/hotel/c{{$sight->city_id}}.html">酒店住宿</a></li>
                <li><span class="glyphicon glyphicon-plane"></span> 交通工具</li>
                <li><span class="glyphicon glyphicon-tree-deciduous"></span> 风景图片</li>
                <li><span class="glyphicon glyphicon-fire"></span> <a href="/travel/c{{$sight->city_id}}.html">旅游攻略</a></li>
                </ul>
                <div class="sp"></div>
                </div>
            </div>
            
            <div class="sp"></div><div class="sp"></div>
            
            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">热门目的地</div>
                <div class="sight-relation-right">
                
                <ul class="sight-relation-sights" style="padding:0px;">
                @foreach($recItems as $item) 
                    <li>
                    <div style="float:left;width:150px;overflow:hidden;height:100px;">
                        <a href="{{$item->getSightUrl()}}"><img src="{{$item->pic}}" width="150"/></a>
                    </div>
                    <div style="float:right;width:calc(100% - 160px)">
                        <a href="{{$item->getSightUrl()}}">{{$item->title}}</a>
                        <div style="height:65px;font-size:12px;overflow:hidden;color:#999">
                        {{$item->getShortDesc()}}
                        </div>
                    </div>
                    </li>
                @endforeach
                
                </ul>
                <div class="sp"></div>
                </div>
            </div>
        </div>
        
        <div class="sp"></div>
    </div>
    
@endsection