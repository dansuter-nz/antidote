<?php
if(!isset($_SESSION)) { session_start();} 


require_once 'shared.php';
$conn=open_conn();
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


if ($body->del=="1")
{

$output = \Stripe\PaymentMethod::retrieve($body->cardId);
$output->detach();
echo json_encode($output);
//$card= \Stripe\Customer::deleteSource($_SESSION["stripe_customer_id"], $_GET["cardid"]);
exit;
}

if($body->paymentMethodId != null) 
  {
      try {
          $payment_method = \Stripe\PaymentMethod::retrieve($body->paymentMethodId);
          $payment_method->attach(['customer' => $_SESSION["stripe_customer_id"],]);
          // Create new PaymentIntent with a PaymentMethod ID from the client.
          $intent = \Stripe\PaymentIntent::create([
              "amount" => calculateOrderAmount($body->items),
              "currency" => $_SESSION["currency"],
              "payment_method" => $body->paymentMethodId,
              "customer" => $_SESSION["stripe_customer_id"],
              "confirmation_method" => "manual",
              "confirm" => true,
              'off_session' => true,
              // If a mobile client passes `useStripeSdk`, set `use_stripe_sdk=true`
              // to take advantage of new authentication features in mobile SDKs
          ]);
      }
      catch (\Stripe\Exception\CardException $err) {
          $error_code = $err->getError()->code;

          if($error_code == 'authentication_required') {
              // Bring the customer back on-session to authenticate the purchase
              // You can do this by sending an email or app notification to let them know
              // the off-session purchase failed
              // Use the PM ID and client_secret to authenticate the purchase
              // without asking your customers to re-enter their details
              echo json_encode(array(
                  'error' => 'authentication_required',
                  'amount' => calculateOrderAmount(""),
                  'card'=> $err->getError()->charge,
                  'paymentMethod' => $err->getError()->payment_method->id,
                  'publicKey' => $config->stripe_publishable_key,
                  'clientSecret' => $err->getError()->payment_intent->client_secret
              ));

          }
          else if
          ($error_code && $err->getError()->payment_intent != null) {
              // The card was declined for other reasons (e.g. insufficient funds)
              // Bring the customer back on-session to ask them for a new payment method
              echo json_encode(array(
                  'error' => $error_code.". ".$err->getError()->decline_code." ".$err->getError()->message ,
                  'publicKey' => $config->stripe_publishable_key,
                  'clientSecret' => $err->getError()->payment_intent->client_secret
              ));
              exit;
          }
          else if ($error_code=="card_declined")
          {
              $e1=$err->getError()->decline_code;
              $e2=$err->getError()->message;

              echo json_encode(array(
              'error' => $error_code.". ".$err->getError()->decline_code ,
              'message' => $err->getError()->message,
              'publicKey' => $config->stripe_publishable_key,
            ));
          exit;
          }
      }
  // After create, if the PaymentIntent's status is succeeded, fulfill the order.
  //check to see if there are errors if not update order in system to show payment is accepted
  contrbution_add(5,$intent->id);
  //header ("Location: /contribute/thanks.php?s=1");
  //exit; 
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
if (!is_null($output))
    {
       echo json_encode($output);
    }




