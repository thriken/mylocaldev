<?php
header ("Content-Type: text/html; charset=utf-8");

include("config.php"); //数据库连接
	$rsa=null;
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $sql ="select * from class  where cid=".$id;    
		//echo $sql;
        $query = mysqli_query($con,$sql);
		while($rs = mysqli_fetch_array($query)){
			$rsa=$rs;
		}
    }

?>
ID: <?php echo $rsa['cid']; ?> </br>
职业: <?php echo $rsa['cname']; ?>  </br>
英文名: <?php echo $rsa['cname_en']; ?>
