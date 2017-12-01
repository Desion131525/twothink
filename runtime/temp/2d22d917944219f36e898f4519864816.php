<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\PhPstudy\WWW\think2\public/../application/home/view/default/village\life.html";i:1512029878;}*/ ?>
<!DOCTYPE html>
<html lang="en">
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
<div class="container">
    <ul class="list-group fuwuList">
        <h1>生活贴士</h1>
        <?php foreach ($rows as $row) :?>
        <li class="list-group-item"><a href="<?php echo url('village/notice_detail'); ?>?id=<?=$row['id']?>" class="text-warning"><span class="iconfont">&#xe601;</span><?=$row['title']?></a></li>
        <?php endforeach;?>
    </ul>
</div>
<script type="text/javascript">
    $(function () {


    });
</script>
</body>
</html>