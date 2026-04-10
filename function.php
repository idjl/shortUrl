<?php

/**
 * @Author: Ding Jianlong
 * @Date:   2019-03-20 22:40:10
 * @Last Modified by:   Ding Jianlong
 * @Last Modified time: 2019-03-20 23:52:20
 */


//生成前台的验证 token
function makeToken($key){
    //1000秒内有效，不变
    return md5($key.sha1(substr(time(),3,4)));
}

//后台验证token
function checkToken($key,$token){
    return hash_equals(makeToken($key), $token);
}

//不允许缩短的域名，黑名单判断
function checkBlackList($url,$blackList){
	$host = parse_url($url)['host'];
	$host = strtolower($host);
	foreach($blackList as $v){
		//包含*的情况
		if(strpos($v,'*') === 0){
			$host = '*.'.getTopHost($host);  //取顶级域名进行对比
		}
		if($host == $v){
			return false;   //黑名单域名
		}
	}
	return true;   //不是黑名单
}

//获取顶级域名
function getTopHost($host){
	//查看是几级域名
    $data = explode('.', $host);
    $n = count($data);

    //判断是否是双后缀
    $preg = '/[\w].+\.(com|net|org|gov|edu)\.cn$/';
    if(($n > 2) &&  preg_match($preg,$host)){
    	//双后缀取后3位
    	$host = $data[$n-3].'.'.$data[$n-2].'.'.$data[$n-1];
    }else{
    	//非双后缀取后两位
    	$host = $data[$n-2].'.'.$data[$n-1];
    }
    return $host;
}

//生成大小写字母和数字随机字符串
function createId($len){
    //小写字母和数字混用
    $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $key = '';
    for($i = 0;$i < $len;$i++){
        $key .= $str[mt_rand(0,strlen($str)-1)];
    }
    return $key;
}

//查看随机生成的id 是否已经被占用,占用重新生成，递归
function checkId($arr,$id){
    if(!isset($arr[$id])){
        return $id;
    }else{
        $id = createId(5);
        return checkId($arr,$id);
    }
}

//获取JSON数据文件路径
function getSaveFile($config){
    if ($config['type'] == 'abc') {
        return 'urlsabc.json';
    } elseif ($config['type'] == '123') {
        return 'urls123.json';
    } else {
        exit('config.php中设置错误，请检查。');
    }
}

//从JSON文件读取数据
function loadUrls($saveFile){
    if (!is_writable($saveFile) || !is_readable($saveFile)) {
        die('请将' . $saveFile . '文件设置为可读写权限');
    }
    $json = file_get_contents($saveFile);
    $arr = json_decode($json, true);
    return is_array($arr) ? $arr : array();
}

//保存数据到JSON文件（加锁防并发）
function saveUrls($saveFile, $arr){
    file_put_contents($saveFile, json_encode($arr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}

//输出JSON响应
function jsonResponse($code, $message, $extra = []){
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['code' => $code, 'message' => $message], $extra), JSON_UNESCAPED_UNICODE);
    exit;
}

//验证后台登录状态，未登录则跳转到登录页
function checkAdmin(){
    session_start();
    if(empty($_SESSION['admin_logged'])){
        header('Location: login.php');
        exit;
    }
}
