@extends("layout-1")
@section("content")
    @parent
    
    <div class="sp"></div>
    <div class="container">
        <div class="fleft two-left-big">
            <div class="sight-detail-title">
            <h1>{{$sight->title}}</h1>
            </div>
            <div class="sp"></div>
            <div style="border:solid 1px #e2e2e2">
                <img style="height:400px"/>
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
                fdsa
                </div>
                
                <div class="tab-3 tab-section-content hidden">
                cccc
                </div>
                
                <div class="tab-4 tab-section-content hidden">
                eeee
                </div>
            </div>
        </div>
        <script language="javascript">
        $(".tab-section li").click(function(){
            $(".tab-section-content").hide();
            $(($(this).attr("rel"))).show();
            
            $("li",this.parentNode).removeClass("active");
            $(this).addClass("active");
        });
        </script>
        
        <div class="fright two-right-small">
            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">周边推荐</div>
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