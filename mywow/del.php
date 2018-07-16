<?php
    include("config.php"); //����������ݿ�
    header ("Content-Type: text/html; charset=utf-8");
    if (!empty($_GET['id'])) {
        $del = $_GET['id'];  //ɾ��blog
        $sql= "delete from blog where id='$del' ";
        mysql_query($sql);
        echo "delete success!";

    }

?>