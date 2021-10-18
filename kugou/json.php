<html>
	<head>
	<meta charset="utf-8" />
		<style>
			input[readonly=readonly]{
				display: none;
			}
		</style>
	</head>
	<body>
	<?php
	$hash = $_GET['hash'];
	$album_id=$_GET['album_id'];
	$json = 'http://www.kugou.com/yy/index.php?r=play/getdata&hash='.$hash.'&album_id='.$album_id;
	echo "<!--$json-->";
	//$json = 'http://www.kugou.com/yy/index.php?r=play/getdata&hash=936051EA140E3CFACB629D2BFAF430D7&album_id=504194';
	$requesturl = $json;
	//curl方式获取json数组
	$curl = curl_init();
	//初始化
	curl_setopt($curl, CURLOPT_URL, $requesturl);
	//设置抓取的url
	curl_setopt($curl, CURLOPT_HEADER, 0);
	//设置头文件的信息作为数据流输出
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//设置获取的信息以文件流的形式返回，而不是直接输出。
	$data = curl_exec($curl);
	//执行命令
	curl_close($curl);
	//关闭URL请求
	//显示获得的数据
	//print_r($data);

	$de_json = json_decode($data);
	//查看错误代码
	//echo 'json error code:'.json_last_error().'->'. json_last_error_msg();
	//print_r($de_json);
	//echo '</br><hr>';

	//获取songs 节点
	$song = $de_json -> data;
	$fike = $song -> audio_name;
	$sn   = $song -> song_name;
	$url  = $song->play_url;
	$filename = $fike. substr($url, -4);
	//print_r($song);
	if( is_null($_GET['d'])){
	echo("歌曲名：" . $sn);
	echo '</br><hr>';
	//艺术家
	echo("歌手：" . $song -> author_name);
	echo("</br>歌手图片：" . $song-> img);
	echo("</br>歌手ID：" . $song-> author_id);
	echo '</br><hr>';
	//唱片
	if($song->have_mv ==1 ) echo('<a href="http://www.kugou.com/mvweb/html/mv_'.$song->video_id.'.html">MV观看</a>');
	if($song->have_album ==1 ) echo('</br>专辑名：<a href="http://www.kugou.com/album/'.$song->album_id.'.html">'. $song -> album_name.'</a>');
	echo("</br>歌词：" . $song -> lyrics);
?>

<?php
	echo '</br><hr>';
	//文件
	echo("大小：" );
	$num=$song -> filesize;
	$num /= pow(1024, 2);
	echo number_format($num, 3);
    $m =intval( ($song -> timelength % 3600000) / 60000);
    $s  = ($song -> timelength % 60000 ) / 1000;
    $s=sprintf("%.1f",$s);
	echo("MB</br>时长：" . $m.':'. ($s<10? '0'.$s : $s) );
	echo("</br>真实地址：$url<hr>");
	echo("<audio controls src= ".$url." style='width:600px'></audio></br>");
	
	$yurl="http://www.kugou.com/song/#hash=".$hash;
	echo "歌曲地址：" .$yurl;
	?>

	<form method="get" >
		<input readonly="readonly" type="text"  name="hash" id="hash" value="<?php echo $hash?>"/>
		<input name="d" value="yes" type="text" id="d" readonly="readonly" />
		<input type="submit" value="下载：<?php echo $filename?>"/>
	</form>
<a  target="_blank" href="data:application/octet-stream;base64,<?php echo base64_encode($song -> lyrics)?>" download="<?php echo $fike?>.lrc">歌词下载</a>

<?php
}
//下载部分
if( !is_null($_GET['d'])){
$downfile = str_replace("http://", "", $url);
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
}
?>
	</body>
</html>