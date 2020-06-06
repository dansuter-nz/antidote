<?php include 'header.php';
if(isset($_SERVER['HTTP_REFERER'])) {$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];}
?>
<div class="foods" >
	<h1>Antidote Foods</h1>
        <div class="row">
			<div class="col-md-12">
			 <input type="text" size="40" placeholder="Search for food" onkeyup="getpage('/admin/ajax/food_search.php?s='+this.value);"> <a href="/foods.asp">Reset Search</a>
			 | <a href="/foods.php?v=g">Gallery</a>
			 | <a href="/foods.php?v=d">Details</a>
			 </div>
		</div>	
		<?php 
	   if ($_SESSION["can_authorize"]) 
		{?>
		<div><a href="/add_food.php" class="button icon edit">Add A Food</a></div>
		<?php
		}
		?>
       <div id="DvAllFoods">
		<?php

		echo "<br>".make_food_rows("");
		?>
		</div>
</div>
<div id="spacer" style="margin-top:20px;"></div>
<?php
include 'footer.htm';
?>