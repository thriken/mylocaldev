<html>
	<head></head>
	<body>
	<?php
	$id = $_GET['id'];
	$json = 'http://music.163.com/api/song/detail/?id=' . $id . '&ids=%5B' . $id . '%5D';
	//$json='http://localhost/wy/29722582.json';
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
	$count_json = count($de_json);
	//获取songs 节点
	$song = $de_json -> songs;

	//歌曲名
	$sn = $song[0] -> name;
	echo("歌曲名：" . $sn);
	echo '</br><hr>';
	//艺术家
	$art = $song[0] -> artists;
	echo("歌手：" . $art[0] -> name);
	echo("</br>歌手图片：" . $art[0] -> picUrl);
	echo '</br><hr>';
	//唱片
	$album = $song[0] -> album;
	//print_r($album);
	echo("专辑名：" . $album -> name);
	echo("</br>专辑图片：" . $album -> picUrl);
	echo("</br>发行时间：" . MS_Date($album -> publishTime));
	echo("</br>发行公司：" . $album -> company);
	echo("</br>类型：" . $album -> subType);
	echo '</br><hr>';
	//文件大小
	$bMusic = $song[0] -> bMusic;
	echo("大小：" . $bMusic -> size);
	echo("字节</br>采样率：" . $bMusic -> sr);
	echo("Hz</br>时长：" . $bMusic -> playTime);
	echo("毫秒</br>后缀：" . $bMusic -> extension);
	echo '</br><hr>';
	$filename=$art[0]-> name ." - ".$sn ."." .$bMusic -> extension;
	$u=urlencode($filename);
	$url="d.php?id=" .$id . "&n=" . urlencode($filename) ;
	//echo "<a href= ". $url." >$url</a></br></br>";
	echo '下载：<a href="'.$url.'" target="_blank">'. $filename .'</a>';

date_default_timezone_set('Asia/Shanghai');
echo '<hr/>时间： ' .MS_Time(mtime(microtime()));
function mtime($t) {
	list($msec, $sec) = explode(' ', $t);
   	return $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}
function MS_Time($n) {
	if(is_numeric($n))
		return date('Y-m-d H:i:s.', substr($n, 0, -3)) . substr($n, -3);
	return substr((new DateTime($n))->format('Uu'), 0, -3);
}
function MS_Date($n) {
	if(is_numeric($n))
		return date('Y-m-d', substr($n, 0, -3)) ;
	return substr((new DateTime($n))->format('Uu'), 0, -3);
}
?>
	</body>
</html>