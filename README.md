# 不用数据库的短网址

一直没找到一个好用的网址缩短工具，所以自己写了一个

shortUrl123短网址是依次增加的数字；shortUrlabc短网址是随机产生小写字母和数字混合形式；两者相互独立，互不影响。

前端为bootstrap自适应，后端利用php的数组保存短网址


#### 使用说明

将shortUrl123（或shortUrlabc）文件夹内所有文件复制到你的项目中。

需要urls.php文件修改为777权限

修改index.php中的$siteTitle和$siteUrl即可，可以放二级文件夹使用

```html
$siteTitle = "51015.cn/ss短网址";
$siteUrl = "https://51015.cn/ss";
```

#### 演示图片

![输入图片说明](https://raw.githubusercontent.com/idjl/shortUrl/master/%E6%88%AA%E5%9B%BE/1.jpg)

![输入图片说明](https://raw.githubusercontent.com/idjl/shortUrl/master/%E6%88%AA%E5%9B%BE/2.jpg)


#### 伪静态的使用

默认开启伪静态，可以关闭

apache可以直接使用；nginx需要配置文件中引入.htaccess-nginx

nignx引入教程：https://blog.csdn.net/u010071211/article/details/85689930

#### 二维码生成接口

https://tool.kd128.com/qrcode?text=https://51015.cn

https://bshare.optimix.asia/barCode?site=weixin&url=https://51015.cn

