<?php
session_start();
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
if (!$_SESSION["can_authorize"])
{
	echo "login first".$_SESSION["can_authorize"];
//header("Location: /login.php"); 
}
$conn=open_conn();
$sSQL="";
if ($_GET["d"]=="") {
	
	$sSQL="INSERT INTO `food_vitamins`(`id_food`,`id_vitamin`,`percentage`,`color`)";
	$sSQL=$sSQL." Select	".$_GET["idf"].",".$_GET["vitamin"].",".$_GET["percentage"].",'#".random_color()."';";
	$result = $conn->query($sSQL) or die($conn->error);
	$sSQL="Select id_food_vitamin,percentage,name from food_vitamins fv inner join vitamins v on v.id_vitamin=fv.id_vitamin where fv.id_food='".$_GET["idf"]."' and fv.id_vitamin='".$_GET["vitamin"]."' order by id_food_vitamin desc limit 1";
	//echo $sSQL;
	//exit;
	$result = $conn->query($sSQL) or die($conn->error);
	while($row = $result->fetch_assoc()) {
	echo "<div id='vitamin".$row["id_food_vitamin"]."'>RDI % ".$row["percentage"]." of ".$row["name"]." <button class='button danger icon remove'  onclick='Delete_food_vit(".$row["id_food_vitamin"]."); return false;'>Remove item</button></div>";
    }
}
else
{
$sSQL="";
$sSQL="Delete from  `food_vitamins` where id_food_vitamin=".$_GET["d"].";";
$result = $conn->query($sSQL) or die($conn->error);
echo "Deleted ";	
}

?>