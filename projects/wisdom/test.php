<?php
$path="/var/www/html/antidote_apache";
$path .= "/header.php";
include_once($path);
//if (!$_GET["f"]=="") 
//{
$irow=0;
$conn=open_conn();
$h="";
//x=rwe("")
$sSQL="Select count(category_name) 'cnt',category_name 'ct_name' fROM adds.stuff_articles                      
group by category_name                                                                               
order by count(category_name) desc; ";
echo $sSQL;
//exit;
$result = $conn->query($sSQL) or die($conn->error);

while($row = $result->fetch_assoc())
	{
	echo "here";
	if (is_null($row["cnt"]))
		{echo "row count isnull". $row["cnt"];}
	if (!is_null($row["cnt"])) 
		{
			$h=$h.'<option value="'.$row["ct_name"].'"';
			if ($_GET["f1"]==$row["ct_name"] && ! $_GET["f1"]=="") 
				{$h=$h." selected";}
			$h=$h.">".$row["ct_name"]." (".$row["cnt"].")</option>";
		}
	$irow=$irow+1;
	//echo mysqli_num_rows($result3);
	}
mysqli_free_result($result);
echo $h;
$h="";					
//}
?>