<?php
//! Model类
/**
* 它的主要部分是对应于留言本各种数据操作的函数
* 如：留言数据的显示、插入、删除等
*/

class Model {

var $dao; //DataAccess类的一个实例（对象）

//! 构造函数
/**
* 构造一个新的Model对象
* @param $dao是一个DataAccess对象
* 该参数以地址传递（&$dao）的形式传给Model
* 并保存在Model的成员变量$this->dao中
* Model通过调用$this->dao的fetch方法执行所需的SQL语句
*/
function __construct(&$dao) {
$this->dao=$dao; 
}

function listNote() {    //获取全部留言
$this->dao->fetch("SELECT * FROM note");                
}

function postNote($name,$content) {    //插入一条新留言
$sql = "INSERT INTO `test`.`note`
(`id`, `name`, `content`, `ndate`, `add`)
VALUES (NULL, '$name', '$content', NULL, NULL);";
//echo $sql;  //对于较复杂的合成SQL语句，<br />
//调试时用echo输出一下看看是否正确是一种常用的调试技巧
$this->dao->fetch($sql);
}

function deleteNote($id) {   //删除一条留言，$id是该条留言的id
$sql = "DELETE FROM `test`.`note` WHERE `id`=$id;";
//echo $sql;
$this->dao->fetch($sql);
}


function getNote() {    //获取以数组形式存储的一条留言
//View利用此方法从查询结果中读出数据并显示
if ( $note=$this->dao->getRow() )
return $note;
else
return false;
}
}
?>