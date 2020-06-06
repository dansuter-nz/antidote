<?php 
session_start();
if (isset($_SESSION["email"])==false)
{
header("Location: /login.php"); 
exit();
}
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'\admin\functions.php');
$conn=open_conn();
//insert into database that student wants to pay for course via bank transfer

//get URL and get Recipe food item values
if ($_GET["t"]=="people") {
	$sSQL="";
	$sSQL="Delete from people WHERE uid_people = '".$_GET["d"]."';";
	$result = $conn->query($sSQL) or die($conn->error); 
	echo "Person Deleted redirectig to persons page";
	}
if ($_GET["t"]=="recipe") {
	$sSQL="";
	$sSQL="Delete from recipes WHERE uid_recipe = '".$_GET["d"]."';";
	$result = $conn->query($sSQL) or die($conn->error); 
	echo "Recipe Deleted redirectig to recipes page";
	header("Location: /recipes.php"); 
	exit();
    }
if ($_GET["t"]=="food") {
	$sSQL="";
	$sSQL="Delete from food WHERE uid_food = '".$_GET["d"]."';";
	$result = $conn->query($sSQL) or die($conn->error); 
	echo "Food Deleted redirectig to foods page";
	header("Location: /foods.php"); 
	exit();
}
?>