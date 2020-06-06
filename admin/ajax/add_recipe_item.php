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
//get URL and get Recipe food item values
if ($_GET["d"]=="") {
	$sSQL="";
	$sSQL="INSERT INTO recipe_foods (`id_Recipe`,`id_food`,`qty_grams`,`id_person`,display_order)";
	$sSQL=$sSQL." Select	".$_GET["idr"].",".$_GET["idf"].",".$_GET["qty"].",".$_GET["idp"].",COALESCE((Select max(display_order)+1 from recipe_foods where id_Recipe=".$_GET["idr"]."),1);";
	//x=rwb(sSQL)
	$result = $conn->query($sSQL) or die($conn->error);
	$sSQL="";
	$sSQL="Select id_recipe_food,qty_grams,name from recipe_foods rf inner join food f on f.id_food=rf.id_food order by id_recipe_food desc limit 1";
	$result = $conn->query($sSQL) or die($conn->error);
	while($row = $result->fetch_assoc())  
	{
	echo '<div id="ingredient'.$row["id_recipe_food"].'">'.$row["qty_grams"].' grams of <a href="/food.asp?f='.$_GET["idf"].'">'.$row["name"].'</a> <button class="button danger icon remove"  onclick="Delete_Ingredient('.$row["id_recipe_food"].'); return false;">Remove item</button></div>';
	}
	//x=rwb("inserted "&sSQL)
} else {
	//must be a delete'
	//x=rwe($_GET["d"])
	$sSQL="";
	$sSQL="Delete from  recipe_foods where id_recipe_food=".$_GET["d"].";";
	//x=rwe(sSQL)
	$result = $conn->query($sSQL) or die($conn->error);
	echo "Deleted!";
	//x=rwb("Deleted "&sSQL)	
}
?>
