<?php 
if (!isset($_GET["v"]))
	{header("location: /index.php");}
include 'header.php';
if(isset($_SERVER['HTTP_REFERER'])) {
$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];
}
?>
<script type="text/javascript">
function showfoods(idv)
{$("#vitFoods"+idv).toggle(300);
$("#butt"+idv).toggleClass("arrowdown arrowup");
if ($("#butt"+idv).html()=="Hide foods table")
	{$("#butt"+idv).html("Show foods table")}
else
	{$("#butt"+idv).html("Hide foods table")}
}
</script>
<style>
	.graph{background-color:#63a504;color:#fff}
</style>
<div class="foods " >	

<?php
$wfid=0;
$h="";
$sSQL = "SELECT * FROM vitamins where id_vitamin=".$_GET["v"];
$conn=open_conn();
$result = $conn->query($sSQL) or die($conn->error);
$irow=0;
if (mysqli_num_rows($result)==1)
	{
		while($row = $result->fetch_assoc())  
		{
			if ($irow % 2==1) {$strClass="light_blue_row";} else { $strClass="white_row";}
			$irow=$irow+1;
			$desc=$row["Full_Description"];
			$wfid=$row["whf_id"];
			//sSQL="Update food set intro='"&stripHTML(replace(rsTemp("Intro"),"'","''"))&"' where id_food='"&rsTemp("id_food")&"'"
			//x=rwb(sSQL)
			//x=openRSA(sSQL)
			//x=rwe("here.")
			$id_vitamin=$row["id_vitamin"];
			}
			$result->close();
		?>
		<div class="row">
			<div class="col-md-12" >
				<h1><?=$row["name"]?></h1><h2 id="nutrientdescr">Basic Description</h2>
				<button id="butt<?=$row["id_vitamin"]?>" class="button icon arrowup" onclick="showfoods(<?=$id_vitamin?>);">Hide foods table</button>
			</div>
		</div>

		<div class="table table-bordered" id="vitFoods<?=$row["id_vitamin"]?>" style="display:block;">
			<div class="row">
					<div class="col-sm-12" >
					<b>Foods with most <?=$row["name"]?> per 100 grams (Ordered by % of Daily Recommended Intake)</b> 
							<table class="table table-striped">
				 <thead>
		        <tr>
		            <th>Food</th>
		            <th>Percentage of DRI per 100 grams</th>
		        </tr>
		    </thead>
		  	<tbody>
			
		
			<?php
				//get the top 3 foods for each vitmain
				$sSQL="Select  f.id_food,f.name,f.wh_id ,fv.percentage/grams_default*100 'DRI_100' from food_vitamins fv inner join food f on f.id_food=fv.id_food where fv.id_vitamin=".$_GET["v"]." Order by DRI_100 desc limit 20;";
				$conn=open_conn();
				$result2 = $conn->query($sSQL) or die($conn->error);
				$s="";
				$irow=0;
				while($row2 = $result2->fetch_assoc())  
				{
				$irow=$irow+1;
				if ($irow==1){$iMax=intval($row2["DRI_100"]);}
				$percent=round($row2["DRI_100"]*100/$iMax);
				//echo $row2["DRI_100"].":".$iMax."<br>";
				$s=$s."<tr>";
		        $s=$s."<td style='width:20%'><a href='/food.php?f=".$row2["id_food"]."'>".$row2["name"]."</br></a></td>";
		        $s=$s."<td style='width:80%'><span class='graph' style='width:".$percent."%;float: left;text-indent:20px;'><b>".round($row2["DRI_100"],0)."</b></b></span></td></tr>"; 
			    }
			    echo $s;
			    $result2->close();
				?>

				</tbody>
			</table> 

		         </div>
			</div>
		
		</div>
		<div class="row">
			 <div class="col-sm-12" style="margin-left: 15px;margin-right: 15px;">
				<?php
				echo $desc;
				?>
			 </div>
		    </div>

		</div>

		<div class="row">
			<div class="col-sm-12">
				<?php
				if ($wfid>0) {
				?>
				<ul><li>Much grattidtude to George Mateljan,and the George Mateljan Foundation for <a href="http://www.whfoods.com/genpage.php?tname=nutrient&dbid=<?=$wfid?>">www.whfoods.com</a></li></li></ul>
				<?php 
			    }
				?>
			</div>
		</div>
		<?php
	}
else
	{
		//no vitamin id match,
		$h="</br><span style='width:100%;white-space:nowrap;'><h1>What Are Vitamins and Minerals?</h1></span></br></br>

Vitamins and minerals make people's bodies work properly. Although you get vitamins and minerals from the foods you eat every day, some foods have more vitamins and minerals than others.</br></br>

Vitamins fall into two categories: fat soluble and water soluble. The fat-soluble vitamins — A, D, E, and K — dissolve in fat and can be stored in your body. The water-soluble vitamins — C and the B-complex vitamins (such as vitamins B6, B12, niacin, riboflavin, and folate) — need to dissolve in water before your body can absorb them. Because of this, your body can't store these vitamins. Any vitamin C or B that your body doesn't use as it passes through your system is lost (mostly when you pee). So you need a fresh supply of these vitamins every day.</br></br>

Whereas vitamins are organic substances (made by plants or animals), minerals are inorganic elements that come from the soil and water and are absorbed by plants or eaten by animals. Your body needs larger amounts of some minerals, such as calcium, to grow and stay healthy. Other minerals like chromium, copper, iodine, iron, selenium, and zinc are called trace minerals because you only need very small amounts of them each day.</br></br>";
	}
	echo $h;
	?>
<div id="spacer" style="margin-top:20px;"></div>
<?php include 'footer.htm';
?>
