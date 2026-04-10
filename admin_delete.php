<?php

/**
 * @Author: Ding Jianlong
 * @Date:   2024-06-19
 */

error_reporting(E_ALL ^ E_NOTICE);
require "config.php";
require "function.php";

checkAdmin();

$saveFile = getSaveFile($config);
$arr = loadUrls($saveFile);

// 删除
$id = $_POST['id'] ?? '';
if($id === ''){
    jsonResponse(8001, '参数错误');
}

if(!isset($arr[$id])){
    jsonResponse(8404, '该短网址不存在');
}

unset($arr[$id]);
saveUrls($saveFile, $arr);
jsonResponse(200, '删除成功');
