<?
$url = str_replace('/referer/file.php/', '', $_SERVER["REQUEST_URI"]);
//得出需要转换的网址。这里我就偷懒，不做安全检测了，需要的自己加上去
echo $url;
$downfile = str_replace(" ", "%20", $url);
//替换空格之类，可以根据实际情况进行替换
$downfile = str_replace("http://", "", $downfile);
//去掉http://
$urlarr = explode("/", $downfile);
//以"/"分解出域名
$domain = $urlarr[0];
//域名
$getfile = str_replace($urlarr[0], '', $downfile);
//得出header中的GET部分
$content = @fsockopen("$domain", 80, $errno, $errstr, 12);
//连接目标主机
if (!$content) {//链接不上就提示错误
	die("<p>对不起，无法连接上 $domain 。</p>");
}
fputs($content, "GET $getfile HTTP/1.0\r\n");
fputs($content, "Host: $domain\r\n");
fputs($content, "Referer: $domain\r\n");
//伪造部分
fputs($content, "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)\r\n\r\n");

while (!feof($content)) {
	$tp .= fgets($content, 128);
	if (strstr($tp, "200 OK")) {//这里要说明一下。header的第一行一般是请求文件的状态。具体请参照HTTP 1.1状态代码及其含义hi.baidu.com/110911/blog/item/21f20d2475af812ed50742c5.html这里 是正常的文件请求状态，只需直接转向就可以。其他状态的继续执行程序
	    $header = get_headers($url, 1);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($url);
        header('Content-Length: ' . $header['Content-Length']);
		//header("Location:$url");
		die();
	}
}

//302 转向，大部分的防盗链系统都是先判断referfer，对了的话再转向真实的地址。下面就是获取真实的地址。
$arr = explode("\n", $tp);
$arr1 = explode("Location: ", $tp);
//分解出Location后面的真时地址
$arr2 = explode("\n", $arr1[1]);
header('Content-Type:application/force-download');
//强制下载
header("location:" . $arr2[0]);
//转向目标地址
die();
?>
