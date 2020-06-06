<?php
if(!isset($_SESSION)) { session_start();} 
if ($_SESSION["id_people"]=="") 
	{header ("Location: /login.php");
	exit;}
define('root_dir',"/var/www/html/antidote_apache");
require(root_dir.'/header.php');
$conn2=open_conn();
$xBody="<h2>Thanks for contributing today.  Below you will see a brief nutrient breakdown of each recipe, and the overall nutritional value of the meal. </br></br>";
$xBody=$xBody.'You can always check out the status of your nutrition from <a href="https://antidote.org.nz/my_nutrition.asp?p=&'.$_SESSION["uid_people"].'">antidote.org.nz/my_nutrition.asp</a> </h2> </br></br>';
$xBody=$xBody.getLastRecipesServed($_SESSION["id_meal"]);
echo $xBody;

//show different payment methods

//ok set up a few differnet varibles.
//first check the dana table to see if user has dana

?>

 

