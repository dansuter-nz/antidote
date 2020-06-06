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
$conn2=open_conn(); 
include 'header.php';  
?>  
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?
 
//this is the page where people get to comsume the recipe item that they have been viewing
//check out how many portion size options there are with this one.
//List as portion size options 
//show photo of this one + any other currently in cart...
//process person add recipes to profile.
//Show thanks you screen enjoy your meal.
//get the meal with suggested contirbution
//love_your_food.asp ASAP
//One DanaOne per meal for make at home
//Cost Plus One DanaOne per meal for in commerical service
//DanaOne per meal for in home service
//Cost is not optional
//DanaOne is optional
//add on takeaway options and danaone as checkboxes.
//Add recipes to order using ajax
//first thing we are inserting into meal time so SQL that.
?>
<div class="row">
    <div class="col-sm-12 col-xs-12">
         <h2>Average daily nutrition all time</h2>
         <?$x=$rwb[$getNutrients[1000,$_SESSION["id_people")]]?>
     </div>
</div>

<div class="row">
	<div class="col-sm-12 col-xs-12" style="">
	<h2>Nutrition from your last 10 meals</h2>
	</div>

<?
//start by showing average for the last 10 meals and inverse (ie missing vitamins)
 
$sSQL="Call get_meals_10 (".$_SESSION["id_people").")";
//x=rwe(sSQL)
$irow=0;
$result = $conn->query($sql);
while($row = $result->fetch_array(MYSQLI_BOTH))
	{ 
		//Note from get_meals_10 sql you recieve multiple meals and unique meal-recipe combination
		//ok so get meal details
	
		$irow=$irow+1;
		$iMeal=$row["id_meal"];
		$dDate=$row["id_meal"];
		$s="<b>You were served by ".$row["served_name"]." on ".$CvbShortdateTime[$row["date_served"],true ]."</b></br>";
		do until $iMeal<>$row["id_meal"];
			//get all recipes
			$sRecipe=$row["recipe_name"];
			//sRecipeImage=rsTemp("image")
			$s=$s.'<a href="/recipe.asp?r='.$row["id_recipe"].'">'.$sRecipe."</a> (".$row["portion_name"].")<br>";
			$rsTemp.$movenext;
		loop;
		//rsTemp.moveprevious
		if ($irow $mod 2 ==1) {
			echo '</div><div class="row">'];
		}
		
		
		?>
		<div class="col-md-6 col-sm-6 col-xs-6">
         	<table style="">
         		<?
         		echo '<tr style="height:40px;"><td style="white-space:nowrap;" colspan="3">'.$s."</td></td></tr>";
         		echo '<tr style="height:40px;"><td style="white-space:nowrap;"><b>Vitamin / Mineral</b></td><td class="small-graph-name" align="center" ><b>(%)</b></td><td align="center"><b>Recommended Daily Intake</b></td></tr>';
	         	//Graph added 7/09/2015 Dan.
	         	$sSQL="";
				//x=rwe("")
				$sSQL="CALL `antidote`.`Recipe_Vitamins_people_eat`(".$iMeal.");"];
				$result2 = $conn2->query($sql);
				while($row2 = $result2->fetch_assoc())
				{
					$iVitcount=0;
	         		do until $rsTempA.$eof;
	         		$iVitcount=$iVitcount+1;
	         		if ($iVitcount>10) {exit do;}
	         		$width=cint($row2["Net_RDI"]);
	         		if ($width>200) {$width=195;}
	         		echo '<tr><td align="right"><a href="/vitamin.asp?v='.$row2["id_vitamin"].'">'.$row2["name"].' &nbsp;</a></td><td class="small-graph-name">('.round($row2["net_RDI"],0).')</td><td style="width:100%"><a title="'.round($row2["net_RDI"],0).'% of your Recommended Daily Intake" href="/vitamin.asp?v='.$row2["id_vitamin"].'"><div class="small-graph-line"  style="width:'.$width/2."%;background-color:".$row2["color"].'">&nbsp;</div></a></td></tr>';
				}
	         	$result2->free();
         	?>
         	<tr><td colspan="5"><hr></td></tr>
         </table>
		</div>
	<?
	}
	$s="";
	$result->free();

?>	
	</div>
		<div class="row">
			<div class="col-sm-12 col-xs-12" >
				*Note above percentages are based on USDA Figures, effects of cooking and juicing will affect these figures, use as a guideline only. For slow juicing Typically 20-30 percent is lost and 80-90% of the fibre is lost.
			</div>
		</div>
	</div>
</form>

<!--#include virtual="/footer.asp" -->





