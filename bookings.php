<?php 
session_start();
$_SESSION["redirect_to"]=$_SERVER['HTTP_REFERER'];
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'/admin/functions.php');
//check to see if logged in if not redirect
if (isset($_SESSION["email"])==false)
{
header("Location: /login.php");  
exit();
}
$conn=open_conn(); 
include 'header.php';  
?>
<form name="booking" action="/cart.php" method="post">

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
if($_GET["course"] != null) 
{
//get database infomation for courses
//first get dates

$sql = "SELECT id_course,date_start,ct.name,date_finish,price,comment,full ";
$sql = $sql."FROM alchemy.courses c ";
$sql = $sql."inner join alchemy.course_types ct ";
$sql = $sql."on ct.id_type=c.course_type ";
$sql = $sql."where id_course=".$_GET["course"].";";

 $result = $conn->query($sql) or die($conn->error);
  //echo $sql;
  $htm="";
while($row = $result->fetch_assoc()) 
 {$_SESSION["course_name"]= $row["name"];
$_SESSION["course_dates"]=date('d M Y',strtotime($row["date_start"]))." to ".date('d M Y',strtotime($row["date_finish"]));
$_SESSION["pay_ref"]=str_replace("-","",$row["date_start"]).$_SESSION["name"];
 $htm=$htm."<div class='row'>\n";
$htm=$htm."<div class='col-md-12'>\n";
$htm=$htm."Hey ".$_SESSION["name"].", you have selected our <b>".$row["name"]."</b>. This course starts on the <b>".date('d M Y',strtotime($row["date_start"]))." and finishes on ".date('d M Y',strtotime($row["date_finish"]))."</b>. The cost of the course is <b>USD$".$row["price"]."</b>.&nbsp ".$row["comment"].". <br><br>\n";
$htm=$htm."</div>\n</div><div class='row'><div class='col-md-12'>\n";
if ($row["full"]==false)
{
$total=$row["price"];
$start=date('d M Y',strtotime($row["date_start"]));
$htm=$htm. "
<hr>
<table class='table'>\n<tr><td>\n<input type='radio'name='rcourse' id='rcourse' checked></td><td colspan='2'>
<b>".$row["name"].". ".$_SESSION["course_dates"]."</b>. </td><td style='text-align:right'><div style='font-size:1.5em;' id='course_price'>".$row["price"]."</div></td></tr>
<tr>\n<td>\n</td><td colspan='3'>Select Accomodation Option</td></tr>\n";
//enumerate accomodation options
$datetime1 = new DateTime($row["date_start"]);
$datetime2 = new DateTime($row["date_finish"]);
$days = $datetime1->diff($datetime2);
$day = $days->format("%a");
$day=$day+1;
$accom="";
$sql2="SELECT `accomodation`.`id_accomodation`,
    `accomodation`.`name`,
    `accomodation`.`url`,
    `accomodation`.`night_price_normal`,
    `accomodation`.`night_price_alchemy`,
    `accomodation`.`night_price_cost`,
    `accomodation`.`availible`,
    `accomodation`.`image_url`,
    `accomodation`.`breif`,
    `accomodation`.`description`,
    `accomodation`.`locationX`,
    `accomodation`.`locationY`
FROM `alchemy`.`accomodation`;";
$count=0;
$result2 = $conn->query($sql2) or die($conn->error);
while($row2 = $result2->fetch_assoc()) 
 {
    $price=$row2["night_price_alchemy"]*$day;
 	$count=$count+1;
 	if ($count==2){$disabled="";$checked="checked";$total=$total+$price;$style="";$selected=$row2["id_accomodation"];}
 	else{$disabled="disabled";$checked="";$style=" style='opacity:0.25'";}
 	$accom=$accom."<tr id='tr".$row2["id_accomodation"]."' class='accomodation ".$disabled."'>\n<td style='text-align:center'><input type='radio' name='accom' id='accom".$row2["id_accomodation"]."' value='".$row2["id_accomodation"]."' ".$checked." onclick='disable_acc(".$row2["id_accomodation"].");'>\n</td><td><a target='blank' href='".$row2["url"]."'><img ".$style." id='img".$row2["id_accomodation"]."' class='imgacc' width='200' height='auto' src='/images".$row2["image_url"]."'></a></td><td class=''><b>".$row2["name"]."</b>. ".$row2["breif"]." (USD$".$row2["night_price_alchemy"]." per night x ".$day." nights)<input type='hidden' name='accprice".$row2["id_accomodation"]."' id='accprice".$row2["id_accomodation"]."' value='".$row2["night_price_alchemy"]."'></td><td style='text-align:right'><div style='font-size:1.5em;' id='acc_price".$row2["id_accomodation"]."'>".$price."</div></td></tr>";


 }
$htm=$htm.$accom;
//finish accomodation

$htm=$htm. "<tr>\n<td style='text-align:center'>\n<input type='radio'name='accom' id='accomodation_no' value='0' onclick='disable_acc(0)'></td><td colspan='2'>No thanks I will sort my own accomodation</td><td><div  id='acc_price0' style='text-align:right;font-size:1.5em;'>0</div></td></tr>\n

";
$htm=$htm. "<tr><td colspan='4'><b>Our Cancelation Policy</b><br><br>90-30 days before the start of the course: 100% refund minus a 10% administration fee<br>
Less than 30 days before the start of the course we don't give cash refunds: However, provided that there is a waiting list (most often there is), you will be able to transfer to the next available course.<br><br></td></tr>";	
$htm=$htm. "<tr>\n<td colspan='2'><h3>Total </h3></td>\n
<td colspan='2' style='text-align:right;'><div style='white-space: nowrap;font-size:2em;' id='total'>USD$&nbsp;<b><u>".$total."</u></b></div><input type='hidden' id='totalacc' name='totalacc' value='".$total."'><input type='hidden' id='totalint2day' name='totalint2day' value='".$total."'></td>
</tr>\n
<tr>\n<td colspan='4' align='center'><input class='button' type='submit' value='add to cart'></td>\n
</tr>\n

</table>
";
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
 </div>
</div>
</div>
</div>
<input type="hidden" name="item_id" id="item_id" value="COUR<?=$_GET["course"]?>">
<input type="hidden" name="item_id2" id="item_id2" value="ACCO<?=$selected?>">
<input type="hidden" name="accdays" id="accdays" value="<?=$day?>">
</form>
<script>
var ipercent=100;
function deposit1(percent)
{
ipercent=percent;
 var a=$("#totalacc").val();
 a=a*ipercent/100;
 //alert($("input#totalint").val());
 $("#total2day").html("USD$<b><u>"+a+"</u></b>");
 $("#totalint2day").val(a);
}

function disable_acc(row)
{
  //make rows disabled
  //alert(row);
  $.each($('.accomodation'), function (index, value) {
  $(this).addClass(" disabled");
});
  $.each($('.imgacc'), function (index, value) {
  $(this).css("opacity","0.25");
});
  $("#img"+row).css("opacity","1");
  $("#tr"+row).removeClass(" disabled");
  $("#tr"+row).removeClass(" disabled");
  //alert($("#course_price").html());
  //alert($("#acc_price"+row).html());
  var a=parseInt($("#course_price").html());
  var b=parseInt($("#acc_price"+row).html());
  var t=a+b;
  var paynow=t*ipercent/100;
  //alert(t);
  $("#total").html("Total&nbsp;USD$<b><u>"+t+"</u></b>");
  $("#total2day").html("USD$<b><u>"+paynow+"</u></b>");
  $("#totalacc").val(t);
  $("#totalint2day").val(paynow);
  $("#item_id2").val("ACCO"+row);
}


</script>



<?php include 'footer.htm';?>