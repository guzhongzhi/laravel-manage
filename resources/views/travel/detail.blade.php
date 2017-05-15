@extends("layout-1")
@section("content")
    @parent
    <link rel="stylesheet" href="/skin/travel/css/classic_travels_detail.v2.0.css">  
    <script src="/skin/travel/js/travel.js"></script>
    <div class="sp"></div>
    <div class="container">
   
        <div class="fleft two-left-big">
                <div class="ctd_controls cf">
                    <a class="fl" href="javascript:;" rel="nofollow" id="only_text" title="只看文字"><i></i>只看文字</a>
                    <span class="fr">
                       
                        <!--
                        <a id="TitlePDF" class="link_pdf  a_popup_login " href="javascript:;" title="下载PDF">
                            <i></i>下载PDF
                        </a>
                        
                        <a id="TitleFavourite" href="javascript:;" class="link_collect  a_popup_login " data-favouriteid="3442551" data-favouritecategory="1" title="一键收藏">
                            <i></i>收藏
                        </a> <a data-shareid="3442551" data-sharecategory="0" data-share-pic="https://youimg1.c-ctrip.com/target/10070g0000007t0uw7F31.jpg" data-share-url="" data-share-title="我刚刚在@携程攻略社区 发现一篇#携程精彩游记# 『北石：江西上饶灵山告诉我，原来石头也可以一柱擎天！』很有用，仿佛跟着作者去旅行，请戳>>>" class="link_share" href="javascript:;" title="一键分享" rel="nofollow">
                            <i></i>分享
                        </a>
                        <a class="link_comment " href="javascript:;" data-referencecategory="0" title="我要评论" rel="nofollow"><i></i>评论<span>1</span></a>
                        !-->
                        <a id="TitleLike" href="javascript:;" class="{{$likeClass}}" data-likeid="{{$travel->id}}" data-likecategory="0" title="喜欢就点击一下" rel="nofollow">
                            <i></i>喜欢<span id="like_{{$travel->id}}">{{$travel->like}}</span>
                        </a>  <a class="link_browse" href="javascript:;">
                            <i></i> 浏览 <span>{{$travel->click}}</span>
                        </a>
                    </span>
                </div>
                <div class="ctd_content">
                    <div class="ctd_content_controls cf">
                        <h2>{{$travel->title}}</h2>
                        <h3>发表于：{{$travel->created_at}}</h3>
                     
                        {!!$travel->content!!}
                      
                    </div>
                    <div class="ctd_theend">
                    </div>
                </div>
            
        </div>
       
        
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