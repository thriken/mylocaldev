<?php
class testView{
  function display($data){
print <<<HTML
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		</head>
	<body>
		<div>
				$data;
		</div>
	</body>
</html>
HTML;
  }
 }
?>