<!DOCTYPE html>
<html lang="en">
<head>
<title>
@section('head.title')
    @parent
    section.page.title
@show
</title>

@section('head')
    @include('admin.head')
@show

</head>
<body class="body-empty">


<!--main-container-part-->
<div class="content-empty {{ $htmlBodyCssName}}">
    <div class="content-detail">
    @section('content')
         
    @show
    </div>
</div>

<!--end-main-container-part-->

<!--end-Footer-part-->
@section('footer.js')
    @include('admin.footer-js')
@show

</body>
</html>
