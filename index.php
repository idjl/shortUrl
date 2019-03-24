<?php

/**
 * @Author: Ding Jianlong
 * @Date:   2019-03-20 22:39:26
 * @Last Modified by:   Ding Jianlong
 * @Last Modified time: 2019-03-20 23:52:39
 */

error_reporting(E_ALL ^ E_NOTICE);//显示除去 E_NOTICE 之外的所有错误信息
require "config.php";
require "function.php";

$token = makeToken($config['key']);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>
        <?php echo $config['title']; ?>
    </title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<div class="container">
	    <h1 class="title text-center">
	        <?php echo $config['title']; ?>
	    </h1>
	    <form id="main" action="" method="post">
	        <div class="text-center">
	            <div class="input-group input-group-lg">
	                <label for="url">网址：</label>
	                <input type="text" name="url" id="url" class="content form-control" placeholder="请以http://或https://开头"><br>
	            </div>
	            <input type="hidden" name="token" value="<?php echo $token; ?>">
	            <button id="submit" class="btn btn-lg btn-danger" type="button" name="create">生成短网址</button>
	        </div>
	    </form>
	    <div class="text-center">
	        <h3 id="short_url">

	        </h3>
	        <div class="qrcode" id="qrcode" style="display:none;"></div>
	        <h3 class="text-warning" id="notice"></h3>

	        <p class="friendLink">
	            <span>友情链接</span>
	            <a href="http://t.tl/" target="_blank">t.tl短网址</a>
	            <a href="http://dwz.cn/"  target="_blank">百度短网址</a>
	            <a href="https://github.com/idjl/shortUrl"  target="_blank">源码下载</a>
	        </p>
	    </div>
	</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.qrcode.min.js"></script>
<script>
    $("#submit").click(function() {
        //隐藏上一次的内容
        $("#short_url").css("display",'none');
        $('#qrcode').css("display",'none');
        //ajax表单序列化
        var data = $("#main").serialize();
        $.ajax({
            url: "create.php",
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (data) {
                //console.log(data);
                if(data.code == 200){
                    $("#short_url").css("display",'block');
                    $("#short_url").text(data.shortUrl);
                    $('#qrcode').css("display",'block');
                    $('#qrcode').text('');  //先清空之前的
                    $('#qrcode').qrcode({width:180,height:180,text:data.shortUrl});
                }
                $("#notice").text(data.message);
            },
            error: function (e) {
                //console.log(e);
                $("#notice").text("服务器错误，请稍后再试");
            },
        });
    });

    //token刷新
    var t = setInterval(refreshCount, 1000);
    function refreshCount() {
        var time=new Date().getTime();
        time = Math.floor(time/1000);
        if(time%1000 == 0){
            window.location.reload();
        }
    }
</script>
</body>
</html>