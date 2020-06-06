<?php 

if(!isset($_SESSION)) { session_start();} 
if (isset($_GET["redirect"])){if ($_GET["redirect"]!="") 
    {
        $_SESSION["redirect_to"]=$_GET["redirect"];}
    }
$bError=false;
//print_r($_SESSION);

$path = $_SERVER['DOCUMENT_ROOT'];
$path .= '/admin/functions.php';
include_once($path);

require_once('google/settings.php');
//require_once __DIR__ . '/vendor/autoload.php';  




$conn2=open_conn(); 
if (isset($_GET["a"])) {
if ($_GET["a"]!="") {
        //autoLogin by person to serve another person.
        //x=rwe("here")
    echo "CALL `People_login_by_Auto`('".$_GET["a"]."');";
    $sql= "CALL `People_login_by_Auto`('".$_GET["a"]."');";
    $result = $conn2->query($sql) or die($conn2->error);       
    while($row = $result->fetch_assoc()) {
        $_SESSION["id_people"]=$row["id_people"];
        GetCurrentMeal($_SESSION["id_people"]);
        $_SESSION["email"]=$row["email"];
        $_SESSION["password"]=$row["password"];
        $_SESSION["name"]=$row["name"];
        if (is_null($row["image_path"]))
            {$_SESSION["image_path"]="/images/people/anon.jpg";
            $_SESSION["image_path_icon"]="/images/people/anon-45/jpg";
            }
        else
            {$_SESSION["image_path"]=$row["image_path"];
            $_SESSION["image_path_icon"]=str_replace("med","xsthumb",$row["image_path"]);
            }
        $_SESSION["uid_people"]=$row["uid_people"];
        $_SESSION["about_me"]=$row["about_me"];
        $_SESSION["can_authorize"]=$row["can_authorize"];
        //echo $_SESSION["redirect_to"];
        if ($_SESSION["redirect_to"]!="") {
            header("Location: ".$_SESSION["redirect_to"]);
            exit;
        } else {
            header("Location:/index.php");
            exit;
        }           
    }
}
}  



// Set session variables

$sLoginAttempt="";
if (isset($_GET["Login_fb"])) {
if ($_POST["Login_fb"]!="") {
        //dim $user;
        //$user = $new $fb_user;
        //$user.$token = $cookie["token"];
        //$user.$LoadMe;
        echo "Token: ".$cookie["token"];
        echo "Expires: ".$cookie["expires"];
        echo "URL: ".$user.$graph_url;
        echo "";
        echo "Json: ";
        echo $user.$json_string;
        echo "";
        echo "-----------------------------------------";
        echo "RESULTS";
        echo "-----------------------------------------";
        
        echo "First Name: ".$user.$first_name;
        echo "Last Name: ".$user.$last_name;
        echo "Email: ".$user.$email;
        echo "Picture: ".$user.$m_id;
        //DrawPicture "https://graph.facebook.com/".$user.$m_id."/picture??width=9999";
    }
}
$sErr=""; 
 
if (isset($_POST["username"])) {
 //x=rwb("Attempting login.")
$sSQL= "CALL `People_login`('".$_POST["username"]."','".$_POST["password"]."');"; 
//echo "<br><br><br>".$sSQL;
$result = $conn2->query($sSQL) or die($conn2->error);       
while($row = $result->fetch_assoc()) {
        $_SESSION["id_people"]=$row["id_people"];
        GetCurrentMeal($_SESSION["id_people"]);
        $_SESSION["email"]=$row["email"];
        $_SESSION["password"]=$row["password"];
        $_SESSION["name"]=$row["name"];
        $_SESSION["uid_people"]=$row["uid_people"];
        $_SESSION["about_me"]=$row["about_me"];
        $_SESSION["can_authorize"]=$row["can_authorize"];
        if (is_null($row["image_path"]))
            {$_SESSION["image_path"]="/images/people/anon.jpg";
            $_SESSION["image_path_icon"]="/images/people/anon-45/jpg";
            }
        else
            {$_SESSION["image_path"]=$row["image_path"];
            $_SESSION["image_path_icon"]=str_replace("med","xsthumb",$row["image_path"]);
            }
        //echo $_SESSION["email"];
        if (isset($_SESSION["redirect_to"])) {
                header("Location: ".$_SESSION["redirect_to"]);
        } 
        else
        {
            header("Location:/index.php");
        }                   
       //else if{$bError=true;}
    }
}


//x=rwe("here")