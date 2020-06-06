<?php 
session_start();
include 'header.php';
if (isset($_SERVER['HTTP_REFERER'])){$_SESSION["last_page"]=$_SERVER['HTTP_REFERER'];}

$htm="";
?>
<div id="main-content">
  <div class="recipes">
<?php
if (isset($_GET["r"])=="1") {$_SESSION["s"]="";}
if (isset($_GET["s"])!="") {
	$_SESSION["s"]=$_GET["s"];
}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<form name="<form action="/recipes.php">
<div class="foods container-fluid" >
	<div class="row">
		<div class="col-">
		  	<h1>Recipes</h1>
		 </div>
	</div>
	<div class="row">
		<div class="col-md-3">
		 <input type="text" value="<?=$_SESSION["s"]?>" name="s" id="s" size="20" placeholder="Find Recipes" onkeyup="getpage('/admin/ajax/recipe_search.php?s='+this.value);event.cancelBubble = true;"> 
		 </div>
		 <div class="col-md-3">
		 <input name="sub" id="sub"  value="Search" type="submit" > 
		 </div>
		 <div class="col-md-2">
		 <a href="/recipes.php?r=1">Reset Search</a>
		 </div>
		 <div class="col-md-2"> <a href="/recipes.php?v=g"><img src="/images/common/gallery.svg" alt="" title="gallery" /> Gallery</a>
		 	</div>
		 	<div class="col-md-2">
		 <a href="/recipes.php?v=d"><img src="/images/common/list.svg" alt="" title="gallery" /> Details</a>
		 </div>
	</div>
   <!-- add search bar for seacrching by vitamins-->
    <?php
    $conn=open_conn();
    $s="<div class='row'>";
    $sSQL="SELECT name FROM vitamins";
    $result = $conn->query($sSQL) or die($conn->error);
    while($row = $result->fetch_assoc())  
      {// show vitamin links
        $link="/recipes.php?s=".$row["name"];
        $s=$s."<div class='col-' style='margin-right:5px;'>";
        $s=$s."<a href='/recipes.php?s=".$row["name"]."'>".$row["name"]."</a> | ";
        $s=$s."</div>";
      }
      $s=$s."</div>";
      echo $s;
      $s="";
    ?>
	
		<?php 
		if (isset($_SESSION["can_authorize"])) {
		if ($_SESSION["can_authorize"]) {?>
			<div class="row">
					<div class="col-sm-12 col-sm-12">
						<h3><a class="button icon edit" href="/add_recipe.php">Add A Recipe</a></h3>
					</div>
			</div>
		<?php
			}
		}
 		if ($_SESSION["id_meal"]>0)
	        {echo "<div class='row'><div class='col-'><a href='/loveyourfood.php'>
	          <img src='/images/mymeal.png' alt='Your meal' >
	          </a></div>
	          <div class='col-'>
	          ".$_SESSION["mymeal_summary"]."
	          </div>
	          </div>";}
		?>

		<?php
		echo "<br>".make_recipes("");
		?>
</div>
</form>
<div id="spacer" style="margin-top:20px;"></div>
<script>
$("#show").hover()
function Edit_Recipe_Vis(idR)
{
  if ($("#show"+idR ).attr("class")=="glyphicon glyphicon-star-empty")
  {
    $("#show"+idR ).addClass("glyphicon-star");
    $("#show"+idR ).removeClass("glyphicon-star-empty");
    $.get("/admin/ajax/edit_showonweb_recipe.php?r="+idR+"&v=1");
    }
    else 
      {
    $("#show"+idR ).addClass("glyphicon-star-empty");
    $("#show"+idR ).removeClass("glyphicon-star");
    $.get( "/admin/ajax/edit_showonweb_recipe.php?r="+idR+"&v=0");
        }

}

$("#favourite").hover()
function Add_favourite(idR)
{
<?php if (!isset($_SESSION["email"])) {echo "window.location.href='/login.php';";}?>
  if ($("#favourite"+idR ).attr("class")=="glyphicon glyphicon-star-empty")
  {
    $("#favourite"+idR ).addClass("glyphicon-star");
    $("#favourite"+idR ).removeClass("glyphicon-star-empty");
    $.get( "/admin/ajax/add_favourite_recipe.php?r="+idR+"");
    }
    else 
      {
    $("#favourite"+idR ).addClass("glyphicon-star-empty");
    $("#favourite"+idR ).removeClass("glyphicon-star");
    $.get( "/admin/ajax/add_favourite_recipe.php?r="+idR+"&d=1");
        }

}
</script>
<?php include 'footer.htm';?>