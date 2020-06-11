<?php

$url = str_replace("www.","",$_SERVER['HTTP_HOST']);
$url = str_replace("dev.","",$url);
$url =strtolower($url);



$conn=open_conn();
$sSQL="SELECT `fb_app_client_id`,`fb_app_secrect` FROM antidote.restaurant where url='".$url."';";

$result = $conn->query($sSQL) or die($conn->error);
$row = $result->fetch_row();
/* Google App Client Id */
define('FB_CLIENT_ID', $row["0"]);
/* Google App Client Secret */
define('FB_CLIENT_SECRET', $row["1"]);
$url='https://' . $_SERVER['HTTP_HOST']; 
/* Google App Redirect Url */
define('FB_CLIENT_REDIRECT_URL', $url.'/google/gauth.php');

?>