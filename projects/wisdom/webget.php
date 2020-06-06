<?php
//Get image from web
ini_set('max_execution_time', '3000');
ini_set('display_errors', 'on');
define('PROJECT_ROOT',$_SERVER["DOCUMENT_ROOT"]);
require(PROJECT_ROOT.'\admin\connection.php');
//require_once (PROJECT_ROOT.'\vendor\autoload.php');
$sql="SELECT id,streamURL FROM adds.stuff_articles where has_img=0 order by id desc limit 1;";
$result = $conn->query($sql) or die($conn->error);
//echo $sql;
$htm="";
$idupdated="";
while($row = $result->fetch_assoc()) 
{
	//website url
	$siteURL = $row["streamURL"];
	//call Google PageSpeed Insights API
	//switch between v4 and v5 depending on whihc one is performing better.
	//$googleAPIURL="https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$siteURL&screenshot=true&key=AIzaSyA6s1zmjY7F0JbOU1W2PIPPU9wQ8Dc6ywA";
	$googleAPIURL="https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url=$siteURL&screenshot=true&key=AIzaSyA6s1zmjY7F0JbOU1W2PIPPU9wQ8Dc6ywA";
	$googlePagespeedData = file_get_contents($googleAPIURL);
	echo "Getting ... ".$googleAPIURL."<br>";
	//echo $googlePagespeedData."<br>";
	//decode json data
	$json = json_decode($googlePagespeedData, true);
	//screenshot data
	//check to see if there was an error?
	$error="";
	if (isset($json['error']['code'])) {$error = $json['error']['code'];}
	if (strlen($error)>0){
		$message=$json['error']['code'];
			echo $error.":".$message.". getting ".$row["streamURL"]."<br>";	
		}
	else{
		//lighthouseResult?audits?final-screenshot?details?data

		//$screenshot = $json['lighthouseResult']['audits']['final-screenshot']['details']['data'];
		$screenshot = $json['screenshot']['data'];
		$screenshot = str_replace(array('_','-'),array('/','+'),$screenshot);
		$img=$screenshot;
		if (strlen($img)>0){
			//save image to the file system
			$path="images/webimgs/stuff/";
			if(!is_dir($path)){
			  mkdir($path);
			}
			$path=$path.$row["id"].".jpg";
			echo "saving image to: ".$path."<br>";
			$imgBase64 = str_replace('data:image/jpeg;base64,', '', $img);
			$imgBase64 = str_replace(' ', '+', $imgBase64);
			//echo "<br>".base64_decode($imgBase64)."<br>";
			file_put_contents($path, base64_decode($imgBase64));
		
			//display screenshot image
			echo "<img src=\"data:image/jpeg;base64,".$screenshot."\" /></br>";
			$idupdated=$idupdated.$row["id"].",";
		}
		else
			{echo 'error in json decode?,<br>'.$googlePagespeedData;}
	}
}
$idupdated=substr($idupdated,0,-1);
if (strlen($idupdated)>0){
	$sql="update adds.stuff_articles set has_img=1 where id in (".$idupdated.");";
	echo $sql;
	$result = $conn->query($sql) or die($conn->error);
}
