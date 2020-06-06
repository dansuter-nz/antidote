<?php include 'header.php';?>
<div id="main-content">
  <div class="aboutspace">
  </div>
  <div class="row">
   <div class="col-sm-6">
    </br>
    </br>
  <b>the Raw Alchemist E-Book</b></br>
   </br>
   The Raw Alchemist. The power of raw food is a forgotten wisdom. Enzymes in our gut are responsible for so much of our health conditions.  When we consume living food we natuarlly absorb more of these living enzymes increasing the health of our gut which allows your digestive and other systems to function even better.
 </br><br>
 <div id="response"></div>
USD$29 <div id="paypal-button"></div>
  </div>
  <div class="col-sm-6">
   <img style="margin-top:20px;text-align: left;  width: 100%;  height: auto;  /* Magic! */  max-width: 50vw;" src="/images/raw-alchemist.jpg">
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
   <img style="margin-top:20px;text-align: left;  width: 100%;  height: auto;  /* Magic! */  max-width: 50vw;" src="/images/raw-alchemist2.webp">

  </div>
  <div class="col-sm-6">
    </br></br>
  <b>Raw Alchemist Hard Copy</b></br>
   </br>
The Raw Alchemist contains a master collection of the café's best selling recipes and techiniques. With organic, unprocessed and vegan offerings from cunilary wizards Shanti Allen and Lesya Pyatnichko you'll discover everything you need for an introduction to raw food or a complete healthy and lifestyle transformation.
 </br><br>USD$40  <div id="response"></div>
<div id="paypal-button1"></div>
  </div>
</div>
<script>
var ipercent=100;
function deposit1(percent)
{
ipercent=percent;
 var a=$("#totalacc").val();
 a=a*ipercent/100;
 //alert($("input#totalint").val());
 $("#total2day").html("USD$<b><u>"+a+"</u></b>");
 $("#totalint2day").val(a);
}

function disable_acc(row)
{
  //make rows disabled
  //alert(row);
  $.each($('.accomodation'), function (index, value) {
  $(this).addClass(" disabled");
});
  $.each($('.imgacc'), function (index, value) {
  $(this).css("opacity","0.25");
});
  $("#img"+row).css("opacity","1");
  $("#tr"+row).removeClass(" disabled");
  $("#tr"+row).removeClass(" disabled");
  //alert($("#course_price").html());
  //alert($("#acc_price"+row).html());
  var a=parseInt($("#course_price").html());
  var b=parseInt($("#acc_price"+row).html());
  var t=a+b;
  var paynow=t*ipercent/100;
  //alert(t);
  $("#total").html("Total&nbsp;USD$<b><u>"+t+"</u></b>");
  $("#total2day").html("USD$<b><u>"+paynow+"</u></b>");
  $("#totalacc").val(t);
  $("#totalint2day").val(paynow);
  
}


</script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
  paypal.Button.render({
    // Configure environment
    env: 'sandbox',
    client: {
      sandbox: 'client_id'
    },
    // Set up a payment
    payment: function(data, actions) {
      return actions.payment.create({
        transactions: [{
          amount: {
            total: '29.00',
            currency: 'USD'
          }
        }]
      });
    },
    // Execute the payment
    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {
        document.getElementById("response").style.display = 'inline-block';
        document.getElementById("response").innerHTML = 'Thank you for making the payment!';
      });
    }
  }, '#paypal-button'); 

  paypal.Button.render({
    // Configure environment
    env: 'sandbox',
    client: {
      sandbox: 'client_id'
    },
    // Set up a payment
    payment: function(data, actions) {
      return actions.payment.create({
        transactions: [{
          amount: {
            total: '40.00',
            currency: 'USD'
          }
        }]
      });
    },
    // Execute the payment
    onAuthorize: function(data, actions) {
      return actions.payment.execute().then(function() {
        document.getElementById("response").style.display = 'inline-block';
        document.getElementById("response").innerHTML = 'Thank you for making the payment!';
      });
    }
  }, '#paypal-button1');

</script>

<?php include 'footer.htm';?>
