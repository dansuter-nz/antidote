<?php 
session_start();
if (isset($_SESSION["email"])==false)
{
header("Location: /login.php"); 
exit();
}

define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'/admin/functions.php');
if (isset($_GET["d"]))
 {
 	$conn=open_conn();
 	//delete cart item and redirect
 	$sql="Delete from people_courses where id_people_course=".$_GET["d"].";";
 	$result = $conn->query($sql) or die($conn->error);
 	$conn->close();
 	//header("Location: ".$_SESSION["redirect_to"]);
 	//echo $_SESSION["redirect_to"];
 	//exit;
 }


include 'header.php';
$total=0;
$itemid="";
$item_id = test_input($_POST["item_id"]);
$item_id2 = test_input($_POST["item_id2"]);
//insert into database that student wants to pay for course via bank transfer
//add items into cart from bookings or books page
if ($item_id!="")
{
$item_id=substr($item_id, 4);
$item_id2=substr($item_id2, 4);
if ($item_id2!="0"){$accprice= test_input($_POST["accprice".$item_id2]);}
else{$accprice=0;}
$days=test_input($_POST["accdays"]);
$sql="call insert_course_cart ('".$_SESSION["id_people"]."',".$item_id.",".$item_id2.",'".$accprice."','".$days."','');";
//echo "<br><br>".$sql;
$conn=open_conn();
$result2 = $conn->query($sql) or die($conn->error);
}
$sql="SELECT id_people_course,a.name 'accom',ct.name 'course',c.price,c.date_start,c.date_finish,pc.acc_qty,pc.acc_price_day,pc.deposit,pc.full_pay,p.name 'person' FROM 
alchemy.people_courses pc
inner join people p
 on p.id_people=pc.id_people
left join courses c 
 on c.id_course=pc.id_course
left join accomodation a
 on a.id_accomodation=pc.id_accomodation
left join course_types ct on
 ct.id_type=c.course_type
 where pc.id_people=".$_SESSION["id_people"]." 
 and deposit=false and full_pay=false;";
//echo "<br><br>".$sql;
//exit;
$conn=open_conn();
$result2 = $conn->query($sql) or die($conn->error);
$htm="<table align='center' cellpadding='10' style='width:90%'>";
$htm=$htm."<tr style='background-color:lightgreen'><td></td><td>Description</td><td>Price</td><td>Units</td><td style='text-align:right'>Total</td></tr>";
while($row = $result2->fetch_assoc()) 
{
$accomtotal=floor($row["acc_price_day"])*floor($row["acc_qty"]);
$total=$total+$row["price"];
$id=$row["id_people_course"];
$htm=$htm."<tr><td><a style='color:red' href='/cart.php?d=".$row["id_people_course"]."'>X</a></td>"; 
$htm=$htm."<td>".$row["course"]."</td>";
$htm=$htm."<td style='text-align:right'>$".$row["price"]."</td>";
$htm=$htm."<td style='text-align:right'>1</td>";
$htm=$htm."<td style='text-align:right'><b>$".$row["price"]."</b></td></tr>";
if (!$row["accom"]=="")
{
$htm=$htm."<tr><td><a style='color:red' href='/cart.php?d=".$row["id_people_course"]."'>X</a></td>"; 
$htm=$htm."<td>".$row["accom"]."</td>";
$htm=$htm."<td style='text-align:right'>$".$row["acc_price_day"]."</td>";
$htm=$htm."<td style='text-align:right'>".$row["acc_qty"]."</td>";
$htm=$htm."<td style='text-align:right'><b>$".$accomtotal."</b></td></tr>";
$total=$total+$accomtotal;
}
}
$htm=$htm."<tr><td>&nbsp;</td><td>&nbsp;</td><td style='text-align:right'><b>Total</b></td><td>&nbsp;</td><td style='text-align:right'><b>USD$".$total."</b></td></tr>";

?>

<br><br>
<br><br>
<h3 align="center">Hey <?=$_SESSION["name"]?>, your cart as below</h3>

<?php
echo $htm
?>
<!--add options for payment of 50%-->
<tr><td colspan="6"></td></tr>
<tr><td><input type="radio" id="pay50" name="payoption" value="1"></td><td colspan="3">Option 1 Pay 50% to secure your spot (Balance payable 1 month prior to course commencement)</td><td style='text-align:right'><b>USD$<?=$total/2?></b></td></tr>
<tr><td><input type="radio" id="pay100" name="payoption" checked="" value="2"></td><td colspan="3">Option 2 Pay 100% to complete booking now</td><td style='text-align:right'><b>USD$<?=$total?></b></td></tr>
<tr><td colspan="3" style="text-align: center"><input type="button" class="button" value="Pay by Bank Transfer" onclick="makepay(<?=$id?>,'<?=$total?>');"></td><td colspan="3"></td></tr>
</table>
<script>
function makepay(id,total)
{
if ($("#pay50").checked==true) 
 {amount=total/2;h=1;}
 else
 {amount=total;h=0;}
location.href="/payments.php?id="+id+"&a="+amount+"&h="+h;
}
</script>
