<?php 
if (!isset($_GET["v"]))
	{header("location: /index.php");}
include 'header.php';
if(isset($_SERVER['HTTP_REFERER'])) {
$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];
}
?>
<br><br>

We do not share your personal information. 

<div id="spacer" style="margin-top:20px;"></div>
<?php include 'footer.htm';
?>
