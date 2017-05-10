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
    </style>
    <div class="container sight-home sight-home-province">
        <ul>
        @foreach($provinces as $province)
        
            <li>{{$province->name}} </li>
        @endforeach
        </ul>
        <div class="sp"></div>
        
    </div>
    <div class="container sight-home sight-home-news">
        <ul>
        @foreach($news as $new)
        
            <li>{{$new->title}} </li>
        @endforeach
        </ul>
        <div class="sp"></div>
        
        
    </div>
@endsection