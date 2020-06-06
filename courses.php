<?php 
session_start();
$_SESSION["redirect"]=$_SERVER['HTTP_REFERER'];
include 'header.php';
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'/admin/functions.php');
$conn=open_conn();
?>

<div id="main-content">
    <div class="row">
  <div class="col-md-12">
   <p style="font-size:16px;text-align: left;width:90%">
<br>
  <?php
$rows=0; 
if ($_GET["id"] == NULL){
  echo "<div class='chef-training'></div>";
echo "<H1>All our courses</h1><br>";
//lets do it......
   $htm="<div class='row'>";
   $htm=$htm."<div class='col-md-12'> 
      Alchemy Academy is a raw, vegan culinary school located in Ubud, Bali. We offer a variety of courses on the art of plant-based cuisine. The curriculum is modern, creative, playful and emphasizes on finding the joy and mindfulness in making and serving healthy and delicious food. We have drop-in classes for home cooks who want to upgrade their repertoire, as well as professional chef certification courses for those looking to start a career in the ever-growing business of health and wellness.<br><br> 
    <br></div></div><div class='row row-center'><div class='col-md-4' style='text-align:center;'>";
   $col=4;
$sql = "SELECT id_type,name,image_path,description FROM alchemy.course_types;";
$result = $conn->query($sql);
 while($row = $result->fetch_assoc()) 
 { $rows=$rows+1;
  $htm=$htm."<a href='/courses.php?id=".$row["id_type"]."'><img width='240' height='' src='/images/courses/".$row["image_path"]."'></a>\n<br><b>".$row["name"]."</b><br><br></div>\n";
  $col=$col+4;
  if ($col<13)
    {
      $htm=$htm."<div class='col-md-4' style='text-align:center;'>\n";
     //echo $col;
    }
  else
    {
      $col=4;
      $htm=$htm."</div><div class='row row-center'><div class='col-md-4' style='text-align:center;'>\n";
      
    }  
}
if($col==8)
    {$htm=$htm."<div class='col-md-4'></div><div class='col-md-4'></div>";}
if($col==12)
    {$htm=$htm."<div class='col-md-4'></div>";}
  $htm=$htm."</div></div>";
   //check to see if url has ?ID=
}

if($_GET["id"] != null) 
{
//get database infomation for courses
  //first get dates
$sql = "SELECT id_course,date_start,ct.name,date_finish,price,comment,ct.description,full ";
$sql = $sql."FROM alchemy.courses c ";
$sql = $sql."inner join alchemy.course_types ct ";
$sql = $sql."on ct.id_type=c.course_type ";
$sql = $sql."where course_type=".$_GET["id"];
$sql = $sql." and date_start>CURRENT_DATE;";
 $result = $conn->query($sql) or die($conn->error);
}
   //display all of them
//echo $sql;

while($row = $result->fetch_assoc()) 
 {
    $rows=$rows+1;
    if ($rows==1){
    $htm=$htm."<br><div class='row'><div class='col-md-12'><h1>Welcome to ".$row["name"]." Alchemy Academy</h1> </div></div>\n";
    $description="<div class='row'><div class='col-md-12'>".$row["description"]."</div></div>\n";
    $htm=$htm."<div class='row'><div class='col-md-8'><h3>Click on the date to see course specifics</h3></div><div class='col-md-4'></div></div>\n";
      }
    $htm=$htm."<div class='row' style='padding:10px'>\n";
    $htm=$htm."<div class='col-md-8'>\n";
    $htm=$htm."<a href='/bookings.php?course=".$row["id_course"]."'>Course date from ".$row["date_start"]." to ".$row["date_finish"]."</a>. ".$row["comment"]."\n</div><div class='col-md-4'>";
    $htm=$htm."\n";
    if ($row["full"]==false)
    {
    $htm=$htm."<a href='/bookings.php?course=".$row["id_course"]."'><button type='button' class='btn btn-success'> Book a Place </button></a> USD $".$row["price"]."&nbsp";
    }
    else
    {$htm=$htm."<a href='mailme.php?subject=alchemy course ".$row["date_start"]." to ".$row["date_finish"]."'>hello@alchemyacademybali.com</a>";
    }
    $htm=$htm."\n";
    $htm=$htm."</div></div>\n";
}

if ($rows==0)
    {$htm="<br><div class='row'><div class='col-md-12'><h3>There are no courses for these dates yet. Email me if you are interested in joining me on this course.</h3><br><br>Shanti hello@alchemyacademybali.com</div></div>\n";}

echo $htm;
echo $description;
   ?>

  </p>
  </div>
  </div>
 <div class="row">
 <div class="col-md-6">
 </div>
</div>
<?php include 'footer.htm';?>