<?php
    include("config.php"); 

    if (!empty($_GET['id'])) {
        $del = $_GET['id'];  
        $sql= "delete from blog where id=".$del;
        mysqli_query($con,$sql);
        echo "delete success!";

    }

?>