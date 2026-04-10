<?php

error_reporting(E_ALL ^ E_NOTICE);
require "config.php";
require "function.php";

session_start();

// 已登录则直接跳转后台
if(!empty($_SESSION['admin_logged'])){
    header('Location: admin.php');
    exit;
}

$error = '';

// 处理登录提交
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $user = trim($_POST['username'] ?? '');
    $pwd  = $_POST['password'] ?? '';
    if($user === $config['adminUser'] && $pwd === $config['adminPWD']){
        $_SESSION['admin_logged'] = true;
        header('Location: admin.php');
        exit;
    }else{
        $error = '账号或密码错误';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title><?php echo $config['title']; ?> 登录</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
<div class="container" style="max-width:400px;margin-top:100px;">
    <h2 class="text-center" style="margin-bottom:30px;">后台登录</h2>
    <?php if($error): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="username">账号</label>
            <input type="text" name="username" id="username" class="form-control" required autofocus>
        </div>
        <div class="form-group" style="margin-top:15px;">
            <label for="password">密码</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-danger btn-block" style="width:100%;margin:20px 0;">登录</button>
    </form>
</div>
</body>
</html>
