<?php header("Content-Type: text/html; charset=utf-8");
include ("config.php");
if (!empty($_POST['sub'])) {
  $hanme = $_POST['hname'];
  $rid = $_POST['rid'];
  $cid = $_POST['cid'];
  $pid1 = $_POST['pid1'];
  $p1 = $_POST['p1'];
  $pid2 = $_POST['pid2'];
  $p2 = $_POST['p2'];
  $pp1 = $_POST['pp1'];
  $pp2 = $_POST['pp2'];
  $pp3 = $_POST['pp3'];
  $pp4 = $_POST['pp4'];
  $pp5 = $_POST['pp5'];
  $pp6 = $_POST['pp6'];
  $sql= "insert into hero values(null,'0','$title',now(),'$con')";
  mysql_query($sql);
  echo "insert success!";
  echo $sql;
}
?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="style.css" />
		<title>添加角色</title>
	</head>
	<body>
				<div id="header">
			<a href="index.php">Index</a> | <a href="addhero.php">Add</a> | And So on
		</div>
		<div id="head">
			<h3>添加角色到我的数据库</h3>
			</div>
		<form action="?" method="post">
			<div>
				<span>角色：</span>
				<input name="hname" id="hname" />
			</div>
			<div>
				<span>种族：</span>
				<select name="rid">
					<?php
$sql="select * from race";
$query=mysqli_query($sql);
while($rs = mysql_fetch_array($query)){
?>
<option value="<? echo $rs['rid']; ?>">  <? echo $rs['rname']; ?> </option>
<? } ?>
				</select>
				<span id="faction" >&nbsp;&nbsp;&nbsp;&nbsp;</span>
			</div>
			<div>
				<span>职业：</span>
				<select name="cid">
					<?php
$sql="select * from class";
$query=mysql_query($sql);
while($rs = mysql_fetch_array($query)){
?>
<option class="<?php echo $rs['cname_en']; ?>" value="<? echo $rs['cid']; ?>">  <? echo $rs['cname']; ?> </option>
<? } ?>
				</select>
				
			</div>

			<div>
				<span>专业：</span>
				<select name="pid1">
					<?php
$sql="select * from profession";
$query=mysql_query($sql);
while($rs = mysql_fetch_array($query)){
?>
<option value="<? echo $rs['pid']; ?>">  <? echo $rs['profession']; ?> </option>
<? } ?>
				</select>
				点数：
				<input name="p1" />
			</div>
			<div>
				<span>专业：</span>
				<select name="pid2">
					<?php
$sql="select * from profession";
$query=mysql_query($sql);
while($rs = mysql_fetch_array($query)){
?>
<option value="<? echo $rs['pid']; ?>">  <? echo $rs['profession']; ?> </option>
<?
}
					?>
				</select>
				点数：
				<input name="p2" />
			</div>
			<div>
				<span>急救：</span>
				<input name="pp1"/>
			</div>
			<div>
				<span>钓鱼：</span>
				<input name="pp2"/>
			</div>
			<div>
				<span>烹饪：</span>
				<input name="pp3"/>
			</div>
			<div>
				<span>食谱：</span>
				<input name="pp4"/>
			</div>
			<div>
				<span>考古：</span>
				<input name="pp5"/>
			</div>
			<div>
				<span>神器：</span>
				<input name="pp6"/>
			</div>
			<input type="submit"  name="sub" value="添加">
		</form>
	</body>
</html>