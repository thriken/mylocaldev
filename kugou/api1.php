<?php
/*音乐部分*/
/*歌曲hash值*/
$music_hash="91b5a3ba1420b1156afd7918c1af27e1";
 
/*可获取所有免费的酷狗音乐（含无损）*/
echo "http://trackercdn.kugou.com/i/v2/?cmd=25&key=".md5(strtolower($music_hash)."kgcloudv2")."&hash={$music_hash}&pid=1&behavior=download<br/>";
 
/*可获取所有免费的酷狗音乐（含无损）及收费音乐的128Kbps音质（除 需购买专辑的收费音乐）*/
$mid="0";//随意 必填
$userid="0";//随意 必填
$appid="1005";//必填1005
echo "http://trackercdn.kugou.com/i/v2/?cmd=26&key=".md5(strtolower($music_hash)."kgcloudv2".$appid.$mid.$userid)."&hash={$music_hash}&pid=1&behavior=play&mid={$mid}&appid={$appid}&userid={$userid}&version=0&vipType=0&token=0<br>";
 
/*MV部分*/
/*MV HASH值*/
$mv_hash="1855DD770F35100F4BEF9C4634B42D55";
echo     "http://trackermv.kugou.com/interface/index/cmd=4&hash=".strtoupper($mv_hash)."&key=".md5(strtoupper($mv_hash)."kgcloud")."&ext=mp4<br/>";echo "http://trackermv.kugou.com/interface/index/cmd=100&hash=".strtoupper($mv_hash)."&key=".md5(strtoupper($mv_hash)."kugoumvcloud")."&pid=6&ext=mp4&ismp3=0<br/>";
echo "http://trackermv.kugou.com/interface/index/cmd=103&hash=".strtoupper($mv_hash)."&key=".md5(strtoupper($mv_hash)."kugoumvcloud")."&pid=6&ext=mp4&ismp3=0<br/>";
echo "http://trackermv.kugou.com/interface/index/cmd=104&hash=".strtoupper($mv_hash)."&ext=mp4<br/>";
 
 
?>