@extends("layout-1")

@section('meta')
<title>

{{$travel->getProvince()->name}} - {{$travel->title}} -  

{{$controller->getConfig()["site_name"]}}

</title>
<meta name="title" content="{{$controller->getConfig()['site_name']}}" />
<meta name="keywords" content="{{$travel->getMetaKeywords()}}" />
<meta name="description" content="{{$travel->getMetaDescription()}}" />
@endsection

@section("content")
    @parent
    <link rel="stylesheet" href="/skin/travel/css/classic_travels_detail.v2.0.css">  
    <script src="/skin/travel/js/travel.js"></script>
    <div class="sp"></div>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="/">首页</a></li>
            <li><a href="/travel">游记</a></li>
            @if($city)
                <li><a href="{{url('/travel/p'.$province->id.'.html')}}">{{$province->name}}</a></li>
                <li><a href="{{url('/travel/c'.$city->id.'.html')}}">{{$city->name}}</a></li>
            @elseif($province)
                <li><a href="{{url('/travel/p'.$province->id.'.html')}}">{{$province->name}}</a></li>
            @endif
            <li class="active">{{$travel->title}}</li>
        </ol>
        <div class="sp"></div>
        <div class="gg930 mt20"></div>
   
        <div class="fleft two-left-big">
                <div class="ctd_controls cf" style="padding-left:0px;">
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
                        <!--<h3>发表于：{{$travel->created_at}}</h3>-->
                    </div>
                    {!!$travel->content!!}
                      

                    <div class="ctd_theend"></div>
                </div>
            
        </div>


        <div class="fright two-right-small">

            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">推荐景区</div>
                <div class="sight-relation-right">

                    <ul class="sight-relation">
                        @foreach(\App\Helper\TravelHelper::getNewsList($cityId, $provinceId, \App\Model\News::CATEGORY_ID_SIGHT, 10, 'recommand') as $itemSight)
                            <li><span class=""></span><a href="{{$itemSight->getSightUrl()}}">{{$itemSight->title}}</a></li>
                        @endforeach
                    </ul>
                    <div class="sp"></div>
                </div>
            </div>

            <div class="sp"></div>
            <div>
                <div style="padding:10px;background:#f33b7a;color:#FFF;font-weight:bold;">推荐游记</div>
                <div class="sight-relation-right">

                    <ul class="sight-relation">
                        @foreach(\App\Helper\TravelHelper::getNewsList($cityId, $provinceId, \App\Model\News::CATEGORY_ID_TRAVEL,10, 'recommand') as $itemList)
                            <li style="width:100%"><span class=""></span><a href="{{$itemList->getTravelUrl()}}" title="{{$itemList->title}}">{{\App\Helper\TravelHelper::utf8Substr($itemList->title, 0, 16)}}</a></li>
                        @endforeach
                    </ul>
                    <div class="sp"></div>
                </div>
            </div>

            <div class="sp"></div><div class="sp"></div>


        </div>
        
        <div class="sp"></div>
    </div>
    
@endsection
