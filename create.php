<?php

/**
 * @Author: Ding Jianlong
 * @Date:   2019-03-20 22:39:04
 * @Last Modified by:   Ding Jianlong
 * @Last Modified time: 2019-03-20 23:42:37
 */


error_reporting(E_ALL ^ E_NOTICE);//显示除去 E_NOTICE 之外的所有错误信息
require "config.php";
require "function.php";

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

//引入保存网址的php文件
$arr = require $saveFile;

// 判断是生成还是跳转
@$id = trim($_GET['id']);
if ($id) {
    //跳转
    if ($arr[$id]) {
        header('location:' . $arr[$id]);
    }
    $message = "不存在此短网址" . $config['site'] .'/'. $id;
    echo json_encode(array('code' => 8404, 'message' => $message));
    exit();
} else {
    //生成
    $token = $_POST['token'];
    $url = trim($_POST['url']);
    if (!checkToken($config['key'],$token)) {
        $message = 'tooken错误，请访问'.$config['site'];
        echo json_encode(array('code' => 8000, 'message' => $message));
        exit();
    }
    if ($url == '') {
        $message = '没有输入URL地址';
        echo json_encode(array('code' => 8001, 'message' => $message));
        exit();
    }
    //判断是否正则为正确的网址
    $regex = "/^(https?:\/\/)([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=#]*)?$/i";
    if (!preg_match($regex, $url)) {
        $message = '请输入正确的URL地址';
        echo json_encode(array('code' => 8002, 'message' => $message));
        exit();
    }
    //判断是否是黑名单域名
    if (!checkBlackList($url, $config['blackList'])) {
        $message = '抱歉，暂时无法缩短该域名';
        echo json_encode(array('code' => 8003, 'message' => $message));
        exit();
    }
    //检查是否已存在重复网址
    $find = array_search($url, $arr);
    if ($find !== false) {
        $id = $find;   //返回之前的短网址
    } else {
        //原来没有新插入
        if ($config['type'] == 'abc') {
            $id = createId(5);    //随机生成5位小数字母+数字
            $id = checkId($arr, $id);
            //原来没有新插入
            $arr[$id] = $url;
        } elseif ($config['type'] == '123') {
            $arr[] = $url;
            $id = count($arr) - 1;

        }
        $a = '<?php' . PHP_EOL . 'return ' . var_export($arr, true) . ';';
        file_put_contents($saveFile, $a);
    }
    $message = "缩短网址成功";
    $shortUrl = ($config['use_rewrite'] == 1) ? "{$config['site']}/{$id}" : "{$config['site']}/create.php?id={$id}";
    echo json_encode(array('code' => 200, 'message' => $message, 'shortUrl' => $shortUrl));
    exit();
}