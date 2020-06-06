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
//get URL and get Recipe food item values
if ($_POST["t"]=="people") {
	$sSQL="";
	$sLocation="";
	if (!file_exists("/people/images/".$_POST["uid"].".jpg")) {
		$sLocation="/people/images/".$_POST["uid"].".jpg";
	}
	$sSQL="";
	$sSQL="UPDATE `people` SET image_path='".$sLocation."',email ='".$_POST["email"]."',password = '".$_POST["password"]."',name = '".$_POST["name"]."',about_me = '".str_replace("'","''",$_POST["about_me"])."' WHERE uid_people = '".$_POST["uid"]."';";
	//x=rwb(sSQL)
	$result = $conn->query($sSQL) or die($conn->error);
	echo "Updated!"; 
}
if ($_POST["t"]=="recipe") 
{
	$sLocation="";
	//cehck to see if there is an image at C:\inetpub\wwwroot\antidote\images\recipes'
	if (!file_exists("/images/recipes/".$_POST["uid"].".jpg")) {
		$sLocation="/images/recipes/".$_POST["uid"].".jpg";
	}
	$bAuth="0";
	$bWeb="0";
	if ($_POST["show_on_web"]=="on") {$bWeb="1";}
	if ($_POST["authorized"]=="on") {$bAuth="1";}
	$sSQL="";
	$sSQL="UPDATE `recipes` SET name ='".str_replace("'","''",$_POST["name"])."',id_type = '".$_POST["type"]."',how_to_make = '".replace($_POST["makeit"],"'","''")."',show_on_web=".$bWeb.",authorized=".$bAuth.",temp=0,servings='".$_POST["servings"]."',brief='".left(replace($_POST["brief"],"'","''"),200)."'  WHERE uid_recipe = '".$_POST["uid"]."';";
	//echo $sSQL;
	$result = $conn->query($sSQL) or die($conn->error);
	echo "Updated!";
}

if ($_POST["t"]=="food") {
	$sLocation="";
	
	//cehck to see if there is an image at C:\inetpub\wwwroot\antidote\images\recipes'
	if (!file_exists("/images/recipes/".$_POST["uid"].".jpg")) {
		$sLocation="/images/recipes/".$_POST["uid"].".jpg";
	}
	$bAuth="0";
	$bWeb="1";
	$wh_id=0;
	//grams_default=$_POST["food_amount")
	//x=rwe(DecodeUTF8($_POST["Intro")))
	if (! $_POST["wh_id"]=="" && is_numeric($_POST["wh_id"])) {$wh_id=$_POST["wh_id"];}
	if ($_POST["show_on_web"]=="on") {$bWeb="1";}
	if ($_POST["authorized"]=="on") {$bAuth="1";}
	$sSQL="";
	$sSQL="UPDATE `food` SET name ='".str_replace("'","''",$_POST["name"])."',Intro = '".replace($_POST["intro"],"'","''")."',Description='".replace($_POST["description"],"'","''")."',wh_id=".$wh_id.",visible=".$bWeb.",default_unit='',grams_default=grams_default,id_person_add=".$_SESSION["id_people"].",cost='".replace($_POST["cost"],"'","''")."',link='".replace($_POST["link"],"'","''")."',id_supplier='".$_POST["supplier"]."' WHERE uid_food = '".$_POST["uid"]."';";
	//echo sSQL;
	$result = $conn->query($sSQL) or die($conn->error);
	echo "Updated!";
}
if ($_POST["t"]=="address") 
{
	//ok so check to see if that address is already on file using long and lat data
	//
	$sSQL="";
	$sSQL="call delivery_address ('".$_POST["address_name"]."','".$_POST["lng"]."','".$_POST["lat"]."','".$_SESSION["id_people"]."','".$_POST["street_number"]."','".$_POST["route"]."','".$_POST["locality"]."','".$_POST["administrative_area_level_1"]."','".$_POST["country"]."','".$_POST["postal_code"]."','".$_POST["order_time"]."','".$_POST["car_time"]."','".$_POST["eta"]."','".$_SESSION["id_meal"]."','".$_POST["km_delivery_rate"]."','".$_POST["delivery_surchage"]."','".$_POST["delivery_price"]."')";
	//echo $sSQL;
	$result = $conn->query($sSQL) or die($conn->error);
	$_SESSION["total_price"]=$_SESSION["total_price"]+$_POST["delivery_surchage"]+$_POST["delivery_price"];
	echo $_POST["address_name"]." added to your addresses";
}
?>
