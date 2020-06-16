<?php
define('root_dir',"/var/www/html/antidote");
require(root_dir.'/admin/functions.php');

//var_dump($_SESSION);
$login="";
if (isset($_SESSION['email'])) 
  {
    $login = $login . "<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='/login.php' role='button' data-toggle='dropdown'><img src='".$_SESSION["image_path_icon"]."' style='' alt='me'></a>";
    $login = $login . "<div class='dropdown-menu' >";
    $login = $login . "<a class='dropdown-item' href='/me.php'>My Info</a>";
    if ($_SESSION["can_authorize"]) {
    $login = $login . "<a class='dropdown-item' href='/admin/data/index.php'>Data Admin</a>";
    }
    $login = $login . "<a class='dropdown-item' href='/logout.php'>Logout</a>";
    $login = $login . "</div></li>";
  }
else
  {
    $login="<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='/login.php' class='navbarDropdown' role='button' data-toggle='dropdown'>Login</a><div class='dropdown-menu' aria-labelledby='navbarDropdown'><a class='dropdown-item' href='/login.php'>Login</a><a class='dropdown-item' href='/register.php'>Register</a></div></li>";
  }
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script src="/files/js/bs431-jq331.js"></script>
<script>

</script>
<link rel="stylesheet" href="/files/css/main-bs431.css" />
<link rel="stylesheet" href="/files/css/gh-buttons.css" />

<?php 
//echo $sScriptName;
//exit;
if ($sScriptName=="ADD_RECIPE.PHP" or  $sScriptName=="ADD_FOOD.PHP" or $sScriptName=="ME.PHP" or $sScriptName=="PROJECTS/DIRECTDEMOCRACY/COMMENT.PHP") 
  {
?>
 <!--file uploader-->

<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/themes/base/jquery-ui.css" type="text/css" />
<link rel="stylesheet" href="/files/plupload-3.1.2/js/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>

<!-- production --> 
<script type="text/javascript" src="/files/plupload-3.1.2/js/plupload.full.min.js"></script>
<script type="text/javascript" src="/files/plupload-3.1.2/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>


<!-- debug
<script type="text/javascript" src="/files/plupload-3.1.2/js/moxie.js"></script>
<script type="text/javascript" src="/files/plupload-3.1.2/js/plupload.dev.js"></script>
<script type="text/javascript" src="/files/plupload-3.1.2/js/jquery.ui.plupload/jquery.ui.plupload.js"></script>
-->
<script type="text/javascript" src="/files/plupload-3.1.2/js/upload.js"></script>

<script src="/files/jquery.bpopup.min.js"></script>
   <!--RTE rich text editor-->
  <!-- Make sure the path to CKEditor is correct. -->
  <script src="/files/ckeditor/ckeditor.js"></script>
  <script>

function updatePhoto() 
{
//UPDATE PHOTO so load a popup with image uploader in it.
$('#popup').bPopup();
}

  //$(".editor").jqte({change: function(){ alert("The editor is changed"); }});
function Save_Form()
  {//alert("Saving "+uid);
  //go through form and update items, build string to save
  sURL="/admin/ajax/save.php"
  //s="?t=food&uid="+$("#uid").val()+"&name="+$("#intro").val()+"&email="+$("#email").val()+"&password="+$("#password").val()+"&about_me="+$("#about_me").val()
  //sURL=sURL+s;
  //alert(sURL);
  //var myValue = $("#intro").getValue();
  //alert (myValue);
  for ( instance in CKEDITOR.instances )
    CKEDITOR.instances[instance].updateElement();
  var serializedForm = $("#myform").serialize();
  //alert("serializedForm is:\n=====\n"+serializedForm +"\n=====");
   $.post(sURL, $("#myform").serialize(),function(data, status)
   {//update url for people pic on client side
        $(".success").show();
        //check output for login redirect due to timeout.
        var n = data.indexOf("login_form");
        //if (n==-1)
          //{document.location.href="/login.asp"};
        $('.success').html(data);
        $('.success').delay(3000).fadeOut('slow');
        
   })
   .fail(function() {
      alert(data); // or whatever
  });
  }           
</script>

<script>
  function Delete_Record(t,d,l)
  {
  sURL="/admin/ajax/delete.php?d="+d+"&t="+t
  //alert(sURL);
   $.get(sURL, function(data, status){
    document.location.href=l;
    alert(data);
    })
  }
  </script>
<?php } 
if ($sScriptName=="ADD_RECIPE.PHP"){?>
<script>
function Add_Ingredient(iR) {
//send food id + qty to form
//alert($("#ingredients_list").html());
if ($("#ingredients_list").html()=="Add from list above")
  { 
    $("#ingredients_list").html("")
  }
sURL="/admin/ajax/add_recipe_item.php?idf="+$("#food_add").val()+"&qty="+$("#food_amount").val()+"&idp=<?=$_SESSION["id_people"]?>"+"&idr="+iR
//alert(sURL);
 $.get(sURL, function(data, status){
  sIngredients=$("#ingredients_list").html();
  sIngredients=sIngredients+data;
  $("#ingredients_list").html(sIngredients);
    }).fail(function() {
    alert('error please login again.'); // or whatever
});
}

function add_contribution(iR) {
//send food id + qty to form
//alert($("#ingredients_list").html());
if ($("#ingredients_list").html()=="Add from list above")
  { 
    $("#ingredients_list").html("")
  }
sURL="/admin/ajax/add_contribution_item.php?idc="+$("#portion_size").val()+"&c="+$("#contribuition_amount").val()+"&idr="+iR
//alert(sURL);
 $.get(sURL, function(data, status){
  sIngredients=$("#contribution_list").html();
  sIngredients=sIngredients+data;
  $("#contribution_list").html(sIngredients);
    }).fail(function() {
    alert('error please login again.'); // or whatever
});
}

function Delete_Ingredient(d)
{
sURL="/admin/ajax/add_recipe_item.php?d="+d
//alert(sURL);
 $.get(sURL, function(data, status){
  $("#ingredient"+d).hide()
  })

}

function Delete_Contribution(d)
{
sURL="/admin/ajax/add_contribution_item.php?d="+d
//alert(sURL);
 $.get(sURL, function(data, status){
  alert(data);
  $("#portion_size"+d).hide()
  })

}
//alert (response);
//send off ingredients to add_Recipe_item.php
</script>
<?php } 
if ($sScriptName=="ADD_FOOD.PHP"){?>

<script>
function Add_Vitamin() {
//send food id + qty to form
//alert($("#ingredients_list").html());
if ($("#ingredients_list").html()=="Add from list above")
  { 
    $("#ingredients_list").html("")
  }
sURL="/admin/ajax/add_food_item.php?idf="+$("#idf").val()+"&percentage="+$("#percentage").val()+"&idp=<?=$_SESSION["id_people"]?>"+"&vitamin="+$("#vitamin").val()
//alert(sURL);
 $.get(sURL, function(data, status){
  sIngredients=$("#ingredients_list").html();
  sIngredients=sIngredients+data;
  //alert(data);
  $("#ingredients_list").html(sIngredients);
    }).fail(function() {
    alert('error please login again.'); // or whatever
});
}
function Delete_food_vit(d)
{
sURL="/admin/ajax/add_food_item.php?d="+d
//alert(sURL);
 $.get(sURL, function(data, status){
  $("#vitamin"+d).hide()
  })

}
//alert (response);
//send off ingredients to add_Recipe_item.asp
</script>
<?php } 
if ($sScriptName=="CONTACT.PHP"){?>
<style>#map-canvas {width: 800px;height: 600px;}</style>
<script src="/files/google_map_api.js"></script>
<script>
function initialize() {

 var location = new google.maps.LatLng(-43.5070854,172.72844);

  var mapOptions = {
    center: location,
    zoom: 17,
    mapTypeId: google.maps.MapTypeId.HYBRID
  };

  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

  var marker = new google.maps.Marker({
    position: location,
    map: map,
    title: 'Antidote!'
  });
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php }
if ($sScriptName=="BLOG.PHP"){?>
<!-- YOUTUBE POPUP Javascript file -->
<script src="/files/js/jquery.youtubepopup.min.js"></script> <script>
        $(function () {
            $("a.youtube").YouTubePopup({ autoplay: 0 });
        });
</script>
<?php 
}

if ($sScriptName=="RECIPE.PHP"){
if (isset($_SESSION["id_people"])){$person=$_SESSION["id_people"];}else{$person=0;}
  ?>
<script>
// This is the first thing we add ------------------------------------------
$(document).ready(function() {
    $('.ratings_stars').hover(
        // Handles the mouseover
        function() {
            $(this).prevAll().addBack().addClass('ratings_over');
            $(this).nextAll().removeClass('ratings_vote'); 
        },
        // Handles the mouseout
        function() {
            $(this).prevAll().addBack().removeClass('ratings_over');
            // can't use 'this' because it wont contain the updated data
        }
    ); 
    // This actually records the vote
    $('.ratings_stars').bind('click', function() {
        var review_text=window.prompt("What did you enjoy about this?","");
        var star = this;
        var widget = $(this).parent();
        
        var clicked_data = {
            clicked_on : $(star).attr('class'),
            widget_id : $(star).parent().attr('id')
        };
        var stars=$(star).attr('class').substr(5,1);
        $.post(
            '/admin/ajax/ratings.php?s='+stars+'&r=<?=$_GET["r"]?>&p=<?=$person?>&t='+review_text, function (data) {
            if (data=='Login required for this activity.')
              {document.location.href='/login.asp';}
            else 
              {//must be useful data for review writeback
              $('#previous_ratings').html(data+$('#previous_ratings').html())
              }
            $(star).prevAll().addBack().addClass('ratings_over');
            $(this).nextAll().removeClass('ratings_vote');  
            
            }
        ); 
    });
    
    
    
});
// END FIRST THING
</script>
<?php } 
if ($sScriptName=="FOODS.PHP"){?>
<script>
function getpage(sURL)
{//alert(sURL);
   $.get(sURL, function(data, status){
    //alert("meal "+mealid+" served");
    //alert(data);
    //return data;
    $("#DvAllFoods").html(data);
    })
}
</script>
<?php } ?>


<?php
//x=rwe(sScriptName)
$sTitle=$_SESSION["restaurant_name"]." ";
$sScriptNameFull=$_SERVER['REQUEST_URI'];
//echo "<br><br><br>".$sScriptNameFull;
$conn=open_conn();
$sSQL="call get_pageTitle ('".$sScriptNameFull."');";
//echo "<br><br><br>".$sSQL;
$result = $conn->query($sSQL) or die($conn->error);
while($row = $result->fetch_assoc()) 
if (mysqli_num_rows($result)>0)
{
  $sTitle=$sTitle.$row["title"];
} 

//check Title page name from pages table  
$imageurl="/images/socrates_change.jpg";
$sDesc="Software to change the world. Our goal is to educate people on the benefits, both mental and physical, both for the inner and the outer, for self and others.";
if ($sScriptName=="RECIPE.PHP")
{
  if (is_numeric($_GET["r"]))
  {
    $sSQL="Select name,uid_recipe,brief from recipes where id_recipe=".$_GET["r"].";";
    $conn=open_conn();
    $result = $conn->query($sSQL) or die($conn->error);
    if (mysqli_num_rows($result)==0)
    {
      echo "invalid recipie ID";exit;
    } 
    while($row = $result->fetch_assoc())  
    {
      $sTitle=$sTitle.$row["name"];
      $imageid=$row["uid_recipe"];
      $uid_recipe=$row["uid_recipe"];
      $sDesc="";
      $sDesc=str_replace("'","",$row["brief"]);
      $imageurl="/images/recipe/large/".$imageid.".jpg";
    }
  }
} 

if ($sScriptName=="FOOD.PHP")
{
  if (is_numeric($_GET["f"]))
  {
    $sSQL="Select name,uid_food,intro from antidote.food where id_food=".$_GET["f"].";";
    $conn=open_conn();
    $result = $conn->query($sSQL) or die($conn->error);
    if (mysqli_num_rows($result)==0)
    {
      echo "Invalid Food.";exit;
    } 
    while($row = $result->fetch_assoc())  
    {
      $sTitle=$sTitle.$row["name"];
      $imageid=$row["uid_food"];
      $uid_recipe=$row["uid_food"];
      $sDesc="";
      $sDesc=str_replace("'","",$row["intro"]);
      $imageurl="/images/recipe/large/".$imageid.".jpg";
    }
  }
} 

$sSQl="";


?>
<title><?=$sTitle;?></title>
<meta name="description" content="<?=$sDesc?>" />
<link rel="canonical" href="http://www.antidote.org.nz"/>
<meta name="keywords" content="POS cafe software, veganism, plant based, nutrition"/>
<meta property="og:image" content="http://<?=$imageurl;?>" />
<meta property="og:title" content="<?=$sTitle;?>" />
<meta property="og:site_name" content="Antidote Food System" />
<meta property="og:description" content="<?=$sDesc;?>" />
<meta property="og:url" content="/<?=strtolower($sScriptNameFull);?>" />
<meta property="og:type" content="website" />
<meta property="fb:app_id" content="1575727666027370" />


<?php if ($sScriptName=="LOVEYOURFOOD.PHP")
{echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>'; }
?>

<script src="/files/js/main.js"></script>

</head>

<body>
<div class="container-fluid" style="max-width:1280px;margin-left: auto;margin-right: auto;">
<div class="info">Info message</div>
<div class="success">Successful operation message</div>
<div class="warning">Warning message</div>
<div class="error">Error message</div>
<div class="background">
<nav class="navbar navbar-expand-lg navbar-light bg-white">
  <a class="navbar-brand" href="#">
    <img src="<?=$_SESSION["logo"]?>" style="width:200px" alt="logo">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="nav navbar-nav navbar-right">
       <li class="nav-item">
        <a class="nav-link" href="/index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/recipes.php">Recipes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/foods.php">Foods</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle navbarDropdown" href="/courses.php" role="button" data-toggle="dropdown">
         Courses
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="/courses.php">Christchurch</a>
          <a class="dropdown-item disabled" href="#">Online</a>
        </div>
      </li>
     <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle navbarDropdown" href="/projects.php"  role="button" data-toggle="dropdown">
        Projects
        </a>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="/projects/">Projects</a>          
          <a class="dropdown-item" href="/projects/wisdom/crowd.htm">Wisdom of the Crowd</a>
          <a class="dropdown-item" href="/foods.php">Food Supply System</a>
          <a class="dropdown-item" href="/projects/directdemocracy/now.php">Direct Democracy Governance System.</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/contact.php">Contact</a>
      </li>
   <?php
       echo $login;
       ?>
      <li class="nav-item">
       <a class="nav-link" href="https://www.facebook.com/brightons.antidote"><img style="text-align: left" alt="instragram" src="/images/insta-icox24.png"></a>
      </li>
      <li class="nav-item">
         <a class="nav-link" href="https://www.instagram.com/suterdaniel/"><img style="text-align: left" alt="fb" src="/images/facebook-icox24.png"></a>
      </li>

    </ul>
  </div>
</nav>
</div>

