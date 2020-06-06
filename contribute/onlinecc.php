<?php
// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
if(!isset($_SESSION)) { session_start();} 
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
\Stripe\Stripe::setApiKey('sk_test_dZ2b6tUngIfsGBEfBtW3mdoB00ChXPEQh0');

//check to see if stripe_customer has been already created for person.
$conn=open_conn();
$sSQL="Select vendor_key from antidote.people_saved_cards p where id_person='".$_SESSION["id_people"]."' and p.vendor='stripe'";
$result = $conn->query($sSQL) or die($conn->error);
echo "<br> ROWS:".$result2->num_rows."<br>".$sSQL;
if ($result->num_rows==0)
{
	$customer = \Stripe\Customer::create();
	$s="INSERT INTO people_saved_cards (id_person,vendor,vendor_key) VALUES ('".$_SESSION["id_people"]."','stripe','".$customer->id."');";
	echo "<br>".$s;
	$result = $conn->query($s) or die($conn->error);
}
else
{
	while($row = $result2->fetch_assoc())
	{
		$customer = \Stripe\Customer::retrieve($row["vendor_key"]);
	}	
}


$intent = \Stripe\PaymentIntent::create([
 'amount' => $_SESSION["total_amount"],
'currency' => 'nzd',
'customer' => $customer->id])

?>