<?php
	class postModel{
		function getPostById($pid){
			$post=array("pid"=>$pid,"title"=>"标题不够长","author"=>"admin","content"=>"内容就这么多");
			return  $post;
		}
		function getIndexList($num){
			$posts = array();
			if($num<5) $num=10;
			for($i=1;$i<11;$i++){
				$post=array("pid"=>$i,"title"=>"标题不够长".$i,"author"=>"admin","content"=>"内容就这么多");
				//print_r($post);
				//echo "<br/>";
				$posts[]=$post;
			}
			return $posts;
		}
	}
?>