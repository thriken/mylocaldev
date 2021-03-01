<?php
class postView{
  function display($data){
print <<<HTML
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		</head>
	<body>
	<div id="header">
		<a href="./" >返回首页</a>
	</div>
		<div>
				<h3>{$data['title']}{$data['pid']}</h3>
				<p>{$data['author']} </br>{$data['content']}</p>
		</div>
	</body>
</html>
HTML;
  }
    function displayIndex($data){
echo '<html>';
echo '	<head>';
echo '<meta http-equiv="content-type" content="text/html;charset=utf-8">';
echo '	</head>';
echo '<body>';
echo '<div>';
			foreach($data as $post){
				echo '<h3><a href="?pid=' .$post['pid'] .'" >'.$post['title'].'</a></h3>';
				echo '<p>' .$post['author'] .' </br>' .$post['content'] .'</p>';
				echo '<hr/>';
				}
echo "		</div>";
echo "	</body>";
echo "</html>";
  }
 }
?>