@extends("layout-1")
@section("breadcrumb")
    <link rel="stylesheet" href="/skin/food/css/common_city.css">
    <div class="sp"></div>
    <div class="container">
        <ol class="breadcrumb">
          <li><a href="/">首页</a></li>
          <li><a href="/food">美食</a></li>
        @if($city)
            <li><a href="{{url('/food/p'.$province->id.'.html')}}">{{$province->name}}</a></li>
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
                "><a href="{{url('food/'.$city->getSightUrlKey($city))}}">{{$city->name}}</a></li>
            @endforeach
            
        @else
            @foreach($provinces as $province)
            
                <li class="
                @if($provinceId == $province->id)
                    active
                @endif
                "><a href="{{url('food/'.$province->getSightUrlKey())}}">{{$province->name}}</a></li>
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
        <div class="con_left" style="margin-right: 0px;"> 
            <div class="food_li" style="margin:0 auto;"> 
                <div class="t"> {{$currentTitleName}}美食 </div>
                <div style="background-color:#000000">
                    <div class="a" style="margin:0 auto;">  
                        @foreach($news as $new)
                            <a href="{{$new->getFoodUrl()}}">
                                <img src="{{$new->getPic()}}"> <div> <span>{{$new->title}}</span> <font> <i class="icon_huo"></i>热度 {{$new->like}}</font> <p>{{$new->getShortDesc()}}</p> </div>
                            </a>  
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sp"></div>
    <div class="container clearboth pagination-container">
        @include("tools.paginate")
    </div>
    
@endsection