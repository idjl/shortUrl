# 不用数据库的短网址V2.0

未使用数据库，利用php的数组保存数据，可以放二级文件夹使用

生成二维码改为jquery.qrcode.js

生成短网址的形式：默认是5位小写字母和数字，可选从1开始依次增加的数字


#### 使用说明

0、将所有文件复制到你的项目中。

1、urls123.php和urlsabc.php文件需设置为777权限

2、修改config.php

```html
'title' => "短网址演示",                     //网站标题
'site' => "https://51015.cn/demo/shortUrl",  //短网址域名
//不允许缩短的域名，单个匹配，*表示所有的二级域名
'blackList' => array('*.51015.cn','baidu1.com','youku1.com'),
'key' => "idjl",                             //token 使用的密钥

//根据需求修改
'use_rewrite' => 1,                          // 是否使用伪静态,默认使用
//生成的短网址类型：abc表示字母数字混合，123为纯数字累加方式
'type' => 'abc',
```

3、访问你设置的短网址域名/index.php

4、如果你使用了这套代码，请给本项目点个Star

#### 演示地址

https://51015.cn/demo/shortUrl/index.php


#### 伪静态的使用


默认开启伪静态，可以关闭，config.php中修改'use_rewrite' => 2即可。

apache可以直接使用；

nginx需要配置文件中引入.htaccess-nginx

nignx引入教程：https://blog.csdn.net/u010071211/article/details/85689930




