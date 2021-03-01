<?php 
$url = 'http://fs.5sing.kgimg.com/201808021213/eba6362ea5259812754db7e74ab72d61/G090/M01/18/05/-oYBAFtToeOAKrcJAJcn10UiAdU188.mp3';
($_GET['url']!=null)?$url=$_GET['url']:null;
$url_domain='//data.5sing.kgimg.com';
$link=explode("/",$url);
$link2=$link[0].$url_domain.'/'.$link[5].'/'.$link[6].'/'.$link[7].'/'.$link[8].'/'.$link[9];
$u = '<a href=http:' .$link2 .'>mp3</a>';
echo $url;
echo "<br/>";
echo $link2;
?> 