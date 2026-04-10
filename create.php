<?php

/**
 * @Author: Ding Jianlong
 * @Date:   2019-03-20 22:39:04
 * @Last Modified by:   Ding Jianlong
 * @Last Modified time: 2019-03-20 23:42:37
 */

error_reporting(E_ALL ^ E_NOTICE);
require "config.php";
require "function.php";

$saveFile = getSaveFile($config);
$arr = loadUrls($saveFile);

// 判断是生成还是跳转
$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id) {
    //跳转 — 只允许 http/https 协议，防止 header 注入
    if (isset($arr[$id]) && preg_match('#^https?://#i', $arr[$id])) {
        header('Location: ' . $arr[$id]);
        exit();
    }
    jsonResponse(8404, "不存在此短网址" . $config['site'] . '/' . $id);
} else {
    //生成
    $token = $_POST['token'] ?? '';
    $url = trim($_POST['url'] ?? '');
    if (!checkToken($config['key'], $token)) {
        jsonResponse(8000, 'token错误，请访问' . $config['site']);
    }
    if ($url === '') {
        jsonResponse(8001, '没有输入URL地址');
    }
    //判断是否为正确的网址
    $regex = "/^(https?:\/\/)([\w\-]+\.)+[\w\-]+(\/[\w\-.\/?%&=#+]*)?$/i";
    if (!preg_match($regex, $url)) {
        jsonResponse(8002, '请输入正确的URL地址');
    }
    //判断是否是黑名单域名
    if (!checkBlackList($url, $config['blackList'])) {
        jsonResponse(8003, '抱歉，暂时无法缩短该域名');
    }
    //检查是否已存在重复网址
    $find = array_search($url, $arr);
    if ($find !== false) {
        $id = $find;
    } else {
        if ($config['type'] == 'abc') {
            $id = createId(5);
            $id = checkId($arr, $id);
            $arr[$id] = $url;
        } elseif ($config['type'] == '123') {
            $id = count($arr);
            $arr[(string)$id] = $url;
        }
        saveUrls($saveFile, $arr);
    }
    $shortUrl = ($config['use_rewrite'] == 1) ? "{$config['site']}/{$id}" : "{$config['site']}/create.php?id={$id}";
    jsonResponse(200, '缩短网址成功', ['shortUrl' => $shortUrl]);
}
