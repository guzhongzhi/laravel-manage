@extends("layout-1")

@section('meta')
<title>

@if($city)
    {{$province->name}} - {{$city->name}} - 
@elseif($province)
    {{$province->name}} - 
@endif
游记列表 - 
{{$controller->getConfig()["site_name"]}}

</title>
<meta name="title" content="{{$controller->getConfig()['site_name']}}" />
<meta name="keywords" content="{{$controller->getConfig()['site_keywords']}}" />
<meta name="description" content="{{$controller->getConfig()['site_description']}}" />
@show
@section("breadcrumb")
    <div class="sp"></div>
    <div class="container">
        <ol class="breadcrumb">
          <li><a href="/">首页</a></li>
          <li><a href="/travel">游记</a></li>
        @if($city)
            <li><a href="{{url('/travel/p'.$province->id.'.html')}}">{{$province->name}}</a></li>
            <li class="active">{{$city->name}}</li>
        @elseif($province)
            <li class="active">{{$province->name}}</li>
        @endif
        </ol>
    </div>
@endsection
@section("content")
    @parent
    <div class="sp"></div>
    <style>
    .sight-home ul,
    .sight-home li {
        list-style:none;
        padding:0;
        margin:0;
    }
    .sight-home-province li.active {
        background:#e1e1e1;
    }
    .sight-home-province  ul{
        margin:10px;
    }
    .sight-home-province  {
        border:solid 1px #e1e1e1;
    }
    .sight-home-province li {
        float:left;
        width:18%;
        padding:10px 0px;
        text-align:left;
        margin-right:10px;
    }
    
    .sight-home-news li div.pic img{
        width:150px;
        max-height:130px;
    }
    .sight-home-news li div.pic {
        float:left;
        width:150px;
        border:solid 1px #e1e1e1;
        display:table-sell;
        vertical-align:middle;
        height: 130px;
    }
    .sight-home-news li div.desc {
        float:left;
        width:calc(100% - 170px);
        margin-left:10px;
    }
    .sight-home-news li .desc .title{
        padding:0;
        margin:0;
    }
    .sight-home-news li .desc .short_desc{
        color:#999;
        padding:0;
        margin:0;
        padding-top:20px;
        line-height:26px;
        font-size:12px;
    }
    .sight-home-news li {
        
    }
    </style>
    <div class="container sight-home sight-home-province">
        <ul>
        @if($provinceId)
            
            @foreach($cities as $city)
                <li class="
                @if($cityId == $city->id)
                    active
                @endif
                "><a href="{{url('travel/'.$city->getSightUrlKey($city))}}">{{$city->name}}</a></li>
            @endforeach
            
        @else
            @foreach($provinces as $province)
            
                <li class="
                @if($provinceId == $province->id)
                    active
                @endif
                "><a href="{{url('travel/'.$province->getSightUrlKey($city))}}">{{$province->name}}</a></li>
            @endforeach
        
        @endif
        </ul>
        <div class="sp"></div>
        
    </div>
    

    <div class="sp"></div>
    <div class="container clearboth pagination-container">
        @include("tools.paginate")
    </div>
    
        
    <div class="sp"></div>
    <div class="container sight-home sight-home-news">
        <ul>
        @foreach($news as $new)
            <li>
                <div class="pic"><a href="{{$new->getTravelUrl()}}"><img src="{{$new->pic}}"/></a></div>
                <div class="desc">
                    <p class="title"><a href="{{$new->getTravelUrl()}}">{{$new->title}}</a></p>
                    <p class="short_desc">{{$new->getShortDesc()}}</p>
                </div>
                <div class="sp"></div>
            </li>
            <li><div class="sp"></div></li>
        @endforeach
        </ul>
        <div class="sp"></div>
        
    </div>
    
    <div class="sp"></div>
    <div class="container clearboth pagination-container">
        @include("tools.paginate")
    </div>
    
@endsection