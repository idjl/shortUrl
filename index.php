<?php

/**
 * @Author: Ding Jianlong
 * @Date:   2019-03-20 22:39:26
 * @Last Modified by:   Ding Jianlong
 * @Last Modified time: 2019-03-20 23:52:39
 */

error_reporting(E_ALL ^ E_NOTICE);
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
    <title><?php echo $config['title']; ?></title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class="page-wrapper">
    <div class="card">
        <div class="card-icon">🔗</div>
        <h1 class="card-title"><?php echo $config['title']; ?></h1>
        <p class="card-desc">将长网址转换为简短链接，方便分享</p>

        <form id="main" action="" method="post" autocomplete="off">
            <div class="input-row">
                <input type="text" name="url" id="url" class="url-input" placeholder="请粘贴以 http:// 或 https:// 开头的网址">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <button id="submit" type="button">生成</button>
            </div>
        </form>

        <div id="result" class="result" style="display:none;">
            <div class="result-label">短网址已生成 🎉</div>
            <div class="result-url-row">
                <input type="text" id="short_url" class="result-url" readonly>
                <button type="button" id="copyBtn" class="copy-btn" title="复制">📋</button>
            </div>
            <div class="qrcode-wrap">
                <div id="qrcode"></div>
            </div>
        </div>

        <div id="notice" class="notice"></div>

        <div class="footer-links">
            <a href="http://t.tl/" target="_blank">t.tl短网址</a>
            <span class="sep">·</span>
            <a href="https://github.com/idjl/shortUrl" target="_blank">源码下载</a>
        </div>
    </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/jquery.qrcode.min.js"></script>
<script>
$("#submit").click(function() {
    var btn = $(this);
    if(btn.hasClass('loading')) return;
    btn.addClass('loading').text('生成中...');
    $("#result").slideUp(150);
    $("#notice").text('').removeClass('error success');

    $.ajax({
        url: "create.php",
        type: 'POST',
        data: $("#main").serialize(),
        dataType: 'json',
        success: function(res) {
            if(res.code == 200){
                $("#short_url").val(res.shortUrl);
                $('#qrcode').empty().qrcode({width:160, height:160, text:res.shortUrl});
                $("#result").slideDown(250);
                $("#notice").addClass('success').text(res.message);
            } else {
                $("#notice").addClass('error').text(res.message);
            }
        },
        error: function() {
            $("#notice").addClass('error').text("服务器错误，请稍后再试");
        },
        complete: function() {
            btn.removeClass('loading').text('生成');
        }
    });
});

// 回车提交
$("#url").keydown(function(e){ if(e.keyCode===13){ e.preventDefault(); $("#submit").click(); }});

// 复制
$("#copyBtn").click(function(){
    var input = document.getElementById('short_url');
    input.select();
    document.execCommand('copy');
    var btn = $(this);
    btn.text('✅');
    setTimeout(function(){ btn.text('📋'); }, 1500);
});

// token 刷新
setInterval(function(){
    if(Math.floor(Date.now()/1000) % 1000 === 0) location.reload();
}, 1000);
</script>
</body>
</html>
