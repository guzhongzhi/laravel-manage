@extends("layout-1")
@section("content")
    @parent
    
    <style>
    .nav-tab  {
        margin:0px;
        padding:0px;
    }
    .nav-tab  li,
    .nav-tab  ul {
        list-style:none;
        padding:0px;
        margin:0px;
    }
    .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover,
    .nav-tab  li a.active,
    .nav-tab  li a{
        border:none;
    }
    .nav-tab  li.active {
        font-weight:bold;
    }
    .nav-tab  li {
        padding:10px;
        float:left;
        margin-right:10px;
    }
    .nav-tabs {
        border:none;
    }
    
    .tab-content {
        clear:both;
    }
    .tab-content {
        padding-top:10px;
        margin-top:2px;
        border-top:solid 1px #e1e1e1;
    }
    .index-left-box {
        padding:1px;
        margin-bottom:10px;
        
    }
    .index-left-box .index-left-box-content {
        font-size:12px;
    }
    .index-left-box .title {
        padding:10px 0px;
    }
    .index-sight-hot-ul,
    .index-sight-hot-ul li{
        margin:0px;
        padding:0px;
        list-style:none;
    }
    .index-sight-hot-ul li .title{
        padding:10px 0px;
        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
    }
    .index-sight-hot-ul li {
        float:left;
        width:23%;
        margin-left:2%;
        padding:10 0px;
    }
    .index-sight-hot-u2l,
    
    .index-sight-hot-u2l li {
        padding:0;
        margin:0;
        list-style:none;
    }
    .index-sight-hot-u2l li {
        float:left;
        width:44%;
        padding:6px 5px;
        height:26px;
        overflow:hidden;
    }
    .index-left-box-content {
        line-height:26px;
    }
    .index-content-tab {
        min-height:545px;
    }
    </style>
    <div class="sp"></div>
    <div class="container">
        <div class="content index-content-tab" style="border:solid 1px #e1e1e1;">
            <div class="title" style="background:#fff3f3;padding:10px;font-size:16px;border-bottom:solid 1px #e1e1e1">热门景点</div>
            <div class="quote" style="padding:10px;">
                <div class="row">
                  <div class="col-md-3">
                    <div class=" index-left-box">
                        <div class="title">热门城市</div>
                        <div class="index-left-box-content">
                        @foreach($controller->getCities() as $city)
                            <a href="{{$city->getSightUrl()}}">{{$city->name}}</a> &nbsp;
                        @endforeach
                            
                        </div>
                        
                    </div>
                    
                    <div class=" index-left-box">
                        <div class="title">热门景点</div>
                        <div class="index-left-box-content">
                            <ul class="index-sight-hot-u2l">
                            @foreach($controller->getSights(0,16) as $s)
                                <li>
                                    <a href="{{$s->getSightUrl()}}">{{$s->title}}</a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    
                  </div>
                  <div class="col-md-9">
                  
                  <div>

                      <!-- Nav tabs -->
                      <ul class="nav-tab nav-tabs" id="sight-city-tab" role="tablist">
                        @foreach($controller->getCities() as $city)
                            <li role="presentation"><a href="#sight-tab-{{$city->id}}" aria-controls="sight-tab-{{$city->id}}" role="tab" data-toggle="tab">{{$city->name}}</a></li>
                        @endforeach
                        
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content">
                        @foreach($sight["cities"] as $city)
                        <div role="tabpanel" class="tab-pane" id="sight-tab-{{$city->id}}">
                        <ul class="index-sight-hot-ul">
                        @foreach($controller->getSights($city->id) as $sight)
                            <li><a href="{{$sight->getSightUrl()}}" title="{{$sight->title}}">
                                <div><img class="img_none" src="/img/none.gif" datasrc="{{$sight->getHomePic()}}" style="width:99%;height:150px"/></div>
                                <div class="title">{{$sight->title}}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        <div class="sp"></div>
                        </div>
                        @endforeach
                      </div>
                      
                      <script language="javascript">
                        $($("#sight-city-tab a")[0]).trigger("click");
                      </script>
                    </div>
                  
                  
                  </div>
                
                </div>
                
            </div>
        </div>
    </div>
    
    
    
    <div class="sp"></div>
    <div class="sp"></div>
    <div class="sp"></div>
    <div class="container">
        <div class="content index-content-tab" style="border:solid 1px #e1e1e1;">
            <div class="title" style="background:#fff3f3;padding:10px;font-size:16px;border-bottom:solid 1px #e1e1e1">热门酒店</div>
            <div class="quote" style="padding:10px;">
                <div class="row">
                  <div class="col-md-3">
                    <div class=" index-left-box">
                        <div class="title">热门城市</div>
                        <div class="index-left-box-content">
                        @foreach($controller->getCities() as $city)
                            <a href="{{$city->getHotelUrl()}}">{{$city->name}}</a> &nbsp;
                        @endforeach
                            
                        </div>
                        
                    </div>
                    
                    <div class=" index-left-box">
                        <div class="title">热门景点</div>
                        <div class="index-left-box-content">
                            <ul class="index-sight-hot-u2l">
                            @foreach($controller->getHotels(0,16) as $s)
                                <li style='overflow:hidden'>
                                    <a href="{{$s->getHotelUrl()}}">{{$s->title}}</a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    
                  </div>
                  <div class="col-md-9">
                  
                  <div>

                      <!-- Nav tabs -->
                      <ul class="nav-tab nav-tabs" id="hotel-city-tab" role="tablist">
                        @foreach($controller->getCities() as $city)
                            <li role="presentation"><a href="#hotel-tab-{{$city->id}}" aria-controls="hotel-tab-{{$city->id}}" role="tab" data-toggle="tab">{{$city->name}}</a></li>
                        @endforeach
                        
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content">
                        @foreach($controller->getCities() as $city)
                        <div role="tabpanel" class="tab-pane" id="hotel-tab-{{$city->id}}">
                        <ul class="index-sight-hot-ul">
                        @foreach($controller->getHotels($city->id) as $sight)
                            <li><a href="{{$sight->getHotelUrl()}}" title="{{$sight->title}}">
                                <div><img class="img_none" src="/img/none.gif" datasrc="{{$sight->getHomePic()}}" style="width:99%;height:150px"/></div>
                                <div class="title">{{$sight->title}}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        <div class="sp"></div>
                        </div>
                        @endforeach
                      </div>
                      
                      <script language="javascript">
                        $($("#hotel-city-tab a")[0]).trigger("click");
                      </script>
                    </div>
                  
                  
                  </div>
                
                </div>
                
            </div>
        </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    <div class="sp"></div>
    <div class="sp"></div>
    <div class="sp"></div>
    <div class="container">
        <div class="content index-content-tab" style="border:solid 1px #e1e1e1;">
            <div class="title" style="background:#fff3f3;padding:10px;font-size:16px;border-bottom:solid 1px #e1e1e1">最新游记</div>
            <div class="quote" style="padding:10px;">
                <div class="row">
                  <div class="col-md-3">
                    <div class=" index-left-box">
                        <div class="title">热门城市</div>
                        <div class="index-left-box-content">
                        @foreach($controller->getCities() as $city)
                            <a href="{{$city->getTravelUrl()}}">{{$city->name}}</a> &nbsp;
                        @endforeach
                            
                        </div>
                        
                    </div>
                    
                    <div class=" index-left-box">
                        <div class="title">最新游记</div>
                        <div class="index-left-box-content">
                            <ul class="index-sight-hot-u2l">
                            @foreach($controller->getTravels(0,16) as $s)
                                <li style='overflow:hidden'>
                                    <a href="{{$s->getTravelUrl()}}">{{$s->title}}</a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    
                  </div>
                  <div class="col-md-9">
                  
                  <div>

                      <!-- Nav tabs -->
                      <ul class="nav-tab nav-tabs" id="travel-city-tab" role="tablist">
                        @foreach($controller->getCities() as $city)
                            <li role="presentation"><a href="#travel-tab-{{$city->id}}" aria-controls="travel-tab-{{$city->id}}" role="tab" data-toggle="tab">{{$city->name}}</a></li>
                        @endforeach
                        
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content">
                        @foreach($controller->getCities() as $city)
                        <div role="tabpanel" class="tab-pane" id="travel-tab-{{$city->id}}">
                        <ul class="index-sight-hot-ul">
                        @foreach($controller->getTravels($city->id) as $sight)
							<li><a href="{{$sight->getTravelUrl()}}" title="{{$sight->title}}">
                                <div><img class="img_none" src="/img/none.gif" datasrc="{{$sight->getHomePic()}}" style="width:99%;height:150px"/></div>
                                <div class="title">{{$sight->title}}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        <div class="sp"></div>
                        </div>
                        @endforeach
                      </div>
                      
                      <script language="javascript">
                        $($("#travel-city-tab a")[0]).trigger("click");
                      </script>
                    </div>
                  
                  
                  </div>
                
                </div>
                
            </div>
        </div>
    </div>
    
    
    
    
    
    <div class="sp"></div>
    <div class="sp"></div>
    <div class="sp"></div>
    <div class="container">
        <div class="content index-content-tab" style="border:solid 1px #e1e1e1;">
            <div class="title" style="background:#fff3f3;padding:10px;font-size:16px;border-bottom:solid 1px #e1e1e1">美食</div>
            <div class="quote" style="padding:10px;">
                <div class="row">
                  <div class="col-md-3">
                    <div class=" index-left-box">
                        <div class="title">热门城市</div>
                        <div class="index-left-box-content">
                        @foreach($controller->getCities() as $city)
                            <a href="{{$city->getFoodUrl()}}">{{$city->name}}</a> &nbsp;
                        @endforeach
                            
                        </div>
                        
                    </div>
                    
                    <div class=" index-left-box">
                        <div class="title">推荐美食</div>
                        <div class="index-left-box-content">
                            <ul class="index-sight-hot-u2l">
                            @foreach($controller->getFoods(0,16) as $s)
                                <li style='overflow:hidden'>
                                    <a href="{{$s->getFoodUrl()}}">{{$s->title}}</a>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    
                  </div>
                  <div class="col-md-9">
                  
                  <div>

                      <!-- Nav tabs -->
                      <ul class="nav-tab nav-tabs" id="food-city-tab" role="tablist">
                        @foreach($controller->getCities() as $city)
                            <li role="presentation"><a href="#food-tab-{{$city->id}}" aria-controls="food-tab-{{$city->id}}" role="tab" data-toggle="tab">{{$city->name}}</a></li>
                        @endforeach
                        
                      </ul>

                      <!-- Tab panes -->
                      <div class="tab-content">
                        @foreach($controller->getCities() as $city)
                        <div role="tabpanel" class="tab-pane" id="food-tab-{{$city->id}}">
                        <ul class="index-sight-hot-ul">
                        @foreach($controller->getFoods($city->id) as $sight)
                            <li><a href="{{$sight->getFoodUrl()}}" title="{{$sight->title}}">
                                <div><img class="img_none" src="/img/none.gif" datasrc="{{$sight->getPic()}}" style="width:99%;height:150px"/></div>
                                <div class="title">{{$sight->title}}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                        <div class="sp"></div>
                        </div>
                        @endforeach
                      </div>
                      
                      <script language="javascript">
                        $($("#food-city-tab a")[0]).trigger("click");
                      </script>
                    </div>
                  
                  
                  </div>
                
                </div>
                
            </div>
        </div>
    </div>
<script language="javascript">
	$(
		function(){
			$('.img_none').each(
				function(){
					this.src = $(this).attr('datasrc');
				}
			);
		}
	);
</script>
    
@endsection