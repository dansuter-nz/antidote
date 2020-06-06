<?php include 'header.php';
if(isset($_SERVER['HTTP_REFERER'])) {
$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];
}
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
if (isset($_SESSION["email"])==false)
{
	header("Location: /login.php");  
	exit();
}
$conn=open_conn();
	//check to see if user chosen to add a recipe of edit one?
	        if ($_GET["id"]!="") 
		    {
				$id_food=$_GET["id"];
			} 
			else 
			{
				//bad ID report to user.
				$sSQL="call Insert_Temp_Food (".$_SESSION["id_people"].")";
				$result = $conn->query($sSQL) or die($conn->error);
				//get the ID of the food generated.
				$sSQL="SELECT max(id_food) as id_food_max FROM food where id_person_add=".$_SESSION["id_people"].";";
				$result = $conn->query($sSQL) or die($conn->error);
				while($row = $result->fetch_assoc())  
				{
				$id_food=$row[0];
			    }
			}	
			$sSQL="SELECT * FROM food where id_food=".$id_food.";";
			$result = $conn->query($sSQL) or die($conn->error);
			while($row = $result->fetch_assoc())  
			{
			$id_food=$row["id_food"];
			$id_person=$row["id_person_add"];
			$uid_food=$row["uid_food"];
			$name=$row["name"];
			$Intro=$row["Intro"];
			$Image_path=$row["Image_path"];
			$Description=$row["Description"];
			$default_unit=$row["default_unit"];
			$bshow_on_web=$row["visible"];
			$wh_id=$row["wh_id"];
			$id_supplier=$row["id_supplier"];
			$link=$row["link"];
			$cost=$row["cost"];
			$grams_default=$row["grams_default"];
			if ($bshow_on_web) 
				{$bwebCheck="Checked";}
		    }
			
			if (! isset($id_supplier)) {
				$id_supplier=0;
			}
			$sSQL="Select s.id_supplier,s.name from suppliers s where  (".$_SESSION["id_people"].")";
			$result = $conn->query($sSQL) or die($conn->error);
			$sSelect='<select id="supplier" name="supplier">';
			$sSelect=$sSelect.'<option value="0">Not selected</option>';
			while($row = $result->fetch_assoc())  
			{		
				if ($id_supplier==$row["id_supplier"]) {$s="Selected";}
				$sSelect=$sSelect.'<option value="'.$row["id_supplier"].'" '.$s.">".$row["name"]."</option>";
            }
			$sSelect=$sSelect."</select>";
		    
			//x=rwe(sSQL)
			?>
			<form  id="myform" name="myform" action="/admin/ajax/save.php">
			<input type="hidden" name="t" value="food">
			<input type="hidden" name="uid" id="uid" value="<?=$uid_food?>">
			<input type="hidden" name="file_name" id="file_name" value="<?=$uid_food?>.jpg">
			<input type="hidden" name="folder_name" id="folder_name" value="food">
			<input type="hidden" name="idf" id="idf" value="<?=$id_food?>">
			<div class="row row-centered">
				<div class="col-sm-12">
					<h3>Adding Food</h3>
				</div>
			</div>
 			<div class="row row-centered">
		        <div class="col-sm-3 indent10" >
		          Food Name
		        </div>
		        <div class="col-sm-5">
					<input type="text" size="50" maxlength="100" name="name" value="<?=$name?>">        
		        </div>
		        <div class="col-sm-4">
					<a class="button icon arrowright" href="/food.php?f=<?=$id_food?>">Preview on web<a>
		        </div>


		    </div>
			<div class="row row-centered">
		        <div class="col-sm-3 indent10" >
		          Picture
		        </div>
		        <div class="col-sm-9">
				<div class="thumb-wrapper photo_holder_food"  style="border:1px solid;">
				<span id="updatePhoto" onclick="updatePhoto();">
				<img id="update_img"  class="photo_holder_food"  style="border:0px solid;" src="<?=$Image_path?>"  />
				</span>
				<pre id="log" style="height: 300px; overflow: auto;display:none;"></pre>
				</div>         
		        </div>
		    </div>
 			<div class="row row-centered">
		        <div class="col-sm-3 indent10" >
		         	Add Vitamin
		        </div>
		        <div class="col-sm-9">
				<select name="vitamin" id ="vitamin"><option value="0">Select Vitamin</option>
						<?php 
						//$enumerate $foods $list;
						$sSQL="SELECT  v.name, v.`id_vitamin`,  v.`color` FROM `vitamins` v order by v.name;";
						$result = $conn->query($sSQL) or die($conn->error);
			            while($row = $result->fetch_assoc())  {	
							echo "<option value='".$row["id_vitamin"]."'>".$row["name"]."</option>";					
						}
						echo "</select>";
					?>
		        % of RDI <input type="text" name="percentage" id="percentage" value="<?=$percentage?>" size="2"> for serving size of   
		        <input type="text" size="6" maxsize="5" id="food_amount" name="food_amount" value="<?=$grams_default?>" disabled> grams
		        <input type="button" id="add_vitamin" class="button" value="Add Vitamin" onclick="Add_Vitamin()">
		        </div>
		    </div>
 			<div class="row row-centered">
		        <div class="col-sm-3 indent10" >
		          Vitamins
		        </div>
		        <div id="ingredients_list" class="col-sm-9">
		        	<?php 
		        	//$check to $see $what $ingredients $are $already $added?;
						$sSQL="SELECT `id_food_vitamin`, `id_food`, v.name, v.`id_vitamin`,`percentage`,  v.`color` FROM `food_vitamins` fv inner join vitamins v on v.id_vitamin=fv.id_vitamin where id_food=".$id_food." order by fv.percentage desc;";
					$result = $conn->query($sSQL) or die($conn->error);
			        $qty=$conn->affected_rows;
		        	if ($qty==0) {
		        		echo "<b><i>Add some vitamns to your food by using the add vitamin button above....</i></b>";
		        	} 
		        	else {
		        		while($row = $result->fetch_assoc())  {	
		        			echo '<div id="vitamin'.$row["id_food_vitamin"].'">RDI % '.$row["percentage"]." of ".$row["name"].' <button class="button danger icon remove"  onclick="Delete_food_vit('.$row["id_food_vitamin"].'); return false;">Remove item</button></div>';
		        			}
		        	}
		        	?>
		        </div>
		    </div>
			<div class="row">
 					<div class="col-sm-3 indent10" >
		       Who did we get this from
		      </div>
					<div id="ingredients_list" class="col-sm-9">
					<?=$sSelect?>
					</div>
			</div>
			<div class="row">
 					<div class="col-sm-3 indent10" >
		       Our Cost incl GST
		      </div>
					<div class="col-sm-9">
					<input name="cost" id="cost"  size="5" type="text" value="<?=$cost?>">
					</div>
			</div>	
			<div class="row">
 					<div class="col-sm-3 indent10" >
		       	Link to Website
		      </div>
					<div class="col-sm-9">
						<input name="link" id="link" size="100" type="text" value="<?=$link?>">
					</div>
			</div>
			
			
			
			
 			<div class="row row-centered">
		        <div class="col-sm-12">
							<textarea id="intro" name="intro"><?=$Intro?></textarea>
		        </div>
		    </div>
 			<div class="row row-centered">
		        <div class="col-sm-12">
							<textarea name="description" id="description" rows="10" cols="80"><?=$Description?></textarea>
							<script>
                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'description');
                CKEDITOR.replace( 'intro');
            </script>
		        </div>
		    </div>
		    
 		    <div class="row " style="">
 		    	<div class="col-sm-4"></div>
		    	<div class="col-sm-8">
		    		 <?if ($_SESSION["can_authorize"]==true) {?>
		    		<input type="checkbox" name="show_on_web" id="show_on_web" checked="<?=$bwebCheck?>"> <label for="show_on_web">Show this food to the live website.</label></br>
		          <?}?> 
		      </div>
		    </div>			
		    <div class="row row-centered" style="text-align:center">
					<div class="col-sm-5">
		      </div>
		    	<div class="col-sm-4 text-left">
		           <button  class="button" onclick="Save_Form(); return false;">Save Changes</button>
		      </div>
		    	<div class="col-sm-3 text-left">
		           <button  class="button danger icon remove" onclick="Delete_Record('food','<?=$uid_food?>','/foods.php'); return false;">Delete Record</button>
		      </div>
		    </div>
		</form>
			<div id="popup" style="left: 701.5px; position: absolute; top: 106px; z-index: 9999; opacity: 1; display: none; background:#fff;">
		        <span class="button b-close"><span>X</span></span>
						<div id="uploader">
							<p>Your browser doesn't have Flash, Silverlight or HTML5 support.</p>
						</div>
		    </div> 
<?php

?>
