@extends("layout-1")
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
        @foreach($provinces as $province)
        
            <li class="
            @if($provinceId == $province->id)
                active
            @endif
            "><a href="{{url('sight/'.$province->getSightUrlKey())}}">{{$province->name}}</a></li>
        @endforeach
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
                <div class="pic"><a href="{{$new->getSightUrl()}}"><img src="{{$new->pic}}"/></a></div>
                <div class="desc">
                    <p class="title"><a href="{{$new->getSightUrl()}}">{{$new->title}}</a></p>
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