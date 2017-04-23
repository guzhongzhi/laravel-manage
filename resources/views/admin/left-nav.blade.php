<!--sidebar-menu-->
<div id="sidebar">
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
        <li class="
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