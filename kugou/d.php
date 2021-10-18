<html>
	<head>
	<meta charset="utf-8" />
<?php
	$id=$_GET['id'];
	$filename=$_GET['n'];

	$url="http://music.163.com/song/media/outer/url?id=". $id. ".mp3";
	//$url="http://music.163.com/song/media/outer/url?id=29722582.mp3";
	$url=curl_post_302($url,null);
    // 要下载的文件可能在本地，也可能在其他节点服务器
    
    if (empty($url) || false === @fopen($url, 'rb')) {
        exit('文件不存在');
    }

    // 处理中文名
    //$filename = basename($url);
    $user_agent  = $_SERVER["HTTP_USER_AGENT"];
    if (false !== stripos($user_agent, 'Trident')) {
        $filename = rawurlencode($filename);
    }

    // 文件目标
    list($host, $dl_file) = explode('download', $url);
    $server_addr = $_SERVER['SERVER_ADDR'];
    if (false !== stripos($host, $server_addr)) {
        echo ' 本地文件';
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('X-Accel-Redirect: /download' . $dl_file);
        exit;
    } else {
        // 文件在其他节点服务器
        $header = get_headers($url, 1);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($url);
        header('Content-Length: ' . $header['Content-Length']);
        exit;
    }
    
	function  curl_post_302($url, $vars) {
     $ch = curl_init();
     curl_setopt($ch,  CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_URL,  $url);
     curl_setopt($ch, CURLOPT_POST, 1);
     curl_setopt($ch,  CURLOPT_FOLLOWLOCATION, 1); // 302 redirect
     curl_setopt($ch,  CURLOPT_POSTFIELDS, $vars);
     $data = curl_exec($ch);
     $Headers =  curl_getinfo($ch);
     curl_close($ch);
     if ($data != $Headers)
     return  $Headers["url"];
     else
     return false;
	}