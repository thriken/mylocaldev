<?php
$album_id=0;
$url=$_GET[url];
$arr=parse_url($url)[fragment];
//print_r($arr);
$arr = explode("&",$arr);
$arr1 = explode("=",$arr[0]);
$arr2 = explode("=",$arr[1]);
echo $hash=$arr1[1];
echo $album_id=$arr2[1];
header("Location:/kugou/json.php?hash=".$hash."&album_id=".$album_id);
?>