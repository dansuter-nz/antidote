
<?php

//require_once __DIR__ . '/vendor/autoload.php';
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);  
require(PROJECT_ROOT.'/admin/functions.php');
require(PROJECT_ROOT.'/fb/settings.php');

include "$_SERVER[DOCUMENT_ROOT]/files/ImageResize.php";


$fb = new Facebook\Facebook([
  'app_id' => FB_CLIENT_ID, // Replace {app-id} with your app id
  'app_secret' => FB_CLIENT_SECRET,
  'default_graph_version' => 'v6.0',
  ]);

$helper = $fb->getRedirectLoginHelper();




try {
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
 //error_reporting(E_ALL);
  //ini_set("display_errors", 1);
  //var_dump($_GET);
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (! isset($accessToken)) 
{
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit;
}

// Logged in
echo '<h3>Access Token</h3>';
var_dump($accessToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId(FB_CLIENT_ID); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
    exit;
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string) $accessToken;

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->get('/me?fields=id,name,email,picture', $_SESSION['fb_access_token']);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$user_info = $response->getGraphUser();
echo '<br>ID: ' . $user_info['id'];
echo '<br>Name: ' . $user_info['name'];
echo '<br>email: ' . $user_info['email'];
$pic_url="http://graph.facebook.com/".$user_info['id']."/picture?width=276&height=414";
echo '<br>'.$pic_url;
//https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=10153173716953325&height=350&width=200&ext=1589231295&hash=AeR9yPj72GeusCKL

$foldername="people";
$targetDir=$_SERVER['DOCUMENT_ROOT']."/images/people/";
//$fileName="Black beans.jpg";
$sql = "SELECT id_people, name,uid_people FROM people where email='".$user_info['email']."';";
echo "<br>"."<br>"."<br>".$sql;
$conn=open_conn();
$result = $conn->query($sql);
if (mysqli_num_rows($result) > 0) 
  {
    // output data of each row
    while($row = $result->fetch_assoc()) 
    {  
      $id_people=$row["id_people"];
      $sql = "UPDATE people SET image_path = '/images/people/med/".$row["uid_people"].".jpg',oauth_uid='".$user_info['id']."' WHERE id_people ='".$id_people."';";
      if ($conn->query($sql) === TRUE) 
        {
           echo "<br>Record updated successfully <br>".$sql;
        } 
      else 
        {
          echo "<br>Error updating record: " . $conn->error;
        }
    }
  }
else 
  {
    $sql = "CALL People_Add_FB ('".$user_info['name']."','".$user_info['email']."','".$user_info['id']."',1,'');";
    echo "<br>".$sql;
    //can be an issue where the person is already added with gmail account, in which case make and 
    if ($conn->query($sql) === TRUE) 
    {
      $sql = "Select password from people where email='".$user_info['email']."';";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {$password=$row["password"];}
        }
         echo "Record added successfully <br>";
         sendEmailRegister($user_info['name'],$user_info['email'],$password);
    } 
    else 
    {
      echo "Error updating record: " . $conn->error;
    }
  }

$sql = "SELECT name,uid_people,auto_login FROM people where oauth_uid=".$user_info['id'].";";
echo $sql;
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) 
  {
  $pic=$user_info['picture'];
  $autologin =$row["auto_login"];
  $filePath=$_SERVER['DOCUMENT_ROOT']."/images/people/original/".$row["uid_people"].".jpg";
  file_put_contents($filePath, file_get_contents($pic_url)); 
  echo $filePath." saved<br>";
  $fileName=$row["uid_people"].".jpg";
  $mini_img=new \Gumlet\ImageResize($filePath);
            $mini_img->resize(600,800); 
            $mini_img->save($targetDir."xlarge/".$fileName);
  $mini_img=new \Gumlet\ImageResize($filePath);
            $mini_img->resize(375,450); 
            $mini_img->save($targetDir."large/".$fileName);
  $mini_img=new \Gumlet\ImageResize($filePath);
            $mini_img->resize(188,225); 
            $mini_img->save($targetDir."med/".$fileName);
  $mini_img=new \Gumlet\ImageResize($filePath);  
            $mini_img->resize(94,112);     
            $mini_img->save($targetDir."small/".$fileName);
  $mini_img=new \Gumlet\ImageResize($filePath); 
            $mini_img->resize(46,62);   
            $mini_img->save($targetDir."thumb/".$fileName);
  $mini_img=new \Gumlet\ImageResize($filePath);  
            $mini_img->resize(23,31);   
            $mini_img->save($targetDir."xsthumb/".$fileName);

  $sql = "UPDATE people SET image_path = '/images/people/med/".$row["uid_people"].".jpg' WHERE id_people ='".$id_people."';";
  $conn->query($sql);
  echo "<br><br>".$sql;
  echo "<br><br>Location: /login_secure.php?a=".$autologin;
  //exit;
  //redirect to login page with autologin code
  }
//$goto=substr($autologin,0,32);
header("Location: /login_secure.php?a=".$autologin); 
exit;

// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');
?>

