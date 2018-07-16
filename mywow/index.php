<?php
include("config.php"); 
?>
<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style.css" />
	<title>我的魔兽角色</title>
	</head>
	<body>
		<div id="header">
			<a href="index.php">Index</a> | <a href="addhero.php">Add</a> | And So on
		</div>
<div>
	<table >
		<tr>
			<td>角色</td>
			<td>种族</td>
			<td>职业</td>
			<td>专业</td>
			<td>点数</td>
			<td>专业</td>
			<td>点数</td>
			<td>急救</td>
			<td>钓鱼</td>
			<td>烹饪</td>
			<td>食谱</td>
			<td>考古</td>
			<td>神器</td>
		</tr>

<?php

    $sql ="select * from hero";
    $query = mysqli_query($con,$sql);
    while ($rs = mysqli_fetch_array($query)) {
		
    	$hid=$rs['hid'];//角色ID
		$hname=$rs['hname']; //角色名
		
		$rid=$rs['rid'];//种族ID
		
		$race;
		//获取种族名称和阵营ID
		if($rs['rid'] >0 ){
			if($rs['rid']<=9)
				$faction="部落";
			else
				$faction="联盟";
			$race=$arr_race[$rid]; //种族
		}
		
		$cid=$rs['cid'];//职业ID
		//获取职业信息
		$class=$arr_class[$rs['cid']];
		$class_en=$arr_class_en[$rs['cid']];
		
		$pid1=$rs['pid1'];
		$p1=$rs['p1'];
		$pid2=$rs['pid2'];
		$p2=$rs['p2'];
		$pp1=$rs['pp1'];
		$pp2=$rs['pp2'];
		$pp3=$rs['pp3'];
		$pp4=$rs['pp4'];
		$pp5=$rs['pp5'];
		$pp6=$rs['pp6'];
?>
		<tr>
    		<td  class='<?php echo $race;  ?>' ><a href="edit.php?hid=<?php echo $hid; ?>"><?php echo $hname; ?></a> </td>
			<td><?php echo $race; ?></td>
			<td><?php echo $class; ?></td>
			<td><?php echo $pid1; ?></td>
			<td><?php echo $p1; ?></td>
			<td><?php echo $pid2; ?></td>
			<td><?php echo $p2; ?></td>
			<td><?php echo $pp1; ?></td>
			<td><?php echo $pp2; ?></td>
			<td><?php echo $pp3; ?></td>
			<td><?php echo $pp4; ?></td>
			<td><?php echo $pp5; ?></td>
			<td><?php echo $pp6; ?></td>

		</tr>
<?php
} 

?>
	</table>
</div>
	</body>
</html>

