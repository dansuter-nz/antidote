<?php
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
\Stripe\Stripe::setApiKey('sk_test_dZ2b6tUngIfsGBEfBtW3mdoB00ChXPEQh0');
$intent = \Stripe\PaymentIntent::create([
  'amount' => number_format($_SESSION["total_price"],2)*100,
  'currency' => 'nzd',
  // Verify your integration in this guide by including this parameter
  'metadata' => ['integration_check' => 'accept_a_payment'],
]);
echo json_encode(array('client_secret' => $intent->client_secret));
?>