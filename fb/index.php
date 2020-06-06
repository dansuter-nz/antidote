<!DOCTYPE html>
<html>
<head>
  <title>
    My Name 
  </title>
</head>

<body>
  <h1>Get My Name from Facebook</h1>

<?php
$path = $_SERVER['DOCUMENT_ROOT'];
   $path .= "/vendor/autoload.php";
   include_once($path);
//require_once __DIR__ . '/vendor/autoload.php';   

session_start();
$fb = new Facebook\Facebook([
  'app_id' => '1575727666027370', // Replace {app-id} with your app id
  'app_secret' => '439fd18b7706fb32ae392195827f11af',
  'default_graph_version' => 'v3.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://www.antidote.org.nz/fb/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';

?>

</body>
</html>