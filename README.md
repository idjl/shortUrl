# 不用数据库的短网址

一直没找到一个好用的网址缩短工具，所以自己写了一个，短网址是依次增加的数字

前端为bootstrap自适应，后端利用php的数组保存短网址，key是短网址的id，value是长网址


#### 使用说明

需要urls.php文件修改为777权限

修改index.php中的$siteTitle和$siteUrl即可，可以放二级文件夹使用

```html
$siteTitle = "51015.cn/d短网址";
$siteUrl = "https://51015.cn/d";
```

#### 演示图片

![输入图片说明](https://raw.githubusercontent.com/idjl/shortUrl/master/%E6%88%AA%E5%9B%BE/%E6%88%AA%E5%9B%BE1.jpg)

![输入图片说明](https://raw.githubusercontent.com/idjl/shortUrl/master/%E6%88%AA%E5%9B%BE/%E6%88%AA%E5%9B%BE2.jpg)


#### 新增分支randChar，短网址形式为：5位小写字母和数字混合

git clone https://github.com/idjl/shortUrl.git

git checkout randChar

