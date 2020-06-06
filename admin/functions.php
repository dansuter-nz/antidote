<?php
function InStr($haystack, $needle, $offset = 0)
{
    $position = strpos($haystack, $needle, $offset);

    return ($position !== false) ? $position += 1 : 0;
}
if (!$_SERVER["HTTP_HOST"]=="localhost")
{
  if($_SERVER["HTTPS"] != "on")
  {
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
      exit();
  }
}
if(!isset($_SESSION)) { session_start();}

$path="/var/www/html/antidote_apache";
$path .= "/vendor/autoload.php";
include_once($path);


function getNutrients($iDays,$idPerson) {
$sSQL="Call Recipe_Vitamins_Avg_by_days (".$iDays.",".$idPerson.")";
echo $sSQL;
$conn=open_conn();
$result = $conn->query($sSQL) or die($conn->error);
$s='<table style=">';
 if ($result->num_rows==0) 
  {
  $s=$s."<tr><td colspan='3'>You'll need to eat some yummy food before anything can show here :-)</td></tr>";
  } 
  else 
  {
    if ($iDays==1000) 
    {
      $s=$s.'<tr style="height:40px;"><td style="white-space:nowrap;" colspan="3">All time nutrition stats. You joined '.$row["days"]." days ago and have dinned here on ".$row["days_with_meals"]." seperate days.  Below the graph shows you average nutrition per day you've been here.</td></tr>";
    } 
  $s=$s.'<tr style="height:40px;"><td style="white-space:nowrap;"><b>Vitamin / Mineral</b></td><td class="small-graph-name" align="center" ><b>(%)</b></td><td align="center"><b>Recommended Daily Intake</b></td></tr>';
  $iVitcount=0;
    
   while($row = $result->fetch_assoc())
   {
    $iVitcount=$iVitcount+1;
    if ($iVitcount>20) {break;}
    $width=cint($row["Net_RDI"]);
    if ($width>200) {$width=195;$width=$width / 2;}
    $s=$s.'<tr><td align="right"><a href="/vitamin.asp?v='.$row["id_vitamin"].'">'.$row["name"].' &nbsp;</a></td><td class="small-graph-name">('.round($row["net_RDI"],0).')</td><td style="width:100%"><a title="'.round($row["net_RDI"],0).'% of your Recommended Daily Intake" href="/vitamin.asp?v='.$row["id_vitamin"].'"><div class="small-graph-line"  style="width:'.$width."%;background-color:".$row["color"].'">&nbsp;</div></a></td></tr>';
    };
}
$s=$s.'<tr style="height:40px;"><td style="white-space:nowrap;" colspan="3"><hr></td></tr>';
$s=$s."</table>";
return $s;
}



function contrbution_add($typeid,$payid)
{
  $sSQL="INSERT INTO `contributions`";
  $sSQL=$sSQL."(`id_contribution_type`,";
  $sSQL=$sSQL."`id_helper`,";
  $sSQL=$sSQL."`id_people`,";
  $sSQL=$sSQL."`id_meal`,";
  $sSQL=$sSQL."`amount`,confirmation_id)";
  $sSQL=$sSQL." Values  ";
  $sSQL=$sSQL."(".$typeid.",";//stripe payment type is 5
  $sSQL=$sSQL."'".$_SESSION["id_helper"]."',";
  $sSQL=$sSQL."'".$_SESSION["id_people"]."',";
  $sSQL=$sSQL."'".$_SESSION["id_meal"]."',";
  $sSQL=$sSQL."'".$_SESSION["total_price"]."',";
  $sSQL=$sSQL."'".$payid."');";
  $conn=open_conn();
  $result = $conn->query($sSQL) or die($conn->error);
  //update meal has been conpleted
  $sSQL="Call Update_people_eat (".$_SESSION["id_meal"].")";
  $result = $conn->query($sSQL) or die($conn->error);
 
}
 
function getLastRecipesServed($id_meal) {
//display an icon of last recipes.
$bAdmin=0;
$sSQL = "Call Get_recipes_by_Meal (".$id_meal.");";

$conn2=open_conn();
$result = $conn->query($sSQL) or die($conn->error);
$irow=0;
$s="<table width='100%' style='margin-right:auto;margin-left:auto;border:0px'>";
$s=$s."<tr>";
$s=$s.'<td colspan="3">';
$s=$s.'<h1 style="">What you had at Antidote today :-)</h1>';
$s=$s."</td>";
$s=$s."</tr>";
while($row = $result->fetch_assoc())
{

  $sFoodType="";
  if ($irow % 2==1) 
    {$strClass="light_blue_row";} 
  else 
    { $strClass="white_row";}
  $irow=$irow+1;
  $id_recipe=$row["id_recipe"];
  $id_person=$row["id_people"];
  $name=$row["recipe_name"];
  $image=$row["recipe_image"];
  $how_to_make=$row["how_to_make"];
  //id_type=rsTemp("id_type")
  $servings=$row["servings"];
  $uid_recipe=$row["uid_recipe"];
  $uid_recipe=$row["uid_people"];
  //person_name=rsTemp("person_name")
  //show_on_web=rsTemp("show_on_web")
  $s=$s."<tr>";
  $s=$s.'<td colspan="3">';
  $s=$s.'<h3 style=""><a href="http://www.antidote.org.nz/recipe.php?r='.$id_recipe.'">'.$name."</a></h3>";
  $s=$s."</td>";
  $s=$s."</tr>";
  $s=$s."<tr>";
  $s=$s."<td>";
  $s=$s.'<a href="http://www.antidote.org.nz/recipe.php?r='.$id_recipe.'"><img src="http://www.antidote.org.nz'.$row["recipe_image"].'" alt="'.$row["recipe_name"].'"></a>';
  $s=$s."</td>";
  $s=$s."<td>";        
  $s=$s.'<div id="ingredients_list" class="col-xs-12">';
  $s=$s.'<ul class="no-indent">';

  $sSQL = "call Recipes_By_ID (".$id_recipe.")";
  $result2 = $conn2->query($sSQL) or die($conn->error);
  $icount=0;
  if ($result->num_rows==0) 
    {$s=$s."No Ingredients";} 
  else 
  {
    while($row2 = $result2->fetch_assoc())
    {
      $icount=$icount+1;
      $s=$s.'<li style="white-space:nowrap">'.$row2["qty_grams"].' grams of <a href="http://www.antidote.org.nz/food.php?f='.$row2["id_food"].'">'.$row2["name"]."</a></li>";
      if ($icount==6) {break;}
    }
    $conn2 -> close();
    $s=$s."<li><b>Serves".$servings."</b></li>";
    $s=$s."</ul>";
    $s=$s."</div>";
    $s=$s."</td>";
  }
  $s=$s."<td>";
  $s=$s.'<table style="width:300px;">';
  $sSQL="";
  $sSQL="Call Get_recipe_cache (".$row["id_recipe"].");";
  $conn2=open_conn();
  $result2 = $conn2->query($sSQL) or die($conn->error);
  //x=rwe(sSQL)
  while($row2 = $result2->fetch_assoc())
  {
    $width=cint($row2["RDI"]);
    if ($width>100) {$width=100;}
    $s=$s.'<tr><td align="right" style=" height: 17px;"><a href="http://www.antidote.org.nz/vitamin.php?v='.$row2["id_vitamin"].'">'.$row2["name"].'</a></td><td style="width:100%"><a title="'.$row2["RDI"].'% of your Recommended Daily Intake" href="/vitamin.php?v='.$row2["id_vitamin"].'"><div class="border: 1px solid #000;"  style="width:'.$width."%;background-color:".$row2["color"].'">&nbsp;</div></a></td></tr>';
  }
  $conn2 -> close();
  $s=$s."</table>";
  $s=$s."</td>";
  $s=$s."</tr>";
}

$conn -> close();
$s=$s.'<tr><td colspan="3"><h2>Vitamin Breakdown for your meal (top 10)</h2></td></tr>';
$s=$s.'<tr><td colspan="3" >';
$s=$s.'<table style="border: 1px solid #000; width:100%;">';
$s=$s.'<tr style="height:40px;border-top:1px solid #000;border-bottom:1px solid #000;"><td style="white-space:nowrap;"><b>Vitamin / Mineral</b></td><td class="small-graph-name" align="center" ><b>(%)</b></td><td align="center"><b>Recommended Daily Intake</b></td></tr>';
$sSQL="CALL `antidote`.`Recipe_Vitamins_people_eat`(".$_SESSION["id_meal"].");";
$a=0;
$conn2=open_conn();
$result2 = $conn2->query($sSQL) or die($conn->error);
while($row2 = $result2->fetch_assoc())
  {
  $width=cint($row2["Net_RDI"]);
  $a=$a+1;
  if ($a>9) {break;}
  if ($width>200) {$width=195;}
  $colwidth=cint($width/2);
  $s=$s.'<tr><td align="right"><a href="/vitamin.php?v='.$row2["id_vitamin"].'">'.$row2["name"].' &nbsp;</a></td><td class="small-graph-name">('.number_format($row2["net_RDI"],0).')</td><td style="width:100%"><a title="'.number_format($row2["net_RDI"],0).'% of your Recommended Daily Intake" href="/vitamin.php?v='.$row2["id_vitamin"].'"><div class="small-graph-line"  style="width:'.$colwidth.'%;background-color:"'.$row2["color"].'">&nbsp;</div></a></td></tr>';
}
$s=$s."</table>";
$s=$s."</td>";
$s=$s."</tr>";
$s=$s."</table>";
$s=$s.'<div id="spacer" style="margin-top:20px;"></div>';
return $s;
}



function GetCurrentMeal($id_person)
{
$sSQL="Call get_people_eat_by_id(".$id_person.")";
//echo $sSQL;
$conn=open_conn();
$result = $conn->query($sSQL) or die($conn->error);
$itotal=0;
$iMeal=0;
$portion_name="";
$id_person=0;
$recipe_name="";
$amount_currency=0;
$image="";
$irow=0;
$s="<table width='100%' style='margin-right:auto;margin-left:auto;border:0px'>";
while($row = $result->fetch_assoc())
{
  $iMeal=$row["id_meal"];
  if ($irow==0){$a="<table><tr><td><a href='/loveyourfood.php'>Meal ID ".$iMeal."</a></td></tr>";}
  $portion_name=$row["portion_name"];
  $id_person=$row["id_people"];
  $recipe_name=$row["recipe_name"];
  $amount_currency=$row["amount_currency"];
  $image=str_replace("med","small",$row["image"]);
  $itotal=$itotal+$amount_currency;
  $_SESSION["amount"]=$itotal;
  setlocale(LC_MONETARY, 'en_NZ');
  $irow=$irow+1;
  if ($irow<5)
  {
  $a=$a."<tr>";
  $a=$a."<td>1 x ".$portion_name." ".$recipe_name."</td>";
  $a=$a."<tr>";
  }
  if ($irow==5){$a=$a."<tr><td>.........</td></tr>";}
  $s=$s."<tr>";
    $s=$s." <td style='margin-top:30px;margin-left:15px;margin-right:15px;'>";
  $s=$s." <a class='button danger  remove' title='Remove this' onclick='Delete_Recipe(".$row["id_people_meals_recipes"]."); return false;'> x </a>
      </td> 
        <td>
        <img id='img_my_meal' src='".$image."' alt='".$recipe_name."'/>      
        <td>
        <h3>1 x ".$portion_name." ".$recipe_name."</h3>
      </td>
        <td class='text-right'>
        <span class='line_price'>".money_format('%i',$amount_currency)."</span>
      </td>
      </tr>";
}

if ($s=="") { 
    $s=$s."You currently have no recipes selected. Click <a href='/recipes.php'>here</a> to view recipe options.";  
    $s=$s." ";
}
$s=$s."<tr id='delivery_row' style='display:none;height:50px;vertical-align: bottom;'>  
      <td colspan='3' style='font-weight:bold;'>Delivery cost</td>     
        <td class='text-right' style=''>
        <span style='padding-top:20px;' id='delivery_price_show'>".number_format((float)0,2)."</span>
      </td>
    </tr>";
$s=$s."<tr>  
      <td colspan='3' style='font-weight:bold;height:50px;vertical-align: bottom'>Total</td>      
        <td class='text-right'>
        <span class='' id='total_price' style='font-weight:bold;text-decoration:underline;'>".number_format((float)$itotal,2)."</span>
      </td>
    </tr>";
$s=$s."</table>";
$a=$a."</table>";
$_SESSION["total_price"]=number_format((float)$itotal,2);
$_SESSION["mymeal_summary"]=$a;
//echo $a;
//exit;
return $s;
}


function sendEmailRegister($name,$email,$password)
 {
$sub="Thanks for registering with ".$_SESSION["restaurant_name"];

$message="Dear ".$name.",\r\n\r\n";
$message=$message."Thanks for joining ".$_SESSION["restaurant_name"]."\r\n\r\n";
$message=$message."Your user name is: ".$email." \r\n\r\n";
$message=$message."Your password is: ".$password." \r\n\r\n";
$message=$message."You can use these details now to directly log in to our website anytime.\r\n\r\n";
$message=$message."http://www.".$_SESSION["url"]."/login.php\r\n\r\n";
$message=$message."This site was built using Antidote open source software.\r\n\r\n";
$message=$message."If you have any feedback about the software please feel free to contact me.\r\n\r\n";
$message=$message.".\r\n\r\n";
$message=$message."Daniel Suter dan@antidote.org.nz\r\n";
$message=$message.$name."\r\n";
$message=$message."Email: ".$_SESSION["site_email"]."\r\n";
$message=$message."Phone:+".$_SESSION["phone"];
sendEmail($sub,$message,$email,'dan@antidote.org.nz');
 }

function replace($seacrh,$find,$replace){
return str_replace($find,$replace,$seacrh);
}
function make_recipes()
{
  $conn=open_conn();
  $bAdmin=0;
  $iCat=0;
  $htm="";
  $_SESSION["s"]="";
  if (!isset($_SESSION['id_people']))
    {$_SESSION['id_people']="";}
  if (!isset($_SESSION['can_authorize']))
    {$_SESSION['can_authorize']="";}

  $sSearch="";
  $bvit=false;
  if (isset($_SESSION["can_authorize"])) 
  {
    if ($_SESSION["can_authorize"]) {$bAdmin=1;}
  }

  if ($_SESSION["id_people"]=="") { 
    $id_person=0;
  } else 
  { $id_person=$_SESSION["id_people"];
  }
  $iCat=="";
  if (isset($_GET["c"])) $iCat==$_GET["c"];
  if ($iCat=="") {$iCat=0;}
  if (isset($_GET["r"]))
    {
      if ($_GET["r"]=="1") 
        {
          $_SESSION["s"]="";
        }
    }
  if (isset($_GET["s"])) {
    $_SESSION["s"]=$_GET["s"];
  }
  $vid=0;
  $sSQL="SELECT id_vitamin,name FROM vitamins;";
  $result = $conn->query($sSQL) or die($conn->error);
  while($row = $result->fetch_assoc())  
  {
    if (strtoupper($_SESSION["s"])==strtoupper($row["name"]))
    {
     $bvit=true;
     $vid=$row["id_vitamin"];
     //echo $row["name"]." found";
     //exit;
    }
  }
  if($bvit)
    {
     $sSQL = "call Get_recipes_filter_by_vit (".$bAdmin.",".$id_person.",".$vid.",0)";
    }
    else
    {
      $sSQL = "call Get_recipes_filter (".$bAdmin.",".$id_person.",'".$_SESSION["s"]."',0)";
    }

  //$sSQL = "Call Get_recipes_filter (".$bAdmin.",".$id_person.",'".$sSearch."',0);";
  //echo $sSQL;
  $conn=open_conn();
  $irow=0;
  $iFoodCount=0;
  $sFoodType="";
  if (!isset($_SESSION["view"])){$_SESSION["view"]="gallery";}
  if (isset($_GET["v"]))
  {
    if ($_GET["v"]=="g") {$_SESSION["view"]="gallery";}
    if ($_GET["v"]=="d") {$_SESSION["view"]="list";}
  }
  if ($_SESSION["view"]=="") {$_SESSION["view"]="gallery";}
  if ($_SESSION["view"]=="gallery") 
    {
    //check to see if id is stated?
    //************************************************
    //update added grouping to the SQL , also added a column to determine sorting order.
    //************************************************
    $bAdmin=0;
    if ($_SESSION["can_authorize"]) { 
        $bAdmin=1;
    }
    
    //x=rwb(sSQL)
    $icol=0;
    $sFoodType="";
    $bNewRow=true;
    $result = $conn->query($sSQL) or die($conn->error);
    while($row = $result->fetch_assoc())  
      {
        if ($icol % 2==1) {
                $strClass="light_blue_row"; 
            } else { 
                $strClass="white_row";
            }    
            
        $icol=$icol+1;
        $iFoodCount=$iFoodCount+1;
        $id_recipe    = $row["id_recipe"];
        $id_person    = $row["id_person"];
        $name         = $row["recipe_name"];
        $id_group_name= $row["group_name"];
        $image        = str_replace("med","med",$row["image"]);
        $how_to_make  = $row["how_to_make"];
        $id_type      = $row["id_type"];
        $servings     = $row["servings"];
        $uid_recipe   = $row["uid_recipe"];

        if ($sFoodType!=$id_group_name) {
          if ($iFoodCount>1){$htm=$htm."</div>";}
            $htm=$htm."<div class='row'><div class='col-12'><div class='group_name'><h3><a href='/index.php?filter=".$id_group_name."'>".$id_group_name."s</a></h3></div></div></div>";
                $sFoodType = $id_group_name;
                $bNewRow   = true;

                $icol      = 1;
          }
            if ($bNewRow) {
                $htm=$htm."
                <div class='row'>
                ";
                $bNewRow=false;
            }
            $htm=$htm."<div class='col-'>
                        <div class='float_recipes float-left'>
                            <a href='/recipe.php?r=".$id_recipe."'>
                                <img src='".$image."' alt='".replace($name,"'","")."' class='Image_recipes'>
                            </a>
                            <div class='Text_recipes'>           
                                <a href='/recipe.php?r=".$id_recipe."'>".$name."</a>                
                            </div>
                        </div>
                       </div>";
              $htm=$htm. "";
      }
    }
  if ($_SESSION["view"]=="list") 
    { 
     $result = $conn->query($sSQL) or die($conn->error);
     //echo $sSQL;
     while($row = $result->fetch_assoc())  
      {
        if ($irow % 2==1) 
          {$strClass="light_blue_row";} 
        else 
          {$strClass="white_row";}
        $irow=$irow+1;
        $id_recipe=$row["id_recipe"];
        $id_person=$row["id_person"];
        $name=$row["recipe_name"];
        $image=$row["image"];
        $how_to_make=$row["how_to_make"];
        $id_type=$row["id_type"];
        $servings=$row["servings"];
        $uid_recipe=$row["uid_recipe"];
        $uid_recipe=$row["uid_people"];
        $person_name=$row["person_name"];
        $show_on_web=$row["show_on_web"];
        if ($sFoodType!=$row["group_name"])
        {
        $sFoodType=$row["group_name"];
        $htm=$htm."<div class='row'><div class='col-'>";
        $htm=$htm."<h2 style=''>".$row["group_name"]."</h2>";
        $htm=$htm."</div>";
        $htm=$htm."</div>";
        }
        $htm=$htm."<div class='row'><div class='col-'>";
        $htm=$htm."<h3 style=''><a href='/recipe.php?r=".$id_recipe."'>".$name."</a></h3>";
        $htm=$htm."</div>";
        
        if ($_SESSION["can_authorize"] || $_SESSION["id_people"]==$id_person)
            {
            $htm=$htm."<div class='col-'>";
            $htm=$htm." &nbsp;<a class='button icon edit' href='/add_recipe.php?id=".$id_recipe."'>Edit</a><br><br>";
             $htm=$htm."</div>";
            }
       
        $htm=$htm."</div>";
        $htm=$htm." <div class='row' id='htm".$id_recipe."' style='height:auto;overflow:hidden;'>";
        $htm=$htm."<div class='col-' style='min-width: 300px;'>";
        $htm=$htm."<a href='/recipe.php?r=".$id_recipe."'>";
        $htm=$htm." <div class='col-'>
                      <div class='float_recipes float-left'>
                          <a href='/recipe.php?r=".$id_recipe."'>
                            <div class='img_overlay_box'>
                              <img src='".$row["image"]."' alt='".replace($name,"'","")." class='image_overlay' style='width:100%; '>
                              <div class='image_overlay_middle'>
                                <div class='image'> <img id='recipes-person-img' class='image_overlay' style='width:100%;' title='By ".$row['person_name']."' src='/images/people/thumb/".$row['uid_people'].".jpg' alt='".$row['person_name']."'> ></div>
                              </div>
                            </div>
                          </a>
                          <div class='Text_recipes'>           
                              <a href='/recipe.php?r=".$id_recipe."'>".$name."</a>
                          </div>
                      </div>
                    </div>";
        $htm=$htm."</div>"; 
        $htm=$htm."<div class='col-' style='min-width: 300px;'>";
        $htm=$htm."<div class='row'>";
        $htm=$htm."<div id='ingredients_list' class='col-xs-12'>";
        $htm=$htm."<ul class='no-indent'>";

        $sSQL='call Recipes_By_ID ('.$id_recipe.')';
        $conn2=open_conn();
        $result2 = $conn2->query($sSQL) or die($conn2->error);
        if (mysqli_num_rows($result2)==0)
          {$htm=$htm."No Ingredients";}
        else
          {$icount=0;}
        while($row2 = $result2->fetch_assoc())  
            {
              $icount=$icount+1;
              $htm=$htm."<li style='white-space:nowrap'>".$row2['qty_grams']." grams of <a href='/food.php?f=".$row2['id_food']."'>".$row2['name']."</a></li>";
              if ($icount==6)
                {break;}
            }    
        $htm=$htm."<li><b>Serves ".$servings."</b></li>";
        $htm=$htm."</ul>";
        $htm=$htm."<div class='row'>";
        $htm=$htm."<div class='col-md-5 col-sm-5 col-xs-12'>";
        $htm=$htm."<button type='button' class='btn btn-default btn-sm'  onclick='Add_favourite(".$row['id_recipe'].")'>";
        $icon='glyphicon-star-empty';
        if (isset($row["id_people_favourite"]))
          {if ($row["id_people_favourite"]) {$icon='glyphicon-star';}}
        $htm=$htm."<span id='favourite".$row['id_recipe']."' class='glyphicon ".$icon."' aria-hidden='true'></span> Favourite";
        $htm=$htm." <span class='result'></span>";
        $htm=$htm."</button>";
        $htm=$htm." </div>";
        $htm=$htm."<div class='col-md-5 col-sm-5 col-xs-12'>";
        if ($_SESSION['can_authorize'] || $_SESSION['id_people']==$id_person)
          {
          $htm=$htm." <button type='button' class='btn btn-default btn-sm'  onclick='Edit_Recipe_Vis(".$row['id_recipe'].")'>";
          $icon='glyphicon-star-empty';
          if ($row["show_on_web"]) {$icon='glyphicon-star';}
          }

        $htm=$htm."<span id='show".$row['id_recipe']."' class='glyphicon ".$icon."' aria-hidden='true'></span> Available";
        $htm=$htm."<span class='result'></span>";
        $htm=$htm."</button>";
        $htm=$htm."</div>";
        $htm=$htm."</div>";
        $htm=$htm."</div>";
        $htm=$htm."</div>";
        $htm=$htm."</div> ";
        $htm=$htm." <div class='col-' style='min-width: 300px;'>";
        $htm=$htm." <div class='row'>";
        $htm=$htm."<div class='col-sm-12 col-xs-12'>";
        $htm=$htm."<table>";
        //Graph added 7/09/2015 Dan.
        $sSQL="Call Get_recipe_cache (".$row["id_recipe"].");";
        $conn2=open_conn();
        $result2 = $conn2->query($sSQL) or die($conn2->error);
        while($row2 = $result2->fetch_assoc())  
        {
          $width=intval($row2["rdi"]);
          //echo $width." ".$row2["id_vitamin"]."<br>";
          //exit;
          if ($width>100) {$width=100;}
          $htm=$htm."<tr><td align='right' class='small-graph-name'><a href='/vitamin.php?v=".$row2["id_vitamin"]."'>".$row2["name"]."</a></td>";
          $htm=$htm."<td style='width:100%'><a title='".$row2['rdi']." % of your Recommended Daily Intake' href='/vitamin.php?v=".$row2["id_vitamin"]."'>";
          $htm=$htm."<div class='small-graph-line'  style='width:".$width."%;background-color:".$row2["color"]."'>&nbsp;</div>";
          $htm=$htm."</a>";
          $htm=$htm."</td></tr>";
        }
        $htm=$htm."</table>";
        $htm=$htm." </div>";
        $htm=$htm." </div>";
        $htm=$htm." </div>";
        $htm=$htm."</div>";
    }
  }
  return $htm; 
}

function make_food_rows($sSearch)
{

$bvit=false;
$conn=open_conn();
$sSQL="SELECT name FROM vitamins;";
$result = $conn->query($sSQL) or die($conn->error);
while($row = $result->fetch_assoc())  
{
  if (strtoupper($sSearch)==strtoupper($row["name"]))
  {
   $bvit=true;
  }
}
if($bvit)
  {
   $sSQL = "call foods_most_popular_by_vit ('".$sSearch."')";
  }
  else
  {
    $sSQL = "call foods_most_popular ('".$sSearch."')";
  }

//echo $sSQL;
$result = $conn->query($sSQL) or die($conn->error);
$irow=0;
$s="";
while($row = $result->fetch_assoc())  
{
  $view="";
  $iMax=100;
  $id_food=$row["id_food"];
  if ($irow % 2==1) {$strClass="light_blue_row";} else {$strClass="white_row";}
  $irow=$irow+1;
  $strClass="";
  $image=replace($row["Image_path"],"small","med");
  if (isset($_GET["v"])){$view="gallery";}
  if ($view="gallery") 
  {
  if ($irow==1) {$s=$s."<div class='row ".$strClass."'>";}
  $s=$s."<div class='col-'>
          <div class='float_recipes float-left'>
            <a href='/food.php?f=".$row["id_food"]."'>
             <img height='217' src='".$image."' alt='".replace($row["name"],"'","")."' class='Image_recipes'>
            </a>
            <div class='Text_recipes'>           
              <a href='/food.php?f=".$id_food."'>".$row["name"]."</a>                
            </div>
          </div>
        </div>";
  }
  else
  {
    $s=$s."<div class='row ".$strClass."'>";
    $s=$s."<div class='col-md-4 col-sm-6'>";
    $s=$s."<div class='row'>";
    $s=$s."<div class='col-md-12 col-sm-12'>";
    $s=$s."<a href='/food.php?f=".$row["id_food"]."'><img src='".$image."'></a>";
    $s=$s."</div>";
    $s=$s."</div>";
    $s=$s."<div class='row'>";
    $s=$s."<div class='col-md-12 col-sm-12'>";
    $s=$s."<a href='/food.php?f=".$row["id_food"]."'>".$row["name"]."</a>";
    $s=$s."</div>";
    $s=$s."</div>"; 
    $s=$s."</div>";
    $s=$s."<div class='col-md-8 col-sm-6'>";
    $s=$s."<table style='width:100%'>";
    $sSQL = "call food_vitmains(".$id_food.");";
    $conn2=open_conn();
    $result2 = $conn2->query($sSQL) or die($conn2->error);
    $irow=0;
    $icount=0;
    while($row2 = $result2->fetch_assoc()) 
    {
        $icount=$icount+1;

        if ($icount<9) 
        { 
          $percent=cint($row2["percentRDI"]);
          if ($percent>100) {$percent=100;}
          $s=$s."<tr><td style='width:30%'><a href='/vitamin.php?v=".$row2["id_vitamin"]."'>".$row2["name"]."</br></a></td>";
          $s=$s." <td style='width:10%;text-align:right;'><b>".round($row2["percentRDI"],0)."</b></td>";
          $s=$s."<td style='width:60%;'><span class='graph' style='width:".$percent."%;float: left;border:1px solid;text-indent:0px;background-color:".$row2["color"].";'>&nbsp;</span></td>";
          $s=$s."</tr> " ; 
          
          $s=$s."</table>";
          $s=$s."</div></div>";  
        }
        $vitamins_count=$icount;
        $s=$s."<div class='row'>";
        $s=$s."<div class='col-md-12 col-sm-12'><hr>";
        $s=$s."</div>";
    }
  } 
}
if ($view="gallery") { $s=$s."</div>";}

return $s;
} 

function cint($numstring)
{return intval($numstring);}




function sendEmail($subject,$message,$to,$from)
 {

$transport = (new Swift_SmtpTransport('smtp.zoho.com', 587, 'tls'))
//$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
->setUsername('dan@antidote.org.nz')
->setPassword('1hdQrdLkgkap');
//       ->setUsername('hello@alchemyacademybali.com')
//       ->setPassword('M@r1p0sa');
//     ->setUsername('dan@antidote.org.nz')
//       ->setPassword('Lovelifenow');

$mailer = new Swift_Mailer($transport);
// Create a message
$message = (new Swift_Message($subject))
  ->setFrom($from)
//  ->setFrom('dan@antidote.org.nz')
  ->setTo($to)
  ->setBody($message)
  ;
// Send the message
$result = $mailer->send($message);
}

function domain_exists($email, $record = 'MX'){
	list($user, $domain) = explode ('@', $email);
	return checkdnsrr($domain, $record);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function open_conn(){
$servername = "localhost";
$username = "antidote_web";
$password = "Freeyourmind32!";
$dbname = "antidote";

$connection = new mysqli($servername, $username, $password, $dbname);
// Check connection
return $connection;
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);

} 
}

function close_conn($conn){
$conn->close(); }

function  makeReviewStars($sqlReviews,$iStarsMax) {

$conn=open_conn();
$irow=0;
$s='<div id="reviews_placeholder">';
$result3 = $conn->query($sqlReviews) or die($conn->error);
if (!$result3) {
  $s=$s."<h4>No reviews yet.  Be the first ot review this.</h4>";
} else {
  $s=$s."<h4>Tried it?  Add your review.</h4>";
}
$s=$s.'<div class="row " >';
$s=$s.'   <div class="col-sm-3">';
$s=$s."   <div class='recipe_choice'>";
$s=$s.'     <div id="r1" class="rate_widget">';
for ($i=1; $i<=$iStarsMax; $i++) {
  $s=$s.'     <div class="star_'.$i.' ratings_stars"></div>';
}
$s=$s."     </div>";
$s=$s."   </div>";
$s=$s."   </div>";
$s=$s."</div>";
$s=$s.'<div id="previous_ratings">';
while($row2 = $result3->fetch_assoc())  
  {
  //x=rwb("here")
    $reviewdate = new DateTime($row2['date_reviewed']);
    $person_image=str_replace("/med/","/thumb/",$row2["image_path"]);
    $s=$s.'<div class="row " >';
    $s=$s.'   <div class="col-sm-3">';
    $s=$s.'       <div class="recipe_choice">';
    $s=$s.'           <div id="r'.$row2["id_review"].'" class="rate_widget">';
    for ($i=1; $i<=$iStarsMax; $i++) {
        if ($i<=$row2["stars"]) {
            $s=$s.'       <div class="star_'.$i.' ratings_vote"></div>';
        } else {
            $s=$s.'       <div class="star_'.$i.' ratings_stars_empty"></div>';
        }
    }
    $s=$s."           </div>";
    $s=$s."       </div>";
    $s=$s."   </div>";
    $s=$s.'   <div class="col-sm-9">';
   //' $s=$s."<div style='display: table-cell; vertical-align: middle;min-height:45px;'>"
    $s=$s.'       <div class="row review_divider">';
    $s=$s.'           <div class="col-sm-2"><a href="/people.php"><img src="'.$person_image.'" alt="name"></a></div>';
    $s=$s.'           <div class="col-sm-3">'.date_format($reviewdate,"Y-M-d")."</div>";
    $s=$s.'           <div class="col-sm-7">'.$row2["review_text"]."</div>";
    //$s=$s."</div>"
    $s=$s."       </div>";
    $s=$s."   </div>";
    $s=$s."</div>";
    }
$s=$s."</div>";
$s=$s."</div>";
return $s;
} 

function right($s,$x) {return substr($s, -($x));}

function left($s,$x) {return substr($s,0,$x);}

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return random_color_part() . random_color_part() . random_color_part();
}
function CvbShortdateTime($my_date)
{
  return date_format($my_date,"Y-M-d");
}

function redirect($url){
    if (headers_sent()){
      die('<script type="text/javascript">window.location=\''.$url.'\';</script‌​>');
    }else{
      header('Location: ' . $url);
      die();
    }    
}
function DrawPicture( $str ) {
    echo "<img src=";
    echo $str;
    echo ">";
}
