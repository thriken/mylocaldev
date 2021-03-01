<html>
<head>
<meta charset="UTF-8"/> 
</head>
<body>
<?php
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

     $id =( ($_GET['id'] == null )?  '1776020' :$_GET['id']);
	$url=curl_post_302('http://music.163.com/song/media/outer/url?id='.$id.'.mp3',null);

?>
<span style="padding-left:20px;width:900px;word-wrap:break-word;">mp3真实地址：<?echo $url ?></span>
<div>
<audio controls="controls" autoplay="no">
<source src="<?echo $url ?>" type="audio/mpeg" />
您的浏览器不支持 audio 标签。
</audio>
</div>
</body>
