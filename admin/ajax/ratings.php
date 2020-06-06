<?php
session_start();
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
if (!$_SESSION["id_people"])
{
echo "login first".$_SESSION["id_people"];
 header("Location: /login.php"); 
}
$conn=open_conn();
//get URL and get Recipe food item values
if ($_GET["r"]=="") {
   echo "No rating end";
 
} else {
    //must be a rating for a recipe'
    $s="";
    $sSQL="";
    $sSQL="INSERT INTO `reviews`(`id_Recipe`,`id_person`,`stars`,`review_text`)";
    $sSQL=$sSQL." Select  ".$_GET["r"].",".$_GET["p"].",".$_GET["s"].",'".$_GET["t"]."';";
    $result = $conn->query($sSQL) or die($conn->error);

    //while($row = $result->fetch_assoc())  
    $sSQL="call get_review_last (".$_GET["r"].");";
    $result = $conn->query($sSQL) or die($conn->error);
    $iStarsMax=5;
    while($row = $result->fetch_assoc())  
    {
    $reviewdate = new DateTime($row['date_reviewed']);
    $person_image=str_replace("/med/","/thumb/",$row["image_path"]);
    $s=$s.'<div class="row ">';
    $s=$s.'   <div class="col-sm-3">';
    $s=$s.'       <div class="recipe_choice">';
    $s=$s.'           <div id="r'.$row["id_review"].'" class="rate_widget">';
    for ($i=1; $i<=$iStarsMax; $i++) {
        if ($i<=$row["stars"]) {
            $s=$s.'       <div class="star_'.$i.' ratings_vote"></div>';
        } else {
            $s=$s.'       <div class="star_'.$i.' ratings_stars_empty"></div>';
        }
    }
    $s=$s."           </div>";
    $s=$s."       </div>";
    $s=$s."   </div>";
    $s=$s.'   <div class="col-sm-9"  style="background-color: yellow;">';
   //' s=s&"<div style=""display: table-cell; vertical-align: middle;min-height:45px;"">"
    $s=$s.'       <div class="row review_divider">';
    $s=$s.'           <div class="col-sm-2"><img src="'.$person_image.'" alt="name"></div>';
    $s=$s.'           <div class="col-sm-3">'.date_format($reviewdate,"Y-M-d")."</div>";
    $s=$s.'           <div class="col-sm-7">'.$row["review_text"]."</div>";
    //s=s&"</div>"
    $s=$s."       </div>";
    $s=$s."   </div>";
    $s=$s."</div>";
    }
    echo $s;
    //x=rwb("inserted "&sSQL) 
}
?>
