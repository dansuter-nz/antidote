<?php 
include 'header.htm';
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'\admin\connection.php');

?>

<div id="main-content">
  <div class="row">
  <div class="col-md-12">
  <br><br>
   <!--add in some code here to have the courses dynamic and controled via the database-->
   <h1>Alchemy Academy Book your experience of a lifetime.</h1>
   <br>
  </p>
</div>
</div>
<div class="row">
<div class="col-md-12">
   <?php 
//lets do it......
if($_GET["cid"] != null) 
{
//get database infomation for courses
  //first get dates
$sql = "SELECT id_course,date_start,ct.name,date_finish,price,comment,full ";
$sql = $sql."FROM alchemy.courses c ";
$sql = $sql."inner join alchemy.course_types ct ";
$sql = $sql."on ct.id_type=c.course_type ";
$sql = $sql."where id_course=".$_GET["cid"].";";

 $result = $conn->query($sql) or die($conn->error);
  //echo $sql;
  $htm="";
while($row = $result->fetch_assoc()) 
 {
 $htm=$htm."<div class='row'>\n";
$htm=$htm."<div class='col-md-8'>\n";
$htm=$htm."You have selected our ".$row["name"].". This course starts on the ".$row["date_start"]." and finishes on ".$row["date_finish"].". ".$row["comment"]."\n";
$htm=$htm."</div>\n<div class='col-md-4'>\n";
if ($row["full"]==false)
{
$htm=$htm."$USD".$row["price"]."&nbsp<a href='/course_bookings.php?cid=".$row["id_course"]."'><button type='button' class='btn btn-success'> Book a Place </button></a>";
}
else
{$htm=$htm."<a href='mailme.php?subject=alchemy course ".$row["date_start"]." to ".$row["date_finish"]."'>hello@alchemyacademybali.com</a>";}
  }
}
echo $htm;
   ?>
  </div>
  </div>
  <div class="row"> 
   <div class="col-md-6">
  	<img style="margin-top:20px;text-align: left;  width: 360px;  height: auto;  /* Magic! */ " src="/images/certification-group.jpg">
  </div>
   <div class="col-md-6">
  	<img style="margin-top:20px;text-align: left;  width: 360px;  height: auto;  /* Magic! */  " src="/images/courses2.jpg">
  </div>
 </div>
 <div class="row">
 <div class="col-md-6">
 </div>
</div>
</div>
</div>
<?php include 'footer.htm';?>