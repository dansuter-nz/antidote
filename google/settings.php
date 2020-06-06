<?php

$url = str_replace("www.","",$_SERVER['HTTP_HOST']);
$url = str_replace("dev.","",$url);
$url =strtolower($url);



$conn=open_conn();
$sSQL="SELECT google_client_id,google_secret FROM antidote.restaurant where url='".$url."';";

$result = $conn->query($sSQL) or die($conn->error);
$row = $result->fetch_row();
/* Google App Client Id */
define('CLIENT_ID', $row["0"]);
/* Google App Client Secret */
define('CLIENT_SECRET', $row["1"]);
$url='https://' . $_SERVER['HTTP_HOST']; 
/* Google App Redirect Url */
define('CLIENT_REDIRECT_URL', $url.'/google/gauth.php');

?>