<?php
// 修改此处，比较简单就没有单独提取config
$siteTitle = "51015.cn/ss短网址";        //网站标题
$siteUrl = "https://www.51015.cn/ss";   //短网址域名
$file = 'urls.php';               // 本地存放 URLS 的文件
$use_rewrite = 1;                 // 是否使用伪静态
// 以下内容根据需要修改
error_reporting(E_ALL ^ E_NOTICE);//显示除去 E_NOTICE 之外的所有错误信息
ob_start();   //开启ob缓存
?>
    <!DOCTYPE html>
    <html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <title>
            <?php echo $siteTitle; ?>
        </title>
        <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/index.css">
        <!--[if lt IE 9]>
        <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>



<?php
// 检查本地文件是否可读写
if(!is_writable($file) || !is_readable($file)){
	die('请将本地保存URLS的文件设置为777权限');
}

// 后面跟着id跳转，没有则展示生成网址页面
$action = trim($_GET['id']);
echo $action;
$action = ($action === '') ? 'create' : 'redirect';

//匹配网址的正则
$regex = "/^(https?:\/\/)([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?$/";
$output = '';   //提示错误信息

//引入urls.php文件
$arr = array();
if(file_get_contents('urls.php')){
    $arr = include('urls.php');       //安全隐患，应当禁用eval
}
//生成
if($action == 'create'){
    if(isset($_POST['create'])){
        $id = createId(5);    //随机生成5位小数字母+数字
		$url = trim($_POST['url']);
		if($url == ''){
			$output = '<strong>没有输入URL地址</strong>';
		}else{
			if(@preg_match($regex, $url)){
                //重复网址则返回键值
                $find = array_search($url,$arr);
                if($find !== false){
                    $id = $find;
                }else{
                    $id = checkId($arr,$id);
                    //原来没有新插入
                    $arr[$id] = $url;
                    //var_dump($arr);

                    $a = '<?php'.PHP_EOL.'return '.var_export($arr,true).';';
                    file_put_contents('urls.php', $a);
                }

                //{$id}前面加入了一个/
                $shorturl = ($use_rewrite == 1) ? "{$siteUrl}/{$id}" : "{$siteUrl}/index.php?id={$id}";
            }else{
				$output = '<strong>无效的URL.</strong>';
			}
		}
	}
}
//跳转
if($action == 'redirect'){
	$id = trim($_GET['id'],' ');
	echo $id;
    if($id !== ''){
        if($arr[$id]){
            header('location:'.$arr[$id]);
        }
        die("不存在此短网址");
    }
}
?>

<div class="container">
    <h1 class="title text-center">
        <?php echo $siteTitle; ?>
    </h1>
    <form action="<?=$_SERVER['PHP_SELF']?>" method="post">
        <div class="text-center">
            <div class="input-group input-group-lg">
                <label for="url">网址：</label>
                <input type="text" name="url" id="url" class="content form-control" placeholder="请输入以http://或https://开头的网址" <?php if(isset($url)){echo "value=".$url;} ?>><br>
            </div>
            <button class="btn btn-lg btn-danger" type="submit" name="create">生成短网址</button>
        </div>
    </form>
    <div class="text-center">
        <?php if($shorturl): ?>
        <h3>
            <?php echo $shorturl; ?>
        </h3>
        <div class="qrcode" style="background-image:url('http://qr.liantu.com/api.php?text=<?php echo $shorturl; ?>');"></div>
        <?php endif; echo '<h3 class="text-warning">'.$output.'</h3>'; ?>

        <p class="friendLink">
            <span>友情链接</span>
            <a href="http://t.tl/" target="_blank">t.tl短网址</a>
            <a href="http://dwz.cn/"  target="_blank">百度短网址</a>
            <a href="https://github.com/idjl/shortUrl"  target="_blank">源码下载</a>
        </p>
    </div>

</div>
<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
</body>
</html>

<?php
ob_end_flush();

//生成大小写字母和数字随机字符串
function createId($len){
    //大小写字母和数字混用
    //$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    //小写字母和数字混用
    $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $key = '';
    for($i = 0;$i < $len;$i++){
        $key .= $str{mt_rand(0,strlen($str)-1)};
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

?>