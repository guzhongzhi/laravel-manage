<html>
	<head>
    @section("head")
		<title>Laravel</title>
        
        <style>
            body {
                fbackground:#e1e1e1;
                padding:0;
                margin:0;
            }
            .top-banner{
                height:90px;
                
            }
            .nav ul{
                height:46px;
                background:#e5004f;
                line-height:46px;
                font-weight:bold;
            }
            .nav ul,
            .nav li {
                list-style:none;
                margin:0;
                padding:0;
            }
            .nav li a{
                color:#FFF;
            }
            .nav li {
                float:left;
                padding:0 20px;
                color:#FFF;
                
            }
            .container {
                width:1024px;
                margin:0px auto;
                background:#FFF;
                overflow:hidden;
            }
            .clear {
                clear:both;
            }
            .sp {
                height:10px;
                overflow:hidden;
                clear:both;
            }
		</style>
	@show
		

		
	</head>
	<body>
    @section("top")
    <div class="container">
        <div class="top-banner">
            <img src="/skin/images/banner.png"/>
        </div>
    </div>
    <div class="container nav">
        
        <ul class="nav">
            <li><a href="/">首页</a></li>
            <li><a href="{{url('sight/')}}">景点</a></li>
            <li>游记</li>
            <li>国内游</li>
            <li>出境游</li>
        </ul>
        <div class="clear"></div>
    </div>
    @show
    
    @section("content")
    
    @show
		
    @section("bottom")
    @show
	</body>
</html>
