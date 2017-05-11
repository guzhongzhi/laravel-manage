
<ul class="pagination pagination-sm">
<li><a href="{{$paginateHelper->getPageUrlByNumber($paginateHelper->getPrevPageNumber())}}" aria-label="Previous"><span aria-hidden="true">&lt;&lt;</span></a></li>
    {{$paginateHelper->initPageNumberDisplay()}}
    @while($paginateHelper->hasNextPageToDisplay() )
        <li class="
    @if($paginateHelper->getCurrentDisplayPageNumber() == $paginateHelper->getCurrentPage())
        active
    @endif
    "><a href="{{$paginateHelper->getPageUrlByNumber($paginateHelper->getCurrentDisplayPageNumber())}}">{{$paginateHelper->getCurrentDisplayPageNumber()}}</a></li>
    @endwhile
<li><a href="{{$paginateHelper->getPageUrlByNumber($paginateHelper->getNextPageNumber())}}" aria-label="Next"><span aria-hidden="true">&gt;&gt;</span></a></li>
<li><a href="#" aria-label="Next">TotalPage: {{$paginateHelper->getTotalPage()}}</a></li>

</ul>