@extends("layout-1")


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
    
    <div class="sp"></div>
    <div class="container">
        <div class="fleft two-left-big">
            <div class="hotel-detail-title">
            <h1>{{$hotel->getProvince()->name}} {{$hotel->title}}</h1>
            </div>
            <div class="sp"></div>
            
            
            <div class="short-desc-view-on-detail">
            {{$hotel->getShortDesc()}}
            </div>
            
            
            <div class="sp"></div>
            <style>
            
            .sp-thumbnail-image-container  {
                width:170px;
            }
            .sp-thumbnail-image-container img {
                width:150px;
            }
            </style>
            
            <div style="border:solid 1px #e2e2e2">
            
    <script language="javascript" src="/js/jquery.sliderPro.min.js"></script>
    
    
    
    
    
<link rel="stylesheet" type="text/css" href="/css/slider/slider-pro.min.css" media="screen"/>
<script type="text/javascript">
	$( document ).ready(function( $ ) {
		$( '#example5' ).sliderPro({
			width: 740,
			height: 500,
			orientation: 'vertical',
			loop: false,
			arrows: true,
			buttons: false,
			thumbnailsPosition: 'right',
			thumbnailPointer: true,
			thumbnailWidth: 170,
			breakpoints: {
				800: {
					thumbnailsPosition: 'bottom',
					thumbnailWidth: 170,
					thumbnailHeight: 100
				},
				500: {
					thumbnailsPosition: 'bottom',
					thumbnailWidth: 120,
					thumbnailHeight: 50
				}
			}
		});
        $(".slider-pro").css("max-width","100%");
	});
</script>
            
        <div id="example5" class="slider-pro">
          <div class="sp-slides">
          
            @foreach($hotel->getImages() as $image)
            <div class="sp-slide">
                <img class="sp-image" src="css/images/blank.gif" data-src="{{$image->url}}" data-retina="{{$image->url}}"/>
                <div class="sp-caption"></div>
            </div>
            @endforeach
          </div>
  
        <div class="sp-thumbnails">

        @foreach($hotel->getImages() as $image)
    
            <div class="sp-thumbnail">
              <div class="sp-thumbnail-image-container"> <img class="sp-thumbnail-image" src="{{$image->url}}"/> </div>
              <div class="sp-thumbnail-text">
                <div class="sp-thumbnail-title"> {{$hotel->title}}</div>
                <div class="sp-thumbnail-description"></div>
              </div>
            </div>
            
            
            @endforeach
            
          </div>
        </div> 
         </div>
            
            
            
            
                <style>
                
                .hotel-hotel-list li,
                .hotel-hotel-list ul {
                    list-style:none;
                    margin:0;
                    padding:0;
                }
                .hotel-hotel-list div.img{
                    height:200px;
                    overflow: hidden;
                    border: solid 1px #e1e1e1;
                    text-align:center;
                }
                .hotel-hotel-list li .title{
                    padding:10px 0px;
                }
                .hotel-hotel-list li img{
                    width:99%;
                    
                }
                .hotel-hotel-list li{
                    float:left;
                    width:32%;
                    height:250px;
                    overflow:hidden;
                    margin-right:1%;
                    text-align:center;
                }
                
                </style>
            
            
            
            <div class="sp"></div>
            <div class="sp"></div>
            <div class="sp"></div>
            <div class="tab tab-section">
                <ul>
                    <li class="active" rel=".tab-1">酒店介绍</li>
                    <li rel=".tab-2">游记/攻略</li>
                    <li rel=".tab-3">附近景点</li>
                    <li rel=".tab-4">跟团游</li>
                </ul>
            </div>
            <div class="hotel-detail-tab-content tab-content tab-hotel" style="clear:both;border:solid 1px #e1e1e1;padding:10px;border-top:solid 2px #f33b7a">
                <div class="tab-1 tab-section-content">
                    {!!$hotel->description!!}
                </div>
                
                <div class="tab-2 tab-section-content hidden  hotel-hotel-list">
                 
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
                <div class="tab-3 tab-section-content hidden hotel-hotel-list">
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
                
                <div class="tab-4 tab-section-content hidden">
                 d
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
    
@endsection