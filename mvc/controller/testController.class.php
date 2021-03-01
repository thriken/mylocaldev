<?php
class testController{
  function show(){
      $testModel = new testModel();//选取合适的模型
      $data = $testModel->get();//获取相应的数据
      $testView = new testView();//选择相应的视图
      $testView->display($data);//展示给用户
  }
}
?>
