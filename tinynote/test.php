<?php
require_once('lib/DataAccess.php');
require_once('lib/Model.php');

$dao=& new DataAccess ('localhost','root','123456','test');
$model=& new Model($dao);
$model->listNote();

while ($note=$model->getNote())
{
$output.="姓名：$note[name]<br> 留言：<br> $note[content] <br> <hr />";
}
echo $output;
?>