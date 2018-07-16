<?php
header ("Content-Type: text/html; charset=utf-8");
$con = mysql_connect("127.0.0.1:3306", "root", "");
if (!$con) {
	die("mysql服务器连接失败。");
}
@mysql_select_db("mywow") or die("数据库连接失败。");
mysql_query("set names 'UTF8'");


$arr_pro=["专业","附魔","珠宝加工","铭文","工程","锻造","制皮","裁缝","炼金","侏儒工程学","地精工程学","草药学","剥皮","采矿"];
$arr_class=["职业","战士","法师","猎人","潜行者","牧师","术士","萨满祭司","德鲁伊","圣骑士","死亡骑士","武僧","恶魔猎手"];
$arr_class_en=["class","warrior","mage","hunter","rogue","priest","warlock","shaman","druid","paladin","dk","monk","dh"];
$arr_race=["种族","兽人","牛头人","巨魔","亡灵","血精灵","地精","熊猫人(部落)","至高岭牛头人(部落)","堕夜精灵(部落)","矮人","侏儒","人类","暗夜精灵","德莱尼","狼人","熊猫人(联盟)","虚空精灵(联盟)","光铸德莱尼(联盟)"];

?>