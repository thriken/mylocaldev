<?php
//! View 类
/**
* 针对各个功能（list、post、delete）的各种View子类
* 被Controller调用，完成不同功能的网页显示
*/
class View {

var $model;  //Model对象

var $output; //用于保存输出HTML代码的字符串

//! 构造函数
/**
* 将参数中的Model对象接收并存储在成员变量$this->model中
* 供子类通过model对象获取数据
*/
function __construct (&$model) {
$this->model=$model; 
}

function display() {  //输出最终格式化的HTML数据
echo($this->output);
}
}

class listView extends View   //显示所有留言的子类
{
function __construct(&$model)
{
parent::__construct(&$model);   //继承父类的构造函数（详见Controller）
$this->model->listNote();
while ($note=$this->model->getNote())  //逐行获取数据
{
$this->output.="姓名：$note[name]<br> 留言：<br> $note[content]     
<a href=\"".$_SERVER['PHP_SELF']."?action=delete&id=$note[id]\">删除</a><br> <hr />";
}           
}
}

class postView extends View  //发表留言的子类
{
function __construct(&$model, $post)
{
parent::__construct(&$model);
$this->model->postNote($post[name],$post[content]);
$this->output="Note Post OK!<br><a href=\"".$_SERVER['PHP_SELF']."?action=list\">查看</a>";
}
}

class deleteView extends View  //删除留言的子类
{
function __construct(&$model, $id)
{
parent::__construct(&$model);
$this->model->deleteNote($id);
$this->output="Note Delete OK!<br><a href=\"".$_SERVER['PHP_SELF']."?action=list\">查看</a>";  
}
}
?>