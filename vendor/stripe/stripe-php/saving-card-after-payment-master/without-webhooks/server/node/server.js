const express = require("express");
const app = express();
const { resolve } = require("path");
// Copy the .env.example in the root into a .env file in this folder
const env = require("dotenv").config({ path: "./.env" });
const stripe = require("stripe")(env.parsed.STRIPE_SECRET_KEY);

app.use(express.static(env.parsed.STATIC_DIR));
app.use(
  express.json({
    // We need the raw body to verify webhook signatures.
    // Let's compute it only when hitting the Stripe webhook endpoint.
    verify: function(req, res, buf) {
      if (req.originalUrl.startsWith("/webhook")) {
        req.rawBody = buf.toString();
      }
    }
  })
);

app.get("/", (req, res) => {
  // Display checkout page
  const path = resolve(env.parsed.STATIC_DIR + "/index.html");
  res.sendFile(path);
});

app.get("/stripe-key", (req, res) => {
  res.send({ publicKey: env.parsed.STRIPE_PUBLISHABLE_KEY });
});

const calculateOrderAmount = items => {
  // Replace this constant with a calculation of the order's amount
  // You should always calculate the order total on the server to prevent
  // people from directly manipulating the amount on the client
  return 1400;
};

app.post("/pay", async (req, res) => {
  const {
    paymentMethodId,
    paymentIntentId,
    items,
    currency,
    isSavingCard
  } = req.body;

  const orderAmount = calculateOrderAmount(items);

  try {
    let intent;
    if (!paymentIntentId) {
      // Create new PaymentIntent
      let paymentIntentData = {
        amount: orderAmount,
        currency: currency,
        payment_method: paymentMethodId,
        confirmation_method: "manual",
        confirm: true
      };

      if (isSavingCard) {
        // Create a Customer to store the PaymentMethod
        const customer = await stripe.customers.create();
        paymentIntentData.customer = customer.id;
        
        // setup_future_usage saves the card and tells Stripe how you plan to use it later
        // set to "off_session" if you plan on charging the saved card when your user is not present
        paymentIntentData.setup_future_usage = 'off_session';
      }

      intent = await stripe.paymentIntents.create(paymentIntentData);
    } else {
      // Confirm the PaymentIntent to place a hold on the card
      intent = await stripe.paymentIntents.confirm(paymentIntentId);
    }

    const response = generateResponse(intent);
    res.send(response);
  } catch (e) {
    // Handle "hard declines" e.g. insufficient funds, expired card, etc
    // See https://stripe.com/docs/declines/codes for more
    res.send({ error: e.message });
  }
});

const generateResponse = intent => {
  // Generate a response based on the intent's status
  switch (intent.status) {
    case "requires_action":
    case "requires_source_action":
      // Card requires authentication
      return {
        requiresAction: true,
        paymentIntentId: intent.id,
        clientSecret: intent.client_secret
      };
    case "requires_payment_method":
    case "requires_source":
      // Card was not properly authenticated, suggest a new payment method
      return {
        error: "Your card was denied, please provide a new payment method"
      };
    case "succeeded":
      // Payment is complete, authentication not required
      // To cancel the payment after capture you will need to issue a Refund (https://stripe.com/docs/api/refunds)
      console.log("💰 Payment received!");
      return { clientSecret: intent.client_secret };
  }
};

app.listen(4242, () => console.log(`Node server listening on port ${4242}!`));
