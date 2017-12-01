<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"D:\PhPstudy\WWW\think2\public/../application/home/view/default/village\village_detail.html";i:1512059530;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>通知详情</title>

    <!-- Bootstrap -->
    <link href="__ROOT__/static/new/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="__ROOT__/static/new/css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .main{margin-bottom: 60px;}
        .indexLabel{padding: 10px 0; margin: 10px 0 0; color: #fff;}
    </style>
</head>
<body>
<div class="main">
    <!--导航部分-->
    <nav class="navbar navbar-default navbar-fixed-bottom">
        <div class="container-fluid text-center">
            <div class="col-xs-3">
                <p class="navbar-text"><a href="index.html" class="navbar-link">首页</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">服务</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">发现</a></p>
            </div>
            <div class="col-xs-3">
                <p class="navbar-text"><a href="#" class="navbar-link">我的</a></p>
            </div>
        </div>
    </nav>
    <!--导航结束-->

    <div class="container-fluid">
        <div class="blank" id="<?=$row['id']?>">

        </div>
        <h3 class="noticeDetailTitle"><strong><?=$row['title']?></strong></h3>
        <div class="noticeDetailInfo">发布者:<?=$row['name']?>小区物管</div>
        <div class="noticeDetailInfo">发布时间：<?=date('Y-m-d',$row['create_time'])?></div>
        <div class="noticeDetailContent">
            <?=$row['description']?>
        </div>
        <P> <?=$content['content']?></P>
      </div>
   </div>
<script src="__ROOT__/static/new/jquery-1.11.2.min.js"></script>
<script type="text/javascript">

    $.getJSON('ajax_get_info',{id:$('.blank').attr('id')},function (data) {

            if(data=='1')
            {
                var content = ' <a href="javascript: ;" status="1">已报名</a>';
                $(content).appendTo('.blank');
            }else {
                var content = ' <a href="javascript: ;" status="0">申请报名</a>';
                $(content).appendTo('.blank');
            }
    });

    $('.blank').click(function () {
        var that = this;
        if($(this).find('a').attr('status')==0)
        {
            var id = $(this).attr('id');
            $.getJSON('ajax_insert',{id:id},function (data) {
                    if(data=='1')
                    {
                        var content = ' <a href="javascript: ;" status="1">已报名</a>';
                        $(that).html(content);
                    }
            })
        }else {
            alert('别点了,已报名了')
        }

    })
</script>
<!--&lt;!&ndash; jQuery (necessary for Bootstrap's JavaScript plugins) &ndash;&gt;

&lt;!&ndash; Include all compiled plugins (below), or include individual files as needed &ndash;&gt;
<script src="../bootstrap/js/bootstrap.min.js"></script>-->
</body>
</html>