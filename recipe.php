<?php 
include 'header.php';
$_SESSION["last_page"]=$_SERVER['REQUEST_URI'];
?>

<div class="foods" >
<?php
$conn=open_conn();
$sSQL = "SELECT * FROM recipes where id_recipe=".$_GET["r"].";";
$result = $conn->query($sSQL) or die($conn->error);
	while($row = $result->fetch_assoc())  
	{
     $irow=0;
    if ($irow % 2==1) 
    	{$strClass="light_blue_row";} 
    	else {$strClass="white_row";}
    $irow=$irow+1;
    $id_recipe=$row["id_recipe"];
    $id_person=$row["id_person"];
    $name=$row["name"];
    $image=str_replace("med","large",$row["image"]);
    //echo $image;
    //exit;
    $how_to_make=$row["how_to_make"];
    $id_type=$row["id_type"];
    $servings=$row["servings"];
    $brief=$row["brief"];
    $uid_recipe=$row["uid_recipe"];
    $sArray="";
    $sSQL="";
    $sSQL="Call get_recipe_with_contribution (".$_GET["r"].")";
    $result2 = $conn->query($sSQL) or die($conn->error);
    $prices="";
    $icount=0;
    while($row2 = $result2->fetch_assoc())  
	 {
      $irow2=0;    
      $icount=$icount+1;
	  if ($icount==1) {
		$prices=$prices.'<div class="col-"><a class="button eatme primary" href="/loveyourfood.php?r='.$id_recipe."&p=".$row2["id_portion_size"].'">$'.$row2["amount_currency"]." for ".$row2["portion_name"]." </a>&nbsp;&nbsp;</div>";
	   } 
	  else {$prices=$prices.'<div class="col-"><a class="button eatme" href="/loveyourfood.php?r='.$id_recipe."&p=".$row2["id_portion_size"].'">$'.$row2["amount_currency"]." for ".$row2["portion_name"]." </a></div>&nbsp;&nbsp; ";}
     }
$prices=$prices.'<div class="col-"><a class="button eatme icon home" href="/loveyourfood.php?id='.$id_recipe.'">DIY</a></div>&nbsp;&nbsp;';
//prices=replace(prices,"<a ","<li ")
$prices='<div class="row">'.$prices."</div>";
$reviews="";
$sSQL="call get_reviews_by_recipe (".$_GET["r"].");";
$irow=0;
$reviews=makeReviewStars($sSQL,5);
}
?>
		<div class="row">
				<div class="col-">
				<h1 style="" itemprop="name"><a href="/recipe.php?r=<?=$id_recipe?>"><?=$name?></a></h1>
			</div>

			<div class="col-">
			<?php if ($_SESSION["can_authorize"] || $id_person==$_SESSION["id_people"]): ?>
			<a class="button icon edit" style="min-width:70px;float:left;margin-left:0px;" href="/add_recipe.php?id=<?=$id_recipe?>">Edit</a>
			<?php endif;?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-7">
				<img src="<?=$image?>" alt="name">
			</div>
			<div class="col-sm-5">
			     <h4><?=$brief?></h4>
			</div>
		</div>
		<div class="row">
	        <div class="col-sm-12" >
	          <h2>Ingredients</h2>
	        </div>
		</div>
		<div class="row">        
	        <div id="ingredients_list" class="col-sm-12">
	        	<ul>
	        	<?php
	        	$conn=open_conn();
	        	$sSQL="call Recipes_By_ID (".$id_recipe.")";
                $result4 = $conn->query($sSQL) or die($conn->error);
                while($row3 = $result4->fetch_assoc())  
                     {echo "<li>".$row3["qty_grams"].' grams of <a href="/food.php?f='.$row3["id_food"].'">'.$row3["name"]."</a></li>"."\n";}
	        	?>
	        	</ul>
	        	<div><b>Serves <?=$servings?> people</b></div>
	      	</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-sm-12">
				<?=$prices?>
			</div>
		</div>
		<div class="row">			 												
		        <div class="col-sm-12 col-sm-12">
	         <?php
	         $graph="";	
         		$graph=$graph.'<table style="border: 1px solid #000; "><tr style="height:40px;border-top:1px solid #000;border-bottom:1px solid #000;"><td style="white-space:nowrap;"><b>Vitamin / Mineral</b></td><td class="small-graph-name" align="center" ><b>(%)</b></td><td align="center"><b>Recommended Daily Intake</b></td></tr>';
         				         	//Graph added 23/06/2015 Dan.
         		$conn=open_conn();
         		$sSQL="";
				$sSQL="CALL Recipe_Vitamins_cache (".$id_recipe.");";
				 $result3 = $conn->query($sSQL) or die($conn->error);
				 $conn=open_conn();
				 $sSQL="CALL Recipe_Vitamins (".$id_recipe.");";
				 //echo $sSQL;
				 $result3 = $conn->query($sSQL) or die($conn->error);
				 $icount=0;
				 $sGood="<h3>Role in Health Support: </h3>";
				 $multiplier=1;
	         	while($row2 = $result3->fetch_assoc())  
	         	    {
	         		$icount=$icount+1;
	         		$width=intval($row2["net_RDI"]);
	         		//echo "here ".$row2["net_RDI"];
	         		if ($icount<4 || $width>99) {
	         			$sGood=$sGood." <b>".$row2["name"].'</b> (<a href="/vitamin.php?v='.$row2["id_vitamin"].'#function">'.$row2["health_benefits"]."</a>). ";
	         		}
	         		if ($icount==1) { //check to see max width
	         			if ($width>200) { 
	         				$multiplier=1;
	         			} else {
	         				$multiplier=1;
	         			}
	         		}
	         		if ($width>200) {$width=196;}
	         		if ($width>100) {$width=98;}
	         		$cellwidth=$width*$multiplier;
	         		//echo $cellwidth." ".$width." ".$multiplier;
	         		//exit;
	         		$graph=$graph.'<tr><td align="right"><a href="/vitamin.php?v='.$row2["id_vitamin"].'">'.$row2["name"].' &nbsp;</a></td><td class="small-graph-name">('.round($row2["net_RDI"]).')</td><td style="width:100%"><a title="'.round($row2["net_RDI"]).'% of your Recommended Daily Intake" href="/vitamin.php?v='.$row2["id_vitamin"].'"><div class="small-graph-line"  style="width:'.$cellwidth."%;background-color:".$row2["color"].'">&nbsp;</div></a></td></tr></div>';
                    }
	         	$graph=$graph."</table>";
	         	echo $sGood;
	         	
 
	         	?>
	         	</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-sm-12">
				<p><h3>Recipe Directions</h4></p>
				<p><?=$how_to_make?></p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-sm-12">
			<p><h3>Vitamins Distribution</h3></p>
			<?=$graph?>
			</div>
		</div>



		<div class="row">
			<div class="col-sm-12 col-sm-12" >
				<p style="color: #888;">The above graph is an indication of the percentages for each vitamin and mineral that the recipe provides for you. The numbers are the percentage of the Recommended daily intake, 100 means that is all you require for that vitamin or mineral for 1 day.
				*Note above percentages are based on USDA Figures, effects of cooking and juicing will affect these figures, use as a guideline only. For slow juicing Typically 20-30 percent is lost and 80-90% of the fibre is lost.</p>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-sm-12">
			<p><h3>Reviews</h3></p>
				<?=$reviews?>
			</div>
		</div>
	</div>
	</div>
</div>
</div>
<div id="spacer" style="margin-top:20px;"></div>
<?
php include 'footer.php';
?>

