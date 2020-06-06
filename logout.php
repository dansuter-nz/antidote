
<?php 
session_start();
echo "now ". $_SESSION['email']."<br>";
$redirect="/index.php";
if ($_SESSION["redirect_to"]!="")
	{$redirect=$_SESSION["redirect_to"];}
session_unset(); 
// destroy the session 
session_destroy(); 
header("Location: ".$redirect);
exit;
?>