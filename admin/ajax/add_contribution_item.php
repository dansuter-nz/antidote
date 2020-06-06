 <?php
session_start();
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
if (!$_SESSION["can_authorize"])
{
	//echo "login first".$_SESSION["can_authorize"];
	//exit;
//header("Location: /login.php"); 
}

$conn=open_conn();
//get URL and get Rec

if (!$_GET["idc"]=="") {
	//sURL="/admin/ajax/add_contribution_item.asp?idc="+$("#portion_size").val()+"&c="+$("#contribuition_amount").val()+"&idr="+iR
	$sSQL="";
	$sSQL="call insert_recipe_contribution (".$_GET["idc"].",'".$_GET["c"]."',".$_GET["idr"].",0)";
	$result = $conn->query($sSQL) or die($conn->error);
	$conn -> close();
	$conn=open_conn();
	$sSQL="";
	$sSQL="SELECT id_recipe_contribution,  amount_currency, p.id_portion_size, p.name FROM recipe_contribution c inner join portion_sizes p	on p.id_portion_size=c.id_portion_size where id_recipe=".$_GET["idr"]." and c.id_portion_size=".$_GET["idc"]." order by id_recipe_contribution desc limit 1";

	$result = $conn->query($sSQL) or die($conn->error);
	while($row = $result->fetch_assoc())  
	{	
		echo '<div id="contribution'.$row["id_recipe_contribution"].'">'.$row["name"]." $".$row["amount_currency"].' <button class="button danger icon remove"  onclick="Delete_contribution('.$row["id_recipe_contribution"].'); return false;">Remove item</button></div>';
	}
	
} else {
	//must be a delete'
	$sSQL="";
	$sSQL="Delete from `recipe_contribution` where id_recipe_contribution=".$_GET["d"].";";
	$result = $conn->query($sSQL) or die($conn->error);
	echo "Deleted ";	
}
?>;
