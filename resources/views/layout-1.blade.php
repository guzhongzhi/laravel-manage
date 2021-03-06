<html>
    <head>
    @section("head")
    
    @section('meta')
    <title>{{$controller->getConfig()["site_name"]}}</title>
    <meta name="title" content="{{$controller->getConfig()['site_name']}}" />
    <meta name="keywords" content="{{$controller->getConfig()['site_keywords']}}" />
    <meta name="description" content="{{$controller->getConfig()['site_description']}}" />
    @show
    <link rel="stylesheet" href="/css/bootstrap.travel.min.css">  
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
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
                height:160px;
                
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
            .breadcrumb{
                padding: 12px 15px;
                margin-bottom: 0px;
                list-style: none;
                background-color: #fffdfb;
                border-radius: 4px;
                border: solid 1px #e5004f;
            }
            .nav>li>a:focus, .nav>li>a:hover {
                text-decoration: none;
                background-color: #ca0046;
            }
            .sight-relation-sights,
            .sight-relation-sights li {
                padding:0px;
                margin:0px;
                list-style:none;
            }
            
            .sight-relation-sights li {
                margin-bottom:10px;
                clear:both;
                height:100px;
            }
            
            .nav>li>a.active {
                background-color: #ca0046;
            }
        </style>
    @show
        
    </head>
    <body>
    @section("top")
    <div class="container">
        <div class="top-banner" style=" background-image: url(/skin/images/banner.png)">
            <image src="/skin/images/logo.png" />
        </div>
    </div>
    <div class="container nav">
        

        <ul class="nav" id="top-nav">
            <li class="nav-home"><a href="/">首页</a></li>
            <li class="nav-sight"><a href="{{url('sight/')}}">景点</a></li>
            <li class="nav-hotel"><a href="{{url('hotel/')}}">酒店</a></li>
            <li class="nav-travel"><a href="{{url('travel/')}}">游记</a></li>
            <li class="nav-food"><a href="{{url('food/')}}">美食</a></li>
        </ul>
        <div class="clear"></div>
    </div>
    <script language="javascript">
    $(document).ready(function(){
        var t = location.pathname.split("/");
        if(t[1] == "" ) {
            t[1] = "home";
        }
        $("#top-nav .nav-" + t[1] + " a").addClass("active");
        
    });
    </script>
    @show
    <div class="clear"></div>
    
    @section("breadcrumb")
        
    @show
    
    <div class="clear"></div>
    @section("content")
    
    @show
    <div class="sp"></div>
    @section("bottom")
        <div style="border-top:solid 2px #f55b90;background:#f5f5f5;padding:10px 10%;">
        <!--
            <div class="row">
              <div class="col-md-3">去旅行</div>
              <div class="col-md-3">最热门</div>
              <div class="col-md-3">旅游常识</div>
              <div class="col-md-3">帮助中心</div>
            </div>
            <div class="sp"></div>
            -->
        <div class="" style="text-align:center;font-size:12px;line-height:24px;">
            站长联系：<a href="mailto:admin@yuxiclub.com">admin@yuxiclub.com</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copyright © 2017 · All Rights Reserved : 愈惜旅游网 <br />
            网站备案号: 蜀ICP备17014098号-1
            
            <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1262495929'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/z_stat.php%3Fid%3D1262495929%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script> 
        </div>
        </div>
        
    @show
    </body>
</html>
