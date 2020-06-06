<?php
if(!isset($_SESSION)) { session_start();} 
if ($_SESSION["id_people"]=="") 
	{header ("Location: /login.php");
	exit;}

require('header.php');
//echo "hello".isset($_SESSION["email"]);
if(!isset($_SESSION)) { session_start();} 
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
//this is the page where people get to comsume the recipe item that they have been viewing
//check out how many portion size options there are with this one.
//List as portion size options 
//show photo of this one + any other currently in cart...
//process person add recipes to profile.
//Show thanks you screen enjoy your meal.
//get the meal with suggested contirbution
//love_your_food.asp ASAP
//One DanaOne per meal for make at home
//Cost Plus One DanaOne per meal for in commerical service
//DanaOne per meal for in home service
//Cost is not optional
//DanaOne is optional
//add on takeaway options and danaone as checkboxes.
//Add recipes to order using ajax
//first thing we are inserting into meal time so SQL that.
//x=rwe("Under Construction")
//Insert to payments Table
//Need to formulate the plan to record nutrition data.....
//Random thoughts -------------
//Ok so get whatever is in the cart and put that into a table along with related nutrional data.
//NB recipes and food are able to change so what is in a person cart needs to recorded fully and not just the record id for the recipe.
//Need to consider the portion size as well.
//Pop this into a table so nutrition data over time by customer can be recorded and queryed

$conn2=open_conn();

if (!$_GET["pay_return"]=="")
{
	$sSQL="INSERT INTO `contributions`";
	$sSQL=$sSQL."(`id_payment_type`,";
	$sSQL=$sSQL."`id_helper`,";
	$sSQL=$sSQL."`id_people`,";
	$sSQL=$sSQL."`id_meal`,";
	$sSQL=$sSQL."`amount`)";
	$sSQL=$sSQL." Values  ";
	$sSQL=$sSQL."(".request("t").",";
	$sSQL=$sSQL."".$_SESSION["id_helper"].",";
	$sSQL=$sSQL."".$_SESSION["id_people"].",";
	$sSQL=$sSQL."".$_SESSION["id_meal"].",";
	$sSQL=$sSQL."".$_SESSION["total_price"].");";
	$result = $conn->query($sSQL) or die($conn->error);
	//update meal has been conpleted
	$sSQL="Call Update_people_eat (".$_SESSION["id_people"].")";
	$result = $conn->query($sSQL) or die($conn->error);
	$xBody="<h2>Thanks for contributing today.  Below you will see a brief nutrient breakdown of each recipe, and the overall nutritional value of the meal. </br></br>";
	$xBody=$xBody.'You can always check out the status of your nutrition from <a href="https://antidote.org.nz/my_nutrition.asp?p=&'.$_SESSION["uid_people"].'">antidote.org.nz/my_nutrition.asp</a> </h2> </br></br>';
	$xBody=$xBody.getLastRecipesServed($_SESSION["id_meal"]);
	echo $xBody;

}
//show different payment methods

//ok set up a few differnet varibles.
//first check the dana table to see if user has dana

if (!isset($_GET["p"]))
{
?>
<div id="dana_options" style="align-content: center; margin-top: 20px;margin-bottom: 20px;width: 800px">  
	<div class="row row-centered" style="width: 100%;margin-left:50%;margin-right: 50%;">
		<div class="col-" style="width: 100%;">
			<div>
				<h2>How would you like to contribute for your meal?</h2>
			</div>
			<ul  style="list-style: none; ">
				<li>
					<a  style="width:150px;margin: 10px 0;" class="button contribute_dana" href="/contribute.php?p=1&t=3">Dana</a>	
				</li>
				<li>
					<a style="width:150px;margin: 10px 0;" class="button contribute_cash" href="/contribute.php?p=2&t=2">Cash on Delivery</a>
				</li>
				<li>
					<a style="width:150px;margin: 10px 0;" class="button contribute_eftpos_disabled" href="/contribute.php?p=3&t=1">Mobile Eftpos</a>
				</li>
				<li>
					<a style="width:150px;margin: 10px 0;" class="button contribute_cconline " href="/contribute.php?p=4&t=1">Credit Card Online</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php 
}
else
{

?>

 <script src="https://js.stripe.com/v3/"></script>


<div id="stripe_pay" style="width:400px; margin-right: 50%;margin-left: 50%">
	<form action="/contribute/onlinecc.php" method="post" id="payment-form">

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

// A reference to Stripe.js
var stripe;
var stripe = Stripe('pk_test_Qxeb9z2kj48kI4kiT4K9Y4yl00LpsbyqJS');
// Information about the order
// Used on the server to calculate order total
var orderData = {
  items: [{ id: "photo-subscription" }],
  currency: "usd"
};

fetch("contribution/paymentintent.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify(orderData)
})
  .then(function(result) {
    return result.json();
  })
  .then(function(data) {
    return setupElements(data);
  })
  .then(function(stripeData) {
    document.querySelector("#submit").addEventListener("click", function(evt) {
      evt.preventDefault();
      // Initiate payment
      pay(stripeData.stripe, stripeData.card, stripeData.clientSecret);
    });
  });

// Set up Stripe.js and Elements to use in checkout form
var setupElements = function(data) {
  stripe = Stripe(data.publicKey);
  var elements = stripe.elements();
  var style = {
    base: {
      color: "#32325d",
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: "antialiased",
      fontSize: "16px",
      "::placeholder": {
        color: "#aab7c4"
      }
    },
    invalid: {
      color: "#fa755a",
      iconColor: "#fa755a"
    }
  };

  var card = elements.create("card", { style: style });
  card.mount("#card-element");

  return {
    stripe: stripe,
    card: card,
    clientSecret: data.clientSecret,
    id: data.id
  };
};

/*
 * Calls stripe.confirmCardPayment which creates a pop-up modal to
 * prompt the user to enter  extra authentication details without leaving your page
 */
var pay = function(stripe, card, clientSecret) {
  var cardholderName = document.querySelector("#name").value;
  var isSavingCard = document.querySelector("#save-card").checked;

  var data = {
    card: card,
    billing_details: {}
  };

  if (cardholderName) {
    data["billing_details"]["name"] = cardholderName;
  }

  changeLoadingState(true);

  // Initiate the payment.
  // If authentication is required, confirmCardPayment will automatically display a modal

  // Use setup_future_usage to save the card and tell Stripe how you plan to charge it in the future
  stripe
    .confirmCardPayment(clientSecret, {
      payment_method: data,
      setup_future_usage: isSavingCard ? "off_session" : ""
    })
    .then(function(result) {
      if (result.error) {
        changeLoadingState(false);
        var errorMsg = document.querySelector(".sr-field-error");
        errorMsg.textContent = result.error.message;
        setTimeout(function() {
          errorMsg.textContent = "";
        }, 4000);
      } else {
        orderComplete(clientSecret);
        // There's a risk the customer will close the browser window before the callback executes
        // Fulfill any business critical processes async using a 
        // In this sample we use a webhook to listen to payment_intent.succeeded 
        // and add the PaymentMethod to a Customer
      }
    });
};

/* ------- Post-payment helpers ------- */

// Shows a success / error message when the payment is complete
var orderComplete = function(clientSecret) {
  stripe.retrievePaymentIntent(clientSecret).then(function(result) {
    var paymentIntent = result.paymentIntent;
    var paymentIntentJson = JSON.stringify(paymentIntent, null, 2);
    document.querySelectorAll(".payment-view").forEach(function(view) {
      view.classList.add("hidden");
    });
    document.querySelectorAll(".completed-view").forEach(function(view) {
      view.classList.remove("hidden");
    });
    document.querySelector(".status").textContent =
      paymentIntent.status === "succeeded" ? "succeeded" : "did not complete";
    document.querySelector("pre").textContent = paymentIntentJson;
  });
};

// Show a spinner on payment submission
var changeLoadingState = function(isLoading) {
  if (isLoading) {
    document.querySelector("button").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("button").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#button-text").classList.remove("hidden");
  }
};
</script>
<?php
}
?>

 

