<?php
session_start();
define('root_dir',"/var/www/html/antidote_apache/admin/functions.php");

if ($_SESSION["id_people"]=="") 
	{header ("Location: /login.php");
	exit;}
$_SESSION["redirect_to"]=$_SERVER['HTTP_REFERER'];
session_start();
if ($_SESSION["id_people"]=="") 
	{header ("Location: /login.php");
	exit;}

include_once("/var/www/html/antidote_apache/admin/functions.php");
$conn=open_conn();
$conn2=open_conn();
//Votes are always on comments
$bUp=0;
$bDown=0;
if ($_GET["v"]=="1") {
	$bUp=1;
	$bDown=0;
} else {
	$bUp=0;
	$bDown=1;
}
//count to check if this person has already voted
$sSQL="Call adds.P_Sel_Vote_count (".$_GET["c"].",".$_SESSION["id_people"].",".$bUp.",".$bDown.")";
$result = $conn->query($sSQL) or die($conn->error);

while($rsTemp = $result->fetch_row())
{
	$votes=$rsTemp[0];
	if ($votes=="0") {
		$result->close();
		$sSQL="Call adds.P_Ins_Vote (".$_GET["c"].",".$_SESSION["id_people"].",".$bUp.",".$bDown.")";
		$result2 = $conn2->query($sSQL) or die($conn2->error);
		$sMsg="Vote has been inserted.";
	} else {
		$sMsg="Duplicate Votes are not allowed.";
	}
}
echo $sMsg;
$result->close();
?>







