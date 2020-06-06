<?php
session_start();
$_SESSION["redirect_to"]=$_SERVER['HTTP_REFERER'];
//check to see if logged in if not redirect
if (isset($_SESSION["email"])==false)
{
header("Location: /login.php");  
exit();
}

include 'header.php';
$conn=open_conn();
?>
<style>
.thumb-wrapper {position:relative;}
.thumb-wrapper span {position:absolute;top: 0px;left: 0px;width: 100%;height: 100%;z-index: 100;background: transparent url(/images/whitecam.png) no-repeat;}
</style>
<div class="foods container-fluid" style="width:960px" >
	<div class="row">
		<td>
		  	<h2>Here you can change some details about your account.</h1>
		 </div>
	</div>




<input type="hidden" name="t" value="people">
<input type="hidden" id="uid" name="uid" value="<?=$_SESSION["uid_people"]?>">
<input type="hidden" id="file_name" name="file_name" value="<?=$_SESSION["uid_people"]?>.jpg">
<input type="hidden" name="folder_name" id="folder_name" value="people">
<input type="hidden" name="idp" id="idp" value="<?=$_SESSION["id_people"]?>">
	<table style="width:100%">	
	<tr>
		<td colspan="2">
			<h1>Profile</h1>
		</td>
	</tr>
	<tr>
			<td>
	  		Name 
			</td>
			<td>
	  		<input type="text" size="30" name="name" id="name" value="<?=$_SESSION["name"]?>">  
			</td>
	</tr>
	<tr>
			<td>
	  		Email 
			</td>
			<td>
	  			<input type="text" size="30" name="email" id="email" value="<?=$_SESSION["email"]?>">  
			</td>
	</tr>
	<tr>
	    <td>
	      Password
	    </td>
        <td>
        	<input type="password" size="30" name="password" id="password" value="<?=$_SESSION["password"]?>">           
        </td>
	</tr>	        			
	<tr>
		<td>
	          
        </td>

		<td>
			<div class="thumb-wrapper photo_holder_person" style="border:0px solid;">
			
			<span id="updatePhoto"><img id="update_img" onclick="updatePhoto();" style="border:0px solid;" class="photo_holder_person" src="<?=$_SESSION["image_path"]?>" alt="<?=$_SESSION["name"]?>" /></span>
			<pre id="log" style="height: 300px; overflow: auto;display:none;"></pre>
			</div>
		</td>
	</tr>   		        						

	<tr>
        <td colspan="2">
          <textarea id="about_me" name="about_me"><?=$_SESSION["about_me"]?></textarea>
        </td>
        <script>

        CKEDITOR.replace( 'about_me');
			      CKEDITOR.config.toolbar = [
					   ['Styles','Format','Font','FontSize'],
					   '/',
					   ['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-',]
					] ;
    	</script>
	</tr>

	<tr>
		<td colspan="2">
	        <button type="button"  class="button" onclick="Save_Form()">Save Changes</button>
		</td>
	</tr>
</table>
<table>
	<tr>
	  <td  colspan="2">
	     <h2>My average Daily Nutrition past week. <a href="/my_nutrition.php">View more stats.</a></h2>
	     <?php echo getNutrients(7,$_SESSION["id_people"])?>
	  </td>
	</tr>
		

	    
	<tr>
		<td>
			<h3>My Recipes</h3>

	         <a class="button icon add" href="/add_Recipe.asp">Add a New Recipe..</a>
	     </td>
	</tr>
	<tr>
		<td>
	    <?php
	    //$check to $see if $person $has $any $Recipes;
		$sSQL= "CALL `antidote`.`Recipes_By_Person`('".$_SESSION["id_people"]."');";
		//x=rwb(sSQL)
		$s="<div class='row'>";
		$result = $conn->query($sSQL) or die($conn->error);
		while($row = $result->fetch_assoc())  
		{
		$s=$s.'
	        <div class="col-" >
	         <a class="" href="add_Recipe.php?id='.$row["id_recipe"].'"><img src="'.$row["image"].'" alt="'.$row["name"].'"><br>'.$row["name"].'</a>
	        </div>';
	    }
	    echo $s."</div>";
	    ?>
	</td>
	</tr>
</table>
	
	<div id="popup" style="left: 701.5px; position: absolute; top: 106px; z-index: 9999; opacity: 1; display: none; background:#fff;">
        <span class="button b-close"><span>X</span></span>
				<div id="uploader">
					<p>Your browser does not have Flash, Silverlight or HTML5 support.</p>
				</div>
    </div> 
    </form>
</div>

<?php include 'footer.htm';?>
