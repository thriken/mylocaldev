<?php
class postController{
  function show($pid){
  $post= new postModel();
		$data = $post->getPostById($pid);
	  $postView= new postView();
		$postView->display($data);


	
      //$testModel = new testModel();//选取合适的模型
      //$data = $testModel->get();//获取相应的数据
      //$testView = new testView();//选择相应的视图
      //$testView->display($data);//展示给用户
  }
  function  getIndexList(){
  	$num=10;
  	$post= new postModel();
		$data = $post->getIndexList($num);
		$postView= new postView();
		$postView->displayIndex($data);
  }
}
?>
