<?php
if(!isset($_SESSION)) { session_start();} 
//echo "hello".isset($_SESSION["email"]);
if (isset($_SESSION["email"]))
{
	define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
	require(root_dir.'/admin/functions.php');
	if ($_GET("d")=="") {
		$sSQL="";
		$sSQL="INSERT INTO `people_recipe_favourites`(`id_people`,`id_recipe`)";
		$sSQL=$sSQL." Select	".$_SESSION("id_people").",".$_GET("r").";";
		$result2 = $conn->query($sSQL) or die($conn->error);
		echo $sSQL;
		echo "favourite added";
	} else {
		//must be a delete'
		$sSQL="";
		$sSQL="Delete from  `people_recipe_favourites` where id_people=".$_SESSION("id_people")." and id_recipe=".$_GET("r").";";
		$result2 = $conn->query($sSQL) or die($conn->error);
		echo $sSQL;
		echo "deleted";	
	}
}
?>
