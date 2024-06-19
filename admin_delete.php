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

// 删除
$id = $_POST['id'] ?? '';
if($id){
    if(isset($arr[$id])) unset($arr[$id]);

    $a = '<?php' . PHP_EOL . 'return ' . var_export($arr, true) . ';';
    file_put_contents($saveFile, $a);

    $message = "删除成功";
    echo json_encode(array('code' => 200, 'message' => $message));
    exit;
}