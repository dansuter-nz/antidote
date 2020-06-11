<?php
// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
if(!isset($_SESSION)) { session_start();} 
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
\Stripe\Stripe::setApiKey($_SESSION["stripe_api_key"]);
$customer = \Stripe\Customer::create();
//add customer id from stripe to the people_saved_cards table
$conn=open_conn();
$customer_id=$customer->id;
$sSQL="Select count(*) 'key_count' from antidote.people_saved_cards p where id_person='".$_SESSION["id_people"]."' and p.vendor_key='".$customer_id."' and p.vendor='stripe'";
$result2 = $conn->query($sSQL) or die($conn->error);
echo "<br> ROWS:".$result2->num_rows."<br>".$sSQL;
while($row = $result2->fetch_assoc())
{
	if ($row["key_count"]==0)
	{
		$conn2=open_conn();
		$s="INSERT INTO people_saved_cards (id_person,vendor,vendor_key) VALUES ('".$_SESSION["id_people"]."','stripe','".$customer->id."');";
		echo "<br>".$s;
		$result = $conn2->query($s) or die($conn->error);
	}
}


//$intent = \Stripe\PaymentIntent::create([
 // 'amount' => 1099,
  //'currency' => 'nzd',
  //'customer' => $customer->id,
//])

?>