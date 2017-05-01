<!--sidebar-menu-->
<style>

#sidebar > ul li ul li a{ color:#939da8}
#sidebar > ul li ul li a:hover, #sidebar > ul li ul li.active a {
	color: #fff;
	background-color: none;
}

.left-menu ul,
.left-menu li {
    padding:0px;
    margin:0px;
    list-style:none;
    
}
.left-menu li.left-menu-submenu  {
    padding-left:30px ;
}
.left-menu li.active a,
.left-menu li a:hover{
    background:none;
}
.left-menu li.active {
    
    background:#28b779
    
}

</style>
<div id="sidebar" class="left-menu">
  <ul>
    @foreach($GlobalLeftMenuItems as $menuItem)
    <li class="
    @if($menuItem->url == $currentUrl  )
    active
    @endif
    
    @if($menuItem->getIsActive($currentUrl) || $menuItem->url == $currentUrl)
    open
    @endif
    
    @if($menuItem->hasChild())
        submenu
    @endif
    "> 
    <a href="{{url($menuItem->url)}}">
        <i class="icon {{$menuItem->icon}}"></i> 
        <span> {{$menuItem->name}}</span>
    </a> 
    
    @if($menuItem->hasChild()) 
    <ul>
        @foreach($menuItem->getChilds() as $childMenu)
        <li class=" left-menu-submenu 
        @if($childMenu->url == $currentUrl  )
        active
        @endif
        "><a href="{{url($childMenu->url)}}">{{$childMenu->name}}</a>
        </li>
        @endforeach
    </ul>
    @endif
    </li>
    @endforeach
    
  </ul>
</div>
<!--sidebar-menu-->