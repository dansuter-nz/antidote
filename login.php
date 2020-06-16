<?php 

include 'header.php';  
require_once('google/settings.php');
require_once('fb/settings.php');
?> 
<form action="/login_secure.php?<?=$_SERVER['QUERY_STRING']?>" method="post" name="frmLogin" id="frmLogin">
<?php
if (isset($bError)) {
?>
<div class="myInfoWarning">
<div class="errorIcon">&nbsp;</div>
Error Logging in please try again.
</div>
<?php }

?>



<!--
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v4.0&appId=2853168548105984&autoLogAppEvents=1"></script>
-->
<div id="main-content" style="margin-top: 60px;">
  <div class="row" style="width: 100%;margin-left: auto;margin-right: auto;">
   <div class="col-sm-12" >
<form action="/login.php" method="post" name="frmLogin" id="frmLogin">
<?php
$sLoginAttempt='';
if ($sLoginAttempt=='fail')
{
echo '<div class="myInfoWarning"><div class="errorIcon">&nbsp;</div>Error Logging in please try again.</div>';}
?>
<div class="login_form">
    <span class="signup_facebook login_button">
<?php

$fb = new Facebook\Facebook([
  'app_id' => FB_CLIENT_ID, // Replace {app-id} with your app id
  'app_secret' => FB_CLIENT_SECRET,
  'default_graph_version' => 'v6.0',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl(FB_CLIENT_REDIRECT_URL, $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '" style="color:#fff;">Log in with Facebook</a>';

?>
    </span>
</div>
<div class="login_form">
<a id="login-button" href="<?= 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online' ?>">
    <span class="signup_google login_button">
        
            Login with Google

    </span>
</a>
</div>
<div class="login_form">
    <h3>Or</h3>
</div>

<div class="login_form">
    <input name="username" type="text" size="30" value="<?php if (isset($_GET["e"])) {echo $_GET["e"];}?>" placeholder="Email">
</div>
<div class="login_form">
    <input name="password" type="password" value="" size="30" placeholder="Password">
</div>

<div class="login_form">
    <input name="login" class="login_button" type="submit" value="Login">   
</div>
</form>
   </div>
  </div>
</div>
<?php
$shtm="";
if (isset($_GET["er"]))
  {$shtm="<br><div class='alert alert-danger'>";
  if ($_GET["er"]=="1"){
    $sErr="That email address is already registered, click on forgot password link or login using email and password.";
  $shtm=$shtm.$sErr."</div>";
  echo $shtm;}
}?>
<?php include 'footer.php';?>




