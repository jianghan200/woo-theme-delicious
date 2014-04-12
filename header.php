<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- 描述手机设备,不允许放大 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="format-detection" content="telephone=no">
    
    <!-- 网页元信息 -->
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="author" content="暨林瀚">
    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri().'/img/favicon.ico' ?>">

    <!-- 站点名称 -->
    <title> <?php bloginfo('name'); ?></title>

    <!-- 载入Wordpres核心CSS -->
    <link href="<?php bloginfo('stylesheet_url');?>" rel="stylesheet">
   
    <!-- IE兼容处理 -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  

  <!-- 插入导航JS -->
  <script>
      $(document).bind("mobileinit", function() {
        //disable ajax nav
        $.mobile.ajaxEnabled=false;

        //初始化代码
        $.mobile.loadingMessage = "正在加载";
        $.mobile.pageLoadErrorMessage = "加载页面出错";

        $.mobile.page.prototype.options.backBtnText = "后退";
        $.mobile.dialog.prototype.options.closeBtnText = "关闭";

        $.mobile.page.prototype.options.domCache = false;

        if (navigator.userAgent.indexOf("Android") != -1)
        {
          $.mobile.defaultPageTransition = 'none'; 
          $.mobile.defaultDialogTransition = 'none'; 
        }
        else
        {
          $.mobile.defaultPageTransition = 'slide'; 
          $.mobile.defaultDialogTransition = 'pop';
        }

      });

  </script>

  <!--以下载入主题需要的JS和CSS
  *************************************************-->
  <!-- 载入JQuery Mobile 样式 -->
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
  <!-- 载入JQuery -->
  <script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
   <!-- 载入Jquery Mobile -->
  <script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>

  <!-- iscroll插件 -->
  <script src="<?php bloginfo('template_url'); ?>/js/iscroll-lite.js"></script>
  
  <!-- 载入自定义的js和css -->
  <script src="<?php bloginfo('template_url'); ?>/js/shoporder.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/css/shop.css" />
	
  

  <?php wp_head(); ?>   
  </head>
<body>

<div data-role="page" id = "shoporder-page">  
  <?php //require_once('header-jqm.php') ?>

<style type="text/css">
  
#wrapper_menu {
  position: absolute; z-index: 1;
  width: 100%;
  height: 100%;
  overflow:auto;
}

#scroller_menu {
  position:absolute; z-index:1;
/*  -webkit-touch-callout:none;*/
  -webkit-tap-highlight-color:rgba(0,0,0,0);
  width: 100%;
  padding:0;
}

#wrapper_content {
  position: absolute; z-index: 1;
  width: 100%;
  height: 100%;
  overflow:auto;
}

#scroller_content {
  position:absolute; z-index:1;
/*  -webkit-touch-callout:none;*/
  -webkit-tap-highlight-color:rgba(0,0,0,0);
  width:100%;
  padding:0;
}

#wrapper_popup {
  overflow:auto;
}

</style>


   <script type="text/javascript">
    var iScroll_menu;
    var iScroll_content;
    var iScroll_popup;

    function load_iScroll() {
      iScroll_menu = new IScroll("#wrapper_menu", {
        click: true,
      });
      
      iScroll_content = new IScroll('#wrapper_content', {
        eventPassthrough: false,
        preventDefault: false,
        useTransition: false,
      });
    }

    function load_iScroll_Popup() {
      iScroll_popup = new IScroll("#wrapper_popup", {
        click: true,
      });
    }

    //返回一个size,用于popup
    function scale(padding, border) {
        var srcWidth = $( window ).width() - 50,
            srcHeight = $( window ).height() - 100,
            ifrPadding = 2 * padding,
            ifrBorder = 2 * border,
            w, h;
       
          w = srcWidth;
          h = srcHeight;

          return {
              'width': w - ( ifrPadding + ifrBorder ),
              'height': h - ( ifrPadding + ifrBorder )
          };
    }

     $('#shoporder-page').on("pageshow",function(){
      //弹窗相关
      $("#popupFoodinfo").on({
            popupbeforeposition: function() {
                var size = scale( 15, 1 ),
                    w = size.width;
                    h = size.height;

                $("#wrapper_popup").css("width", w);
                $("#wrapper_popup").css("height", h);
            },
            popupafteropen: function() {
              hideLoader();
              //初始化滚动条相关的东西
              setTimeout(function () { load_iScroll_Popup(); }, 200);
            },
            popupafterclose: function() {
               $("#wrapper_popup").css("width", 0);
               $("#wrapper_popup").css("height", 0);
            },
        });

      //初始化滚动条相关的东西
      setTimeout(function () { load_iScroll(); }, 200);

      document.getElementById('shoporder-page').addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
    })
    
var curTypeId = 0;
    var curFoodId = 0;
    curTypeId = 2193;

//打开食物详情页面
   function showfoodinfo(food_name)
    {
      function OnFoodinfoSuccess(data, status)
      {
        document.getElementById('foodinfo-content').innerHTML = data;
        $('#popupFoodinfo').popup('open');

        myScroll3.refresh();
      }

      function OnFoodinfoFailed(data, status) 
      {
        hideLoader();
      }

      $.ajax({
            type: "GET",
            url: "<?php bloginfo('url')?>/?product="+food_name,
            dataType : "html",  
            cache : false,
            success: OnFoodinfoSuccess,
            error: OnFoodinfoFailed,
        });

        return true;
    }

    //关闭食物详情页面
    function closefoodinfo()
    {
      $('#popupFoodinfo').popup('close');
    }
    
    function showfood(obj){
      var id = $(obj).attr('id');
      $('.active').removeClass('active');
      $(obj).addClass('active');
      $('.fooditem').hide();
      $('.'+id+'_food').show();

      myScroll2.scrollTo(0, 0, 200, 0);
      myScroll2.refresh();
    }
    function shouchang(obj,type){
      var tage = $(obj).attr('tage');
      if(tage != 'zc'){
        return false;
      }
      $(obj).attr('tage','jxz');
      var food_id = $(obj).attr('foodid');
      var url = "/index.php?r=show/foodcollect&customerid=348801&wxusername=oiRPcjkEe0gxS8s5gUuoNW2RD5zg&adminId=295&shopId=712";
      $.post(url,{food_id:food_id,type:type},function(data){
        if(data == 'success'){
          $(obj).attr('tage','zc');
          $(obj).parent('div').siblings('div').show();
          $(obj).parent('div').hide();
        }else{
          $(obj).attr('tage','zc');
        }
      });
    }

  // 搜索功能相关
    var height = 0;
    function cbutton(obj){
      if(height == 0){
        height = $('#page_top').css('height');
        $('#search_box').css('top',height);  
      }
      $('#search_box').toggle();
    }
    //提交搜索
    function submitsearch(){
      var keyword = $('#s').val();
      if(keyword == ''){
        $('#s').focus();
      }else{
        $('#searchform').submit();
      }
    }
  </script>
  
    
  <div id="page_top" data-id="shop_header" data-role="header" data-position="fixed" data-tap-toggle="false">
    <h1 id = "page_title">伯明翰汉朝餐厅</h1>
    <a data-icon="search" class="ui-btn-right" onclick="cbutton(this);">搜索</a>
  </div>

  <div id="search_box" style="width:100%;background:#ffffff;position:fixed;top:0px;left:0px;display:none;padding-buttom:20px;z-index:9999;">
    <form id="searchform" action="<?php bloginfo('url')?>/" method="post">
      <div style="width:95%;margin:0 auto;position:relative;">
        <input type="text" name="s" placeholder="搜索商品" id="s"/>
        <span onclick="return submitsearch();" style="position:absolute;background:transparent;border:0;width:50px;top:9px;right:3px;z-index:9999;">搜索</span>
      </div>
    </form>
  </div>