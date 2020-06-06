<?php include 'header.php';
if(isset($_SERVER['HTTP_REFERER'])) {$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];}
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
if (isset($_SESSION["can_authorize"]))
{
    if (!$_SESSION["can_authorize"])
    {
    echo "<br><br><br><h1>Sorry you cannot view this page</h1>";
    exit();
    }
}
else
{
    echo "<br><br><br><h1>Sorry you cannot view this page</h1>";
    exit();
}
$conn=open_conn();
			//$check to $see if $user $has $an $active $open $recipe;
			if ($_GET["id"]!="") {
				$sRedirect="/add_recipe.php?id=".$_GET["id"];
				$sSQL="SELECT * FROM recipes where id_recipe=".$_GET["id"].";";
			} else {
				$sSQL="SELECT * FROM recipes where id_person=".$_SESSION["id_people"]." and temp=1;";
			
			$sRedirect="/add_recipe.php";
			}
			//echo $sSQL;
			$result = $conn->query($sSQL) or die($conn->error);
			$qtd = mysqli_num_rows($result);
		    //echo $qtd;
			//exit;
			if ($qtd==0) {
				$sSQL="call Insert_Temp_Recipe (".$_SESSION["id_people"].")";
				$result = $conn->query($sSQL) or die($conn->error);
				$sSQL="SELECT * FROM recipes where id_person=".$_SESSION["id_people"]." and temp=1;";
				$result = $conn->query($sSQL) or die($conn->error);
			}
            while($row = $result->fetch_assoc())
            {
			$id_recipe=$row["id_recipe"];
			$id_person=$row["id_person"];
			$name=$row["name"];
			$image=$row["image"];
			$how_to_make=$row["how_to_make"];
			$id_type=$row["id_type"];
			$uid_recipe=$row["uid_recipe"];
			$bshow_on_web=$row["show_on_web"];
			$bauthorized=$row["authorized"];
			$servings=$row["servings"];
			$brief=$row["brief"];
			$bauthCheck="";
		    }
		    $bwebCheck=false;
			if ($bauthorized) {$bauthCheck="Checked";}
			if ($bshow_on_web) {$bwebCheck="Checked";}
			
			//echo $bwebCheck;
			
			?>
		<form  id="myform" name="myform" action="/admin/ajax/save.php">
			<input type="hidden" name="t" value="recipe">
			<input type="hidden" name="uid" value="<?=$uid_recipe?>">
			<input type="hidden" name="file_name" id="file_name" value="<?=$uid_recipe?>.jpg">
			<input type="hidden" name="folder_name" id="folder_name" value="recipe">
			<input type="hidden" name="idr" id="idr" value="<?=$id_recipe?>">
			<div class="row row-centered">
				<div class="col-sm-12">
					<h3>Adding Recipe</h3>
				</div>
			</div>
			
 			<div class="row row-centered vcenter">
		        <div class="col-sm-3 indent10" >
		          Give it a Name
		        </div>
		        <div class="col-sm-5">
					<input type="text" size="40" maxlength="100" name="name" value="<?=$name?>">        
		        </div>
		        <div class="col-sm-4">
					<a class="button icon arrowright" href="/recipe.php?r=<?=$id_recipe?>">Preview on web</a>
		        </div>
		  	</div>
			
			<div class="row row-centered">
		       	<div class="col-sm-3">
					
	   			 </div>
		        <div class="col-sm-5">
							<div class="thumb-wrapper photo_holder_recipe" style="border:1px solid;">
							<span id="updatePhoto"  onclick="updatePhoto();">
							<img id="update_img" class="photo_holder_recipe" style="border:0px solid;" src="<?=$image?>" alt=" __Upload a photo" />
							</span>
							<pre id="log" style="height: 300px; overflow: auto;display:none;"></pre>
							</div>          
		        </div>
		        
		        <div class="col-sm-4">
						<textarea id="brief" name="brief" cols="30" rows="6" placeholder="Add a Brief Description of the recipe here.  This text will show on Menu page."><?=$brief?></textarea>
		   		</div>
		 	</div>
		  
		  	<div class="row row-centered">
		        <div class="col-sm-3 indent10" >
		          Choose Ingredients
		        </div>
		        <div class="col-sm-9">
					<input type="text" size="6" id="food_amount" name="food_amount" value="100">
					grams of 
					<select id="food_add" name="food_add">
						<?php //'$enumerate $foods $list;
						$sSQL="Select id_food,name from food order by name;";
						$result = $conn->query($sSQL) or die($conn->error);
						while($row = $result->fetch_assoc())  {echo "<option value='".$row["id_food"]."'>".$row["name"]."</option>";}
						?>
					</select>
					<input type="button" id="add_ingredient" class="button" value="Add Ingredient" onclick="Add_Ingredient(<?=$id_recipe?>)">
		        </div>
		    </div>
 			<div class="row row-centered">
		        <div class="col-sm-3 indent10" >
		          Ingredients Added
		        </div>
		        <div id="ingredients_list" class="col-sm-9">
		        	<?php
		        	$sSQL="call Recipes_By_ID (".$id_recipe.")";
		        	$result = $conn->query($sSQL) or die($conn->error);
                    $qtd = mysqli_num_rows($result);
			        if ($qtd==0) 
			        {
		        		echo "<b><i>Add some ingredients to your menu by using the add ingredient button above....</i></b>";
		        	} 
		        	else 
		        	{
		        		while($row = $result->fetch_assoc())  {
		        			echo '<div style="padding:5px;" id="ingredient'.$row["id_recipe_food"].'">'.$row["qty_grams"].' grams of <a href="/food.php?f='.$row["id_food"].'">'.$row["name"].'</a> <button class="button danger icon remove"  onclick="Delete_Ingredient('.$row["id_recipe_food"].'); return false;">Remove item</button></div>';
		        		}
		        	}
		        	?>
		        </div>
		    </div>
		  
 			<div class="row row-centered">
	        	<div class="col-sm-3 indent10" >
	          		Recipe Type
	       		</div>
		        <div class="col-sm-9">
					<select name="type"><option value="0">Select Type</option>
					<?php
					$conn->close();
					$conn=open_conn();
					$sSQL="Select id_recipe_type,name from recipe_types order by name;";
					$result = $conn->query($sSQL) or die($conn->error);
					while($row = $result->fetch_assoc())  
					{
						echo "<option value='".$row["id_recipe_type"]."'";
						if ($id_type==$row["id_recipe_type"]) {echo " selected";}
						echo ">".$row["name"]."</option>";
					}
					?>
					</select>    
	          		for no of people
					<input type="text" name="servings" id="servings" value="<?=$servings?>" size="2">    
		        </div>
	    	</div>
			<div class="row row-centered">
		        <div class="col-sm-3 indent10" >
		         	Add serving sizes
		        </div>
		        <div class="col-sm-9">
							<select name="portion_size" id="portion_size"><option value="0">Select</option>
							<?php //'enumerate portions;
								$sSQL="SELECT id_portion_size,name FROM portion_sizes order by id_portion_size;";
								$result = $conn->query($sSQL) or die($conn->error);
								while($row = $result->fetch_assoc()) {
									echo "<option value='".$row["id_portion_size"]."'";
									if ($id_type==$row["id_portion_size"]) {echo " selected";}
									echo ">".$row["name"]."</option>";
	                            }
							?>
							</select>
							is <input type="text" size="2" id="contribuition_amount" name="contribuition_amount" value="5.00">
							<input type="button" id="contribution" class="button" value="Add Contribution" onclick="add_contribution(<?=$id_recipe?>)">  
				</div>
			</div>
			<div>
				<div class="row row-centered">
	        		<div class="col-sm-3 indent10" >
	          			Serving sizes
	        		</div>
	        		<div id="contribution_list" class="col-sm-9">
		        	<?php //check to $see $what $ingredients $are $already $added?';
							$sSQL="Call get_recipe_with_contribution (".$id_recipe.")";
                            $result = $conn->query($sSQL) or die($conn->error);
							
							$prices="";
							$icount=0;
                            $qtd = mysqli_num_rows($result);
			               if ($qtd==0) 
			               {
		        		   echo "<b><i>Add some pricing options.</i></b>";
		        	       } 
		        	       else 
		        	       {
								while($row = $result->fetch_assoc()) 
								{
									$icount=$icount+1;
									echo '<div  style="padding:5px;" id="portion_size'.$row["id_recipe_contribution"].'">'.$row["portion_name"]." $".$row["amount_currency"].' <button class="button danger icon remove"  onclick="Delete_Contribution('.$row["id_recipe_contribution"].'); return false;">Remove</button></div>';
							    }
							}
		        	?>
	        		</div>
	    		</div>
	 			<div class="row row-centered">
			        <div class="col-sm-12">
								<textarea id="makeit" name="makeit"  placeholder="Descibe how this recipe should be prepared"><?=$how_to_make?></textarea>
			        </div>
			    </div>
				<script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'makeit');
            	</script>
            </div>
 		    <div class="row " style="">
 		    	<div class="col-sm-4"></div>
		    	<div class="col-sm-8">
		    		<?php if ($_SESSION["can_authorize"]==true) {?>
		    		<input type="checkbox" name="show_on_web" id="show_on_web" <?=$bwebCheck?>> <label for="show_on_web">show on website.</label><br>
		    		<?php }?>
		    		<?php if ($_SESSION["can_authorize"]==false) {?>
		    		<input type="checkbox" name="show_on_web" id="show_on_web" <?=$bwebCheck?>> <label for="show_on_web">Request this recipe to be added to the website.</label><br>
		    		<?php }?>
		      </div>
		    </div>			
		    <div class="row row-centered" style="text-align:center">
				<div class="col-sm-5">
		      	</div>
		    	<div class="col-sm-4 text-left">
		           <button  class="button" onclick="Save_Form(); return false;">Save Changes</button>
		      	</div>
		    	<div class="col-sm-3 text-left">
		    			<?php if ($_SESSION["can_authorize"]==true) {
		    			?>
		          	<button  class="button danger icon remove" onclick="Delete_Record('recipe','<?=$uid_recipe?>','/recipes.php'); return false;">Delete Record</button>
		           <?php
		       		}
		       		?>
		           
		      </div>
		    </div>
			<div id="popup" style="left: 701.5px; position: absolute; top: 106px; z-index: 9999; opacity: 1; display: none; background:#fff;">
		        <span class="button b-close"><span>X</span></span>
				<div id="uploader">
					<p>Your browser does not have Flash, Silverlight or HTML5 support.</p>
				</div>
		    </div> 
		</form>



<?php include 'footer.htm';?>
