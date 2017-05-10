<!DOCTYPE html>
<html lang="en">
<head>
<title>
@section('head.title')
    @parent
    {{$siteName}}
@show
</title>

@section('head')
    @include('admin.head')
@show

</head>
<body class="{{ $htmlBodyCssName}}">
<!--Header-part-->
<div id="header">
  <h1><a href="#">@yield('site_name')</a></h1>
</div>
<!--close-Header-part--> 

@section('top.nav')
    @include('admin.top-nav')
@show

@section('search')
<!--start-top-serch-->

<!--close-top-serch-->
@show

@section('left.nav')
    @include('admin.left-nav')
@show

<!--main-container-part-->
<div id="content">

@section('breadcrumb')
<!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> 
        <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
        @section('breadcrumb.append')
        
        @show
    </div>
  </div>
<!--End-breadcrumbs-->
@show
    
    @include('admin.global-message')
    
    <div class="content-detail">
    @section('content')

    @show
    </div>
</div>

<!--end-main-container-part-->

<!--Footer-part-->
@include('admin.footer')
<!--end-Footer-part-->


@section('footer.js')
    @include('admin.footer-js')

@show

</body>
</html>
