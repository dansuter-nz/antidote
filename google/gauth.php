<?php
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'/admin/functions.php');
require_once('settings.php');
require_once('google-login-api.php');

include "$_SERVER[DOCUMENT_ROOT]/files/ImageResize.php";

// Google passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
	try {
		$gapi = new GoogleLoginApi();
		// Get the access token 
		$data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);
		// Get user information
		$user_info = $gapi->GetUserProfileInfo($data['access_token']);
		//pop values into database or updated
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}

$foldername="people";

$targetDir=$_SERVER['DOCUMENT_ROOT']."/images/people/";
//$fileName="Black beans.jpg";
$sql = "SELECT id_people, name,uid_people FROM people where email='".$user_info['email']."';";
//echo "<br>"."<br>"."<br>".$sql;
$conn=open_conn();
$result = $conn->query($sql);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {	
    $id_people=$row["id_people"];
	$sql = "UPDATE people SET image_path = '/images/people/med/".$row["uid_people"].".jpg',oauth_uid='".$user_info['id']."' WHERE id_people ='".$id_people."';";
	if ($conn->query($sql) === TRUE) {
         echo "Record updated successfully <br>".$sql;
		} else {
		echo "Error updating record: " . $conn->error;
		}
	  }
    }
else {
    $sql = "CALL People_Add_google ('".$user_info['name']."','".$user_info['email']."','".$user_info['id']."',1,'');";
    echo "<br>".$sql;
    //can be an issue where the person is already added with gmail account, in which case make and 
	if ($conn->query($sql) === TRUE) {
		$sql = "Select password from people where email='".$user_info['email']."';";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
   		 	// output data of each row
   		 	while($row = $result->fetch_assoc()) {$password=$row["password"];}
    	}
    	 echo "Record added successfully <br>";
    	 sendEmailRegister($user_info['name'],$user_info['email'],$password);
	} 
	else {
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
file_put_contents($filePath, file_get_contents($user_info['picture'])); 
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
echo $sql;
echo "<br>"."Location: /login_secure.php?a=".$autologin;
//exit;
//redirect to login page with autologin code
}
header("Location: /login_secure.php?a=".$autologin); 
exit;
?>
<head>
<style type="text/css">

#information-container {
	width: 400px;
	margin: 50px auto;
	padding: 20px;
	border: 1px solid #cccccc;
}

.information {
	margin: 0 0 30px 0;
}

.information label {
	display: inline-block;
	vertical-align: middle;
	width: 150px;
	font-weight: 700;
}

.information span {
	display: inline-block;
	vertical-align: middle;
}

.information img {
	display: inline-block;
	vertical-align: middle;
	width: 100px;
}

</style>
</head>

<body>

<div id="information-container">
	<div class="information">
		<label>Name</label><span><?= $user_info['name'] ?></span>
	</div>
	<div class="information">
		<label>ID</label><span><?= $user_info['id'] ?></span>
	</div>
	<div class="information">
		<label>Email</label><span><?= $user_info['email'] ?></span>
	</div>
	<div class="information">
		<label>Email Verified</label><span><?= $user_info['verified_email'] == true ? 'Yes' : 'No' ?></span>
	</div>
	<div class="information">
		<label>Picture</label><img src="<?= $user_info['picture'] ?>" />
	</div>
</div>

</body>
</html>