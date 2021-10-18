<html>
	<head>
		<meta charset="utf-8" />
		<title>5sing</title>
	</head>
	<body>
<?php
$id =( ($_GET['id'] == null )?  '3711624' :$_GET['id']);
$type =( ($_GET['t'] == null )?  'yc' :$_GET['t']);
	$json="http://service.5sing.kugou.com/song/getSongUrl?songid=$id&songtype=$type&from=web&version=6.6.72";
    //$json="http://mobileapi.5sing.kugou.com/song/getSongUrl?songid=$id&songtype=$type";
	$rurl = "http://5sing.kugou.com/$type/$id.html";
	echo "<!--$json-->";
	$requesturl = $json;
	//curl方式获取json数组
	$curl = curl_init();
	//初始化
	curl_setopt($curl, CURLOPT_URL, $requesturl);
	//设置抓取的url
	curl_setopt($curl, CURLOPT_HEADER, 0);
	//设置头文件的信息作为数据流输出
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	//伪装referer
	curl_setopt ($curl, CURLOPT_REFERER, $rurl); 
	//设置获取的信息以文件流的形式返回，而不是直接输出。
	$data = curl_exec($curl);
	//执行命令
	curl_close($curl);
	//关闭URL请求
	//显示获得的数据
    $data = substr($data,1);
    $data = explode(")",$data)[0];
	//print_r($data);

	$de_json = json_decode($data);
	//查看错误代码
	//echo 'json error code:'.json_last_error().'->'. json_last_error_msg();
	//print_r($de_json->data);
	$song=$de_json->data;
    $songn=$song-> songName;
 	$songtype = $song -> songtype;
	//SuperQuality
	$squrl = $song -> squrl;
	$squrlmd5 = $song -> squrlmd5;
	$sqsize = $song -> sqsize;
	$sqext = $song -> sqext;
	//High Quality
	$hqurl = $song -> hqurl;
	$hqurlmd5 = $song -> hqurlmd5;
	$hqsize = $song -> hqsize;
	$hqext = $song -> hqext;
	//Low Quality
	$lqurl = $song -> lqurl;
	$lqurlmd5 = $song -> lqurlmd5;
	$lqsize = $song -> lqsize;
	$lqext = $song -> lqext;

function fsizefmt($num){
    $fsize=null;
    if($num>1024*1024){
        $num /= pow(1024, 2);
        $fsize = number_format($num,2) . "MB";
    }else{
        $num /= pow(1024, 1);
        $fsize = number_format($num,1) . "KB";
    }
	return $fsize;
}
function ourl($url){
	$url_domain='//data.5sing.kgimg.com';
	$link=explode("/",$url);
	$link2=$link[0].$url_domain.'/'.$link[5].'/'.$link[6].'/'.$link[7].'/'.$link[8].'/'.$link[9];
	return $link2;
}

?>

<?php 
 if ($songtype=='yc')  $t='原创'; 
 if ($songtype=='fc')  $t='翻唱'; 
 if ($songtype=='bz')  $t='伴奏'; 

echo("<p>歌曲地址：<a target=_blank href=".$rurl.">$rurl</a></p>");
echo "歌曲名称：".$songn."&nbsp;&nbsp;&nbsp;&nbsp;";
echo "歌曲类型：".$t;
echo $lqsize !=0 ? ("<p><a target=_blank href=".ourl($lqurl).">普通音质</a> &nbsp; 大小：".fsizefmt($lqsize)."</p>") : null;
echo $hqsize !=0 ? ("<p><a target=_blank href=".ourl($hqurl).">&nbsp;高&nbsp;音&nbsp;质</a> &nbsp;&nbsp; 大小：".fsizefmt($hqsize)."</p>"):null;
echo $sqsize !=0 ? ("<p><a target=_blank href=".ourl($squrl).">超高音质</a> &nbsp; 大小：".fsizefmt($sqsize)."</p>"):null;
?>
	</body>
</html>