<?php

//! Controller
/**
* 控制器将$_GET['action']中不同的参数（list、post、delete）
* 对应于完成该功能控制的相应子类
*/

class Controller {
var $model;  // Model 对象 
var $view;   // View  对象

//! 构造函数
/**
* 构造一个Model对象存储于成员变量$this->model;
*/
function __construct (& $dao) {
$this->model=& new Model($dao);
}


function getView() {    //获取View函数
//返回视图对象view
//对应特定功能的Controller子类生成对应的View子类的对象
//通过该函数返回给外部调用者
return $this->view;
}


}

//用于控制显示留言列表的子类
class listController extends Controller{   //extends表示继承  

function __construct (& $dao) {
parent::__construct($dao);  //继承其父类的构造函数
//该行的含义可以简单理解为：
//将其父类的构造函数代码复制过来      
$this->view=& new listView($this->model);
//创建相应的View子类的对象来完成显示
//把model对象传给View子类供其获取数据        
}
}

//用于控制添加留言的子类
class postController extends Controller{

function __construct (& $dao, $post) {
parent::__construct($dao);
$this->view=& new postView($this->model, $post);
//$post的实参为$_POST数组
//表单中的留言项目存储在该系统数组中        
}
}

//用于控制删除留言的子类
class deleteController extends Controller{
function __construct (& $dao, $id) {
parent::__construct($dao);
$this->view=& new deleteView($this->model, $id);        
}
}
?>