<?php

if(!isset($_SESSION)) { session_start();} 
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
?>
<div id="stripe_pay" style="width:400px; margin-right: 50%;margin-left: 50%">
	<form action="/payments/stripe.php" method="post" id="payment-form">

	  <div class="form-row">
	    <label for="card-element">
	      Credit or debit card
	    </label>
	   
			<div id="card-element">
			  <!-- A Stripe Element will be inserted here. -->
			</div>
		
		    <!-- Used to display Element errors. -->
		    <div id="card-errors" role="alert"></div>

	  </div>
	  <button  id="submit">Pay</button>
	</form>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
// Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
var stripe = Stripe('pk_test_Qxeb9z2kj48kI4kiT4K9Y4yl00LpsbyqJS');
var elements = stripe.elements();
// Custom styling can be passed to options when creating an Element.
var style = {
  base: {
    color: "#32325d",
  }
};

var card = elements.create("card", { style: style });
card.mount("#card-element");

// Create a token or display an error when the form is submitted.
card.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

stripe.confirmCardPayment(clientSecret, {
  payment_method: {
    card: card,
    billing_details: {
      name: "<?=$_SESSION["name"]?>"
    }
  },
  setup_future_usage: 'off_session'
}).then(function(result) {
  if (result.error) {
    // Show error to your customer
    console.log(result.error.message);
  } else {
    if (result.paymentIntent.status === 'succeeded') {
      // Show a success message to your customer
      // There's a risk of the customer closing the window before callback execution
      // Set up a webhook or plugin to listen for the payment_intent.succeeded event
      // to save the card to a Customer

      // The PaymentMethod ID can be found on result.paymentIntent.payment_method
    }
  }
});


</script>
