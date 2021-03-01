<?php
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
?>