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
    .sight-home-province li {
        float:left;
        width:18%;
        padding:10px 0px;
        text-align:left;
    }
    
    .sight-home-news li div.pic {
        float:left;
        width:200px;
        border:solid 1px red;
        height: 200px;
    }
    .sight-home-news li div.desc {
        float:left;
        width:calc(100% - 220px);
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
        
            <li><a href="{{url('sight/'.$province->getUrlKey())}}">{{$province->name}}</a></li>
        @endforeach
        </ul>
        <div class="sp"></div>
        
    </div>
    <div class="container sight-home sight-home-news">
        <ul>
        @foreach($news as $new)
            <li>
                <div class="pic"><img src="{{$new->pic}}"/></div>
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
@endsection