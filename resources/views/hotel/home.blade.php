@extends("layout-1")

@section("breadcrumb")
    <div class="sp"></div>
    <div class="container">
        <ol class="breadcrumb">
          <li><a href="/">首页</a></li>
          <li><a href="/hotel">酒店/住宿</a></li>
        @if($city)
            <li><a href="{{url('/hotel/p'.$province->id.'.html')}}">{{$province->name}}</a></li>
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
    .hotel-home ul,
    .hotel-home li {
        list-style:none;
        padding:0;
        margin:0;
    }
    .hotel-home-province li.active a {
        color: #a70303 !important;
        font-weight: bold;
    }
    .hotel-home-province  ul{
        margin:10px;
    }
    .hotel-home-province  {
        border:solid 1px #e1e1e1;
    }
    .hotel-home-province li {
        float:left;
        width:18%;
        padding:10px 0px;
        text-align:left;
        margin-right:10px;
    }
    
    .hotel-home-news li div.pic img{
        width:150px;
        max-height:130px;
    }
    .hotel-home-news li div.pic {
        float:left;
        width:150px;
        border:solid 1px #e1e1e1;
        display:table-sell;
        vertical-align:middle;
        height: 130px;
    }
    .hotel-home-news li div.desc {
        float:left;
        width:calc(100% - 170px);
        margin-left:10px;
    }
    .hotel-home-news li .desc .title{
        padding:0;
        margin:0;
    }
    .hotel-home-news li .desc .short_desc{
        color:#999;
        padding:0;
        margin:0;
        padding-top:20px;
        line-height:26px;
        font-size:12px;
    }
    .hotel-home-news li {
        
    }
    </style>
    
    <div class="container hotel-home hotel-home-province">
        <ul>
        @if($provinceId)
            
            @foreach($cities as $city)
                <li class="
                @if($cityId == $city->id)
                    active
                @endif
                "><a href="{{url('hotel/'.$city->getHotelUrlKey($city))}}">{{$city->name}}</a></li>
            @endforeach
            
        @else
            @foreach($provinces as $province)
            
                <li class="
                @if($provinceId == $province->id)
                    active
                @endif
                "><a href="{{url('hotel/'.$province->getHotelUrlKey($city))}}">{{$province->name}}</a></li>
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
    <div class="container hotel-home hotel-home-news">
        <ul>
        @foreach($news as $new)
            <li>
                <div class="pic"><a href="{{$new->getHotelUrl()}}"><img src="{{$new->getPic()}}"/></a></div>
                <div class="desc">
                    <p class="title"><a href="{{$new->gethotelUrl()}}">{{$new->title}}</a></p>
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