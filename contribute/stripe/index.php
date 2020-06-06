<?php
if(!isset($_SESSION)) { session_start();} 
if ($_SESSION["id_people"]=="") 
  {header ("Location: /login.php");
  exit;}


if (!isset($_SESSION["total_price"])) 
  {
      //header ("Location: /login.php");
  //exit;
  }
$path = "/var/www/html/antidote_apache";
$path .= "/header.php";
//include_once($path);

require($path);
//added code by me to the example
$conn=open_conn();
\Stripe\Stripe::setApiKey('sk_test_dZ2b6tUngIfsGBEfBtW3mdoB00ChXPEQh0');

$sSQL="Select vendor_key from antidote.people_saved_cards p where id_person='".$_SESSION["id_people"]."' and p.vendor='stripe'";
$result = $conn->query($sSQL) or die($conn->error);
//echo "<br> ROWS:".$result2->num_rows."<br>".$sSQL;
if ($result->num_rows==0)
{
  $customer = \Stripe\Customer::create();
  $s="INSERT INTO people_saved_cards (id_person,vendor,vendor_key) VALUES ('".$_SESSION["id_people"]."','stripe','".$customer->id."');";
  echo "<br>".$s;
  $result = $conn->query($s) or die($conn->error);
}
else
{
  while($row = $result->fetch_assoc())
  {
    $customer = \Stripe\Customer::retrieve($row["vendor_key"]);
  } 
}
$intent = \Stripe\SetupIntent::create([
  'customer' => $customer->id,
  'usage' => "on_session"
]);
$_SESSION["stripe_customer_id"]=$customer->id;


//code add finished
//echo $_SESSION["stripe_customer_id"];

$cards=\Stripe\PaymentMethod::all([
  'customer' => $_SESSION["stripe_customer_id"],
  'type' => 'card',
]);
$card_list = json_encode($cards);
//echo $card_list;
//exit;
//echo $cards->data[0]->id."<br>";


$no_of_cards=sizeof($cards->data);
if ($no_of_cards>0)
  {
  $x=0;

  $s='<div id="dana_options" style="align-content: center; margin-top: 10px;margin-bottom: 10px; width:100%">
   <div class="sr-payment-summary payment-view">
    <div class="order-amount">
      <h2 style="">Total $'.$_SESSION["total_price"].'</h2>
      <h3>Contribution for Meal '.$_SESSION["id_meal"].'</h3>
    </div>
  </div>
  <div class="sr-payment-form payment-view">
    <div class="sr-form-row"><label for="card-element">Use a saved card</label></div>
    <div class="sr-form-row">';
  while ($x<$no_of_cards) 
  {
    if ($x==0){$default=" checked";$disabled="";}
    else{$default="";$disabled=" disabled";}

    //html for card list
    $id=$cards->data[$x]->id;
    $s=$s."<div class='row'>
            <div class='col-md-4' style='margin-top:10px;'><span style=\"color:#888;font-weight:bold;\">XXXX XXXX XXXX</span> <b>".$cards->data[$x]->card->last4."</b> Expires <b>".$cards->data[$x]->card->exp_month."/".$cards->data[$x]->card->exp_year."</b></div>
            <div class='col-md-2'><input type=\"text\" style=\"width:70px;\" maxlength=\"3\" size=\"3\" id=\"cvc".$id."\" name=\"cvc".$id."\" placeholder=\"CVC\"></div>
            <div class='col-md-3'>
            <button id=\"saved_card".$x."\" style=\"width:200px;\" onclick=\"paysavedcard('".$id."');event.cancelBubble = true;\">Use card</button> 
            </div><div class='col-md-3'>
            <button class='delete_card' id=\"saved_card".$x."_del\" style=\"width:200px;\" onclick=\"delsavedcard('".$id."');event.cancelBubble = true;\">Delete card</button>
            </div>
          </div>
        ";
    $x=$x+1;
  }
    $s=$s.'
    </div>

    </div>
    </div>';
 }

?>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/normalize.css" />
  <link rel="stylesheet" href="css/global.css" />
  <script src="https://js.stripe.com/v3/"></script>
  <script src="script.js" defer></script>
  <body>

  <?=$s;?>
    <form id="setup-form" data-secret="<?= $intent->client_secret ?>">

    <div class="sr-root">
      <div class="sr-main col-">
          
        <div class="sr-payment-form payment-view">
          <div class="sr-form-row">
            <label for="card-element">
              Or use a new card
            </label>
            <div class="sr-combo-inputs">
              <div class="sr-combo-inputs-row">
                <input
                  type="text"
                  id="name"
                  placeholder="Name"
                  autocomplete="cardholder"
                  class="sr-input"
                />
              </div>
              <div class="sr-combo-inputs-row">
                <div class="sr-input sr-card-element" id="card-element"></div>
              </div>
            </div>
            <div class="sr-field-error" id="card-errors" role="alert"></div>
            <div class="sr-form-row">
              <label class="sr-checkbox-label"><input type="checkbox" id="save-card"><span class="sr-checkbox-check"></span> Save card for future payments</label>
            </div>
          </div>
          <button id="submit" style="width:300px"><div class="spinner hidden" id="spinner"></div><span  id="button-text">Use a new card</span></button>
          <div class="sr-legal-text">
            Your card will be charged $<?=$_SESSION["total_price"]?>.
          </div>
        </div>
        <div class="sr-payment-summary hidden completed-view">
          <h1>Your payment <span class="status"></span>!</h1>
          <h4>
          </h4>
        </div>
        <div class="sr-section hidden completed-view">
          <div class="sr-callout" style="display:none;">
            <pre>
  
            </pre>
          </div>
          <button onclick="window.location.href = '/me.php';">Show me the numbers!</button>
        </div>  
      </div>
      <div class="sr-content col-">
          <?php
          if (!$_SESSION["mymeal_summary"]=="")
              {echo "<div class='row'><div class='col-'><a href='/loveyourfood.php'>
                <img src='/images/mymeal.png' alt='Your meal' >
                </a></div>
                <div class='col-'>
                ".$_SESSION["mymeal_summary"]."
                </div>
                </div>";
              }
          ?>
      </div>
    </div>
  </form>
  </body>
</html>
