@extends("admin.column-2-left")

@section('content')

<div class="widget-box">
  <div class="widget-title"> <span class="icon"> <i class="icon-hand-right"></i> </span>
    <h5>Tools</h5>
    <span style="float:right;line-height:36px;margin-right:10px">
        <a href="javascript:loadAjaxModal('{{url("admin/system/menu/edit/0")}}')"  class="btn btn-danger btn-mini"><i class="icon-plus-sign"></i> Add New Menu</a> 
    </span>
  </div>
  <div class="widget-content"> 
    @include("admin.search");    
  </div>
</div>


        <div class="widget-box">
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped with-check">
              <thead>
                <tr>
                  <th class="{{$paginateHelper->getOrderedFieldClass('id')}}"><a href="{{$paginateHelper->getSortByUrlByNumberAndFieldName('id')}}">ID</a></th>
                  <th>ParentId</th>
                  <th class="{{$paginateHelper->getOrderedFieldClass('name')}}"><a href="{{$paginateHelper->getSortByUrlByNumberAndFieldName('name')}}">Name</a></th>
                  <th>Url</th>
                  <th class="{{$paginateHelper->getOrderedFieldClass('sort_order')}}"><a href="{{$paginateHelper->getSortByUrlByNumberAndFieldName('sort_order')}}">Order</a></th>
                  <th>Menu</th>
                  <th>icon</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              @foreach($paginate->items() as $menuItem)
                <tr>
                  <td>{{$menuItem->id}}</td>
                  <td>{{$menuItem->parent_id}}</td>
                  <td>{{$menuItem->name}}</td>
                  <td>{{$menuItem->url}}</td>
                  <td>{{$menuItem->sort_order}}</td>
                  <td>{{$menuItem->show_in_menu}}</td>
                  <td>{{$menuItem->icon}}</td>
                  <td class="center table-action">
                    <a href="javascript:loadAjaxModal('{{url("/admin/system/menu/edit",$menuItem->id)}}')" title="Edit"><i class="icon-edit"></i></a>
                    <a href="javascript:loadAjaxModal('{{url("/admin/system/menu/edit",$menuItem->id)}}')" title="Delete"><i class="icon-trash"></i></a>
                    
                  </td>
                </tr>
                @endforeach
              </tbody>
              
            </table>
            
          </div>
        </div>
            
            <div class="clearboth pagination-container">
            
              <ul class="pagination pagination-sm">
                <li><a href="{{$paginateHelper->getPageUrlByNumber($paginateHelper->getPrevPageNumber())}}" aria-label="Previous"><span aria-hidden="true">&lt;&lt;</span></a></li>
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
            </div>
@endsection