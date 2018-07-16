<?php
header ("Content-Type: text/html; charset=utf-8");

include("config.php"); //数据库连接

    if (!empty($_GET['id'])) {
        $id = $_GET['id'];
        $sql ="select * from class  where cid='$id' ";    
        $query = mysql_query($sql);
        $rs = mysql_fetch_array($query);
    }

?>
ID: <?php echo $rs['cid']; ?> 
职业: <?php echo $rs['cname']; ?>  
英文名: <?php echo $rs['cname_en']; ?>
