<?php

/**
 * @Author: Ding Jianlong
 * @Date:   2019-03-20 22:44:12
 * @Last Modified by:   Ding Jianlong
 * @Last Modified time: 2019-03-24 23:06:22
 */


//配置文件


$config = array(
	'title' => "短网址演示",                     //网站标题
	'site' => "https://51015.cn/demo/shortUrl",  //短网址域名
	//不允许缩短的域名，单个匹配，*表示所有的二级域名
	'blackList' => array('*.51015.cn','baidu1.com','youku1.com'),
	'key' => "idjl",                             //token 使用的密钥

	//根据需求修改
	'use_rewrite' => 1,                          // 是否使用伪静态,默认使用
	//生成的短网址类型：abc表示字母数字混合，123为纯数字累加方式
	'type' => 'abc',
);