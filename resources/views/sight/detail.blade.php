@extends("layout-1")
@section("content")
    @parent
    
    <div class="sp"></div>
    <div class="container">
        <div class="fleft two-left-big">
            <div class="sight-detail-title">
            <h1>{{$sight->getProvince()->name}} {{$sight->title}}</h1>
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
  
    @foreach($sight->getImages() as $image)
    <div class="sp-slide">
        <img class="sp-image" src="css/images/blank.gif" data-src="{{$image->url}}" data-retina="{{$image->url}}"/>
        <div class="sp-caption"></div>
    </div>
    @endforeach
  </div>
  
        <div class="sp-thumbnails">

        @foreach($sight->getImages() as $image)
    
            <div class="sp-thumbnail">
              <div class="sp-thumbnail-image-container"> <img class="sp-thumbnail-image" src="{{$image->url}}"/> </div>
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
            <div class="short-desc-view-on-detail">
            {{$sight->getShortDesc()}}
            </div>
            <div class="sp"></div>
            <div class="sp"></div>
            <div class="sp"></div>
            <div class="tab tab-section">
                <ul>
                    <li class="active" rel=".tab-1">景点介绍</li>
                    <li rel=".tab-2">游记/攻略</li>
                    <li rel=".tab-3">交通酒店</li>
                    <li rel=".tab-4">跟团游</li>
                </ul>
            </div>
            <div class="sight-detail-tab-content tab-content tab-sight" style="clear:both;border:solid 1px #e1e1e1;padding:10px;border-top:solid 2px #f33b7a">
                <div class="tab-1 tab-section-content">
                    {!!$sight->content!!}
                </div>
                
                <div class="tab-2 tab-section-content hidden">
                 e
                </div>
                
                <div class="tab-3 tab-section-content hidden">
                 f
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
                <li><span class="glyphicon glyphicon-question-sign"></span> 周边景点</li>
                <li><span class="glyphicon glyphicon-warning-sign"></span> 注意事项</li>
                <li><span class="glyphicon glyphicon-flash"></span> 旅游佳季</li>
                <li><span class="glyphicon glyphicon-thumbs-up"></span> 特色美食</li>
                <li><span class="glyphicon glyphicon-screenshot"></span> 酒店住宿</li>
                <li><span class="glyphicon glyphicon-plane"></span> 交通工具</li>
                <li><span class="glyphicon glyphicon-tree-deciduous"></span> 风景图片</li>
                <li><span class="glyphicon glyphicon-fire"></span> 旅游攻略</li>
                </ul>
                <div class="sp"></div>
                </div>
            </div>
            
            <div class="sp"></div><div class="sp"></div>
            
            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">热门目的地</div>
                <div class="sight-relation-right">
                
                <ul class="sight-relation">
                <li>周边景点</li>
                <li>注意事项</li>
                <li>旅游佳季</li>
                <li>特色美食</li>
                <li>酒店住宿</li>
                <li>交通工具</li>
                <li>风景图处</li>
                <li>旅游攻略</li>
                </ul>
                <div class="sp"></div>
                </div>
            </div>
        </div>
        
        <div class="sp"></div>
    </div>
    
@endsection