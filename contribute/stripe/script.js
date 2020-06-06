// A reference to Stripe.js
var stripe;

var orderData = {
  items: [{ id: "Contribution for Meal" }],
  currency: "nzd"
};

fetch("stripe-key.php")
  .then(function(result) {
    return result.json();
  })
  .then(function(data) {
    return setupElements(data);
  })
  .then(function({ stripe, card, clientSecret }) {
    document.querySelector("#submit").addEventListener("click", function(evt) {
      evt.preventDefault();
      pay(stripe, card, clientSecret);
    });
  });

var setupElements = function(data) {
  stripe = Stripe(data.publicKey);
  /* ------- Set up Stripe Elements to use in checkout form ------- */
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

  var card = elements.create("card", {hidePostalCode: true, style: style });
  card.mount("#card-element");

  return {
    stripe,
    card,
    clientSecret: data.clientSecret
  };
};

var handleAction = function(clientSecret) {
  // Show the authentication modal if the PaymentIntent has a status of "requires_action"
  stripe.handleCardAction(clientSecret).then(function(data) {
    if (data.error) {
      showError("Your card was not authenticated, please try again");
    } else if (data.paymentIntent.status === "requires_confirmation") {
      // Card was properly authenticated, we can attempt to confirm the payment again with the same PaymentIntent
      fetch("pay.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          paymentIntentId: data.paymentIntent.id
        })
      })
        .then(function(result) {
          return result.json();
        })
        .then(function(json) {
          if (json.error) {
            showError(json.error);
          } else {
            orderComplete(clientSecret);
          }
        });
    }
  });
};

/*
 * Collect card details and pay for the order 
 */
var pay = function(stripe, card) {
  var cardholderName = document.querySelector("#name").value;
  var data = {
    billing_details: {}
  };

  if (cardholderName) {
    data["billing_details"]["name"] = cardholderName;
  }

  changeLoadingState(true);

  // Collect card details
  stripe
    .createPaymentMethod("card", card, data)
    .then(function(result) {
      if (result.error) {
        showError(result.error.message);
      } else {
        orderData.paymentMethodId = result.paymentMethod.id;
        orderData.isSavingCard = document.querySelector("#save-card").checked;

        return fetch("pay.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify(orderData)
        });
      }
    })
    .then(function(result) {
      return result.json();
    })
    .then(function(paymentData) {
      if (paymentData.requiresAction) {
        // Request authentication
        handleAction(paymentData.clientSecret);
      } else if (paymentData.error) {
        showError(paymentData.error);
      } else {
        orderComplete(paymentData.clientSecret);
      }
    });
};

/* ------- Post-payment helpers ------- */

/* Shows a success / error message when the payment is complete */
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
      paymentIntent.status === "succeeded" ? "succeeded" : "failed";
    document.querySelector("pre").textContent = paymentIntentJson;
    var delay = 1000;
    URL="/me.php?v=orders" 
    setTimeout(function(){ window.location = URL; }, delay);
  });

};

var showError = function(errorMsgText) {
  changeLoadingState(false);
  var errorMsg = document.querySelector(".sr-field-error");
  errorMsg.textContent = errorMsgText;
  setTimeout(function() {
    errorMsg.textContent = "";
  }, 4000);
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

function paysavedcard(payID)
{
  //alert(*"#CVC"+payID);
  var cvc= document.querySelector("#cvc"+payID).value;
  //alert(cvc.length);
  if (cvc.length!=3)
    {alert ("CVC is required");}
  else
  {
    //alert("{'idc':{'id':"+payID+",'cvc':"+cvc+"}}");
    fetch("pay.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: '{"paymentMethodId":"'+payID+'","cvc":'+cvc+'}'
    })
        .then(function(result) {
          return result.json();
        })
        .then(function(paymentData) {
          if (paymentData.requiresAction) {
            // Request authentication
            handleAction(paymentData.clientSecret);
          } else if (paymentData.error) {
            showError(paymentData.error);
          } else {
            orderComplete(paymentData.clientSecret);
          }
        });

  }

}
function delsavedcard(payID2)
{
  //alert(*"#CVC"+payID);
  //alert('{"cardId":"'+payID2+'","del":1}');
  fetch("pay.php", {
  method: "POST",
  headers: {
    "Content-Type": "application/json"
  },
  body: '{"cardId":"'+payID2+'","del":1}'
  })
  alert ("Your card has been removed!");
  location.href="/contribute/stripe/";
}


