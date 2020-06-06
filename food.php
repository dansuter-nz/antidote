<?php include 'header.php';
if(isset($_SERVER['HTTP_REFERER'])) {
$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];
}
?>
<?php
if (!isset($_GET["f"]) || !is_numeric($_GET["f"])) 
{
	echo "Invalid food id. Try again.";
	exit;
}

$sSQL = "SELECT s.id_supplier,s.website,s.name,s.title,s.image_location,f.image_path,f.name,intro,description,wh_id,f.link FROM food f left join suppliers s on s.id_supplier=f.id_supplier where id_food=".$_GET["f"].";";
$irow=0;
$conn=open_conn();
//echo $sSQL;
$result = $conn->query($sSQL) or die($conn->error);
while($row = $result->fetch_assoc())  {
if ($irow % 2==1) {$strClass="light_blue_row";} else { $strClass="white_row";}
//sSQL="Update food set intro='"&stripHTML(replace(rsTemp("Intro"),"'","''"))&"' where id_food='"&rsTemp("id_food")&"'"
?>
<div class="foods" >
<div class="row"  style="width:100%">
		<div class="col-">
			<ul class="imgList">
				<li class="service-list" style="width:100%">
					<img class="pad5" src="<?=$row["image_path"]?>" alt="icon" width="200" height="auto" />
					<h1><?=$row["name"]?></h1>
					<?php if (isset($row["id_supplier"])) {
							$sLink=$row["website"];
							if (! $row["link"]=="") {$sLink=$row["link"];}
						?>
					  <div>Supplied by </div><div><a href="<?=$sLink?>" target="_blank"><img src="<?=$row["image_location"]?>" title="<?=$row["name"]?>"></a></div></br></br></br>
					<?php
				     }
					?>	
					<?php 
					if ($_SESSION["can_authorize"]) 
					{?>
						<div><a href="/add_food.php?id=<?=$_GET["f"]?>" class="button icon edit">Edit Food</a></div>
					<?php
				    }
					?>
				
				</li>
			</ul>
			<table class="table table-striped">
					 <thead>
			        <tr>
			            <th>Food</th>
			            <th colspan="2">Percentage of DRI per 100 grams</th>
			        </tr>
			    </thead>
			  	<tbody>	
				<?php
				//get the top 3 foods for each vitmain
				$sSQL="Select fv.id_vitamin,v.name,fv.percentage/f.grams_default*100 'percentRDI',fv.color,fv.id_food from food f inner join food_vitamins fv on fv.id_food=f.id_food inner join vitamins v on v.id_vitamin=fv.id_vitamin where f.id_food=".$_GET["f"]." order by percentage desc;";
				$result2 = $conn->query($sSQL) or die($conn->error);
				while($row2 = $result2->fetch_assoc())  {
					$irow=$irow+1;
					if ($irow==1){
						$iMax=intval($row2["percentRDI"]);
					    $percent=100;
					    }
					 else {$percent=round($row2["percentRDI"]*100/$iMax);}
					//echo $iMax.":".$percent."<br>"
					?>
		        <tr>
		            <td style="width:15%"><a href="/vitamin.php?v=<?=$row2["id_vitamin"]?>"><?=$row2["name"]."</br>"?></a></td>
		            <td style="width:5%"><b><?=round($row2["percentRDI"],0)?></b></td>
		            <td style="width:80%"><span class="graph" style="width:<?=$percent?>%;float: left;text-indent:0px;">&nbsp;</span></td>
		        </tr>  
				<?php
			    };
				?>
				</tbody>
				</table> 
		</div>
	<div class="bodyText">	
		<div class="row">
			<div class="col-md-12">
				<?php if (strlen($row["intro"])>0) 
				{
				echo $row["intro"];
				}				
				?>

			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
				<?php echo $row["description"]?>
			</div>
		</div>	

		<div class="row">
			<div class="col-md-12">
				<?php if ($row["wh_id"]>0) {
					?>
				<ul><li>Much grattidtude to George Mateljan,and the George Mateljan Foundation for <a href="http://www.whfoods.com/genpage.php?tname=foodspice&dbid=<?=$row["wh_id"]?>">www.whfoods.com</a></li></li></ul>
				<?php 
			     }
			    ?>
			</div>
		</div>	
	</div>
	</div>
<?php
}
?>
</div>

<div id="spacer" style="margin-top:20px;"></div>
<?php
require(root_dir.'/footer.htm');
?>
