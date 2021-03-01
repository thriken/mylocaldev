<?php
require_once('controller/postController.class.php');
require_once('model/postModel.class.php');
require_once('view/postView.class.php');
if(empty($_GET["pid"])){
	$pid=0;
}else{
	$pid=$_GET["pid"];
}
$postController = new postController();//调用控制器
if($pid>0){
	$postController->show($pid);
}else{
	$postController->getIndexList(10);
}
?>