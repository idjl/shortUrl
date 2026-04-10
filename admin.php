<?php

/**
* @Author: Ding Jianlong
* @Date:   2024-06-19
*/

error_reporting(E_ALL ^ E_NOTICE);
require "config.php";
require "function.php";

checkAdmin();

// 退出登录
if(isset($_GET['action']) && $_GET['action'] === 'logout'){
    session_destroy();
    header('Location: login.php');
    exit;
}

$saveFile = getSaveFile($config);
$arr = loadUrls($saveFile);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title><?php echo $config['title']; ?> 后台管理</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

<div class="container">
    <h2 style="text-align:center;position:relative;">
        列表
        <a href="admin.php?action=logout" class="btn btn-sm btn-secondary" style="position:absolute;right:0;top:5px;">退出登录</a>
    </h2>
    <table class="table table-hover" style="word-break:break-all; word-wrap:break-word;">
        <tbody>
        <?php
        foreach($arr as $k => $v){
            $k = htmlspecialchars($k, ENT_QUOTES, 'UTF-8');
            $v = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
            echo '<tr><td>'.$k.'</td><td>'.$v.'</td><td><a href="javascript:void(0)" onclick="del(\''.$k.'\')">删除</a></td></tr>';
        }
        ?>
        </tbody>
    </table>
</div>
</body>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    function del(id) {
        $.ajax({
            url: "admin_delete.php",
            type: 'POST',
            data: {"id":id},
            dataType: 'json',
            success: function (data) {
                if(data.code == 200){
                    alert(data.message);
                    window.location.reload(true);
                }else{
                    alert(data.message);
                }
            },
            error: function (e) {
                console.log(e);
                alert("服务器错误，请稍后再试");
            },
        });
    }
</script>
</html>
