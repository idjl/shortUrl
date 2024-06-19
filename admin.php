<?php

/**
* @Author: Ding Jianlong
* @Date:   2024-06-19
*/

error_reporting(E_ALL ^ E_NOTICE);//显示除去 E_NOTICE 之外的所有错误信息
require "config.php";
require "function.php";

if (!isset($_SERVER['PHP_AUTH_USER']) || $config['adminUser'] != $_SERVER['PHP_AUTH_USER'] || $config['adminPWD'] != $_SERVER['PHP_AUTH_PW'] ) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
}

if ($config['type'] == 'abc') {
    $saveFile = 'urlsabc.php';
} elseif ($config['type'] == '123') {
    $saveFile = 'urls123.php';
} else {
    exit('config.php中设置错误，请检查。');
}
// 检查本地文件是否可读写
if (!is_writable($saveFile) || !is_readable($saveFile)) {
    die('请将urlsabc.php（或urls123.php）文件设置为777权限');
}
$arr = include $saveFile;

?>

<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>
        <?php echo $config['title']; ?> 后台管理
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
    <h2 style="text-align: center">列表</h2>
    <table class="table table-hover" style="word-break:break-all; word-wrap:break-word;">
        <tbody>
        <?php

        foreach($arr as $k => $v){
            // echo $k.' ==> '.$v.'<a href="javascript:void (0)" onclick="del(\''.$k.'\')">删除</a><br>';
            echo '<tr><td>'.$k.'</td><td>'.$v.'</td><td><a href="javascript:void (0)" onclick="del(\''.$k.'\')">删除</a></td></tr>';
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
        //ajax表单序列化
        $.ajax({
            url: "admin_delete.php",
            type: 'POST',
            data: {"id":id},
            dataType: 'json',
            success: function (data) {
                console.log(data);
                console.log(data.code);

                if(data.code == 200){
                    // 刷新页面
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





