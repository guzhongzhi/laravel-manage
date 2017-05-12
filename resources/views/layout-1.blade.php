<html>
	<head>
    @section("head")
		<title>Laravel</title>
        	<link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">  
	<script src="http://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
        .tab-section-content {
            min-height:150px;
        }
        .sight-relation-right {
            padding:10px;
            border: solid 1px #e1e1e1;
            background: #fffeee;
            border-top:none;
        }
        .sight-detail-title h1{
            padding:0;
            margin:0px;
            font-size:22px;
        }
        .sight-detail-title {
            font-size:22px;
            padding:6px 0;
            font-weight:bold;
        }
        .sight-relation {
            margin:10px;
        }
        .sight-relation ,
        .sight-relation li {
            margin:0px;
            padding:0px;
            list-style:none;
            
        }
        .sight-relation li {
            float:left;
            width:49%;
            padding:16px 0px;
            
        }
        .short-desc-view-on-detail {
            font-size:14px;
            line-height:24px;
            color:#a96500;
            border:solid 1px #e1e1e1;
            padding:6px;
            background:#FFFAEE;
        }
        .tab ul,
        .tab li {
            margin:0;
            padding:0;
            list-style:none;
        }
        .tab li:first-child{
            margin-left:0px;
        }
        .tab li.active {
            background:#f33b7a;
            color:#FFF;
            font-weight:bold;
        }
        .tab li {
            float:left;
            padding:6px 16px;
            background:#e9e9e9;
            color:#222;
            margin-left:10px;
            cursor:pointer;
        }
            .fleft {
                float:left;
            }
            .fright {
                float:right;
            }
            .two-left-big {
                width:calc(100% - 320px);
            }
            .two-right-small {
                width:300px;
            }
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
            .hidden {
                display:none;
            }
            .sight-detail-tab-content  {
                font-size:14px;
                line-height:26px;
            }
            .nav li{
                padding: 0 10px;
            }
            .nav li a {
                padding:0px 15px;
                margin-right:15px;
            }
            .container {
                padding:0px;
                width:80%;
                max-width:1240px;
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
            .pagination li a{
                border:solid 1px #e1e1e1;
                padding:4px 8px;
            }
            .pagination-container li,
            .pagination-container ul {
                list-style:none;
                padding:0px;
                margin:0px;
            }
            
            .pagination li {
                float:left;
                padding:10px;
            }
            
            .pagination li.active a{
                background:#999;
                color:#FFF;
            }
            .pagination li:first-child{
                padding-left:0px;
            }
            
            .fr {
                float:right!important
            }
		</style>
	@show
		

		
	</head>
	<body>
    @section("breadcrumb")
        
    @show
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
            <li><a href="{{url('travel/')}}">游记</a></li>
            <li><a href="/">国内游</a></li>
            <li><a href="/">出境游</a></li>
        </ul>
        <div class="clear"></div>
    </div>
    @show
    <div class="clear"></div>
    @section("content")
    
    @show
	<div class="sp"></div>
    @section("bottom")
    <br/>
    <br/>
    <br/>
    fdsa
    <br/>
    <br/>
    @show
	</body>
</html>
