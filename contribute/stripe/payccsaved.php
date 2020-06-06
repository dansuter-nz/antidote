<?php
if(!isset($_SESSION)) { session_start();} 
$conn=open_conn();
$sSQL="Select vendor_key from antidote.people_saved_cards p where id_person='".$_SESSION["id_people"]."' and p.vendor='stripe'";
$result = $conn->query($sSQL) or die($conn->error);

require_once 'shared.php';

function calculateOrderAmount($items) {
	// Replace this constant with a calculation of the order's amount
	// Calculate the order total on the server to prevent
	// people from directly manipulating the amount on the client
	return intval($_SESSION["total_price"]*100,0);
}

function generateResponse($intent) 
{
  switch($intent->status) {
    case "requires_action":
    case "requires_source_action":
      // Card requires authentication
      return [
        'requiresAction'=> true,
        'paymentIntentId'=> $intent->id,
        'clientSecret'=> $intent->client_secret
      ];
    case "requires_payment_method":
    case "requires_source":
      // Card was not properly authenticated, suggest a new payment method
      return [
        error => "Your card was denied, please provide a new payment method"
      ];
    case "succeeded":
      // Payment is complete, authentication not required
      // To cancel the payment after capture you will need to issue a Refund (https://stripe.com/docs/api/refunds)
      return ['clientSecret' => $intent->client_secret];
  }
}
//4242424242424242
try 
{
  if($body->paymentMethodId != null) 
  {
    $payment_method = \Stripe\PaymentMethod::retrieve($body->paymentMethodId);
    $payment_method->attach(['customer' => $_SESSION["stripe_customer_id"],]);
    // Create new PaymentIntent with a PaymentMethod ID from the client.

    try {
      $intent = \Stripe\PaymentIntent::create([
        "amount" => intval($_SESSION["total_price"]*100,0),
        "currency" => $body->$_SESSION["currency"],
        "payment_method" => $body->paymentMethodId,
        "customer" => $_SESSION["stripe_customer_id"],
        "confirmation_method" => "manual",
        "confirm" => true,
        'on_session' => true,
        // If a mobile client passes `useStripeSdk`, set `use_stripe_sdk=true`
        // to take advantage of new authentication features in mobile SDKs

      ]);
    }
  catch (\Stripe\Exception\CardException $e) 
  {
    // Error code will be authentication_required if authentication is needed
    echo 'Error code is:' . $e->getError()->code;
    $payment_intent_id = $e->getError()->payment_intent->id;
    $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);
  }

    // After create, if the PaymentIntent's status is succeeded, fulfill the order.
  } 
  else if ($body->paymentIntentId != null) 
  {
    // Confirm the PaymentIntent to finalize payment after handling a required action
    // on the client.
    $intent = \Stripe\PaymentIntent::retrieve($body->paymentIntentId);
    $intent->confirm();
    // After confirm, if the PaymentIntent's status is succeeded, fulfill the order.
  }  
  $output = generateResponse($intent);

  echo json_encode($output);
} 
catch (\Stripe\Error\Card $e) 
{
  echo json_encode([
    'error' => $e->getMessage()
  ]);
}





