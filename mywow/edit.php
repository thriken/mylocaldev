<?php
include("config.php"); 
header ("Content-Type: text/html; charset=utf-8");

if (!empty($_GET['id'])) {
	$edit = $_GET['id'];
	$sql = "select * from blog where id='$edit'";
	$query = mysql_query($sql);
	$rs = mysql_fetch_array($query);
}


if (!empty($_POST['sub'])) {
	$title = $_POST['title'];  
	$con = $_POST['con'];     
	$hid = $_POST['hid'];
	$sql= "update blog set title='$title', contents='$con' where id='$hid' ";
	mysql_query($sql);
	echo "<script>alert('update success.');location.href='index.php'</script>";

}

?>

<form action="edit.php" method="post">
    <input type="hidden" name="hid" value="<?php echo $rs['id'];?>">
    title   :<br>
    <input type="text" name="title" value="<?php echo $rs['title'];?>">
    <br><br>
    contents:<br>
    <textarea rows="5" cols="50" name="con" ><?php echo $rs['contents'];?></textarea><br><br>
    <input type="submit"  name="sub" value="submit">
    
</form>