<?php 
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/header.php');
if(isset($_SERVER['HTTP_REFERER'])) {
$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];
}
?>
<div class="row row-centered" style="width:100%;">
	<div class="col-">
		<span class="">
<?php
$h="";
$conn=open_conn();
$sSQL = "SELECT id_project,id_person,title,intro,description,created,pn.name,url_link FROM projects p inner join people pn on p.id_person=pn.id_people";
$result = $conn->query($sSQL) or die($conn->error);
while($row = $result->fetch_assoc())
	{
	$h=$h."<h1>".$row["title"]."</h1>";
	$h=$h."<p>".$row["name"]."</p>";
	$h=$h."<p>on ".$row["created"]."</p>";
	$h=$h."<p>updated ".$row["created"]."</p>";
	$h=$h."<p>Link <b><a href='".$row["url_link"]."'>".$row["url_link"]."</a></b></p>";
	$h=$h."<p><b>".$row["intro"]."</b></p>";			
	$h=$h."<p>".$row["description"]."</p>	";
}
echo($h);
?>	
		</span>
	</div>
</div>

<hr>
<div class="row row-centered">
	<div class="col-md-12 text-center" >
		<img src="/images/socrates_change.jpg">
	</div>
</div>		
<br>
<?php 
include 'footer.htm';
?>