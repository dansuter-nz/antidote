<?php
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
if(!isset($_SESSION)) { session_start();} 
if(isset($_SERVER['SCRIPT_NAME'])) {$sScriptName=strtoupper(substr($_SERVER['SCRIPT_NAME'],1));}

\Stripe\Stripe::setApiKey($_SESSION["stripe_api_key"]);

\Stripe\PaymentIntent::create([
  'amount' => number_format($_SESSION["total_price"],2)*100,
  'currency' => 'nzd',
  'payment_method_types' => ['card'],
]);
?>