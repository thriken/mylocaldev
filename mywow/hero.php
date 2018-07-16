<?php
require("config.php");

	function getPros(){
		$sql="select * from profession";
		$query=mysql_query($sql);
		while($rsPros = mysql_fetch_array($query)){
			
			return $rsPros;
		}
		
	}
		
	//function getHeros(){
	//	echo "get Heros resultset";
	//}
	//function getClass(){
	//	$sql="select * from class";
	//}
	//
	//function getRace(){
	//	$sql="select * from race";	
	//}
?>