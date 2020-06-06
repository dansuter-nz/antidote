<?php include 'header.php';?>
<div id="main-content">
  <div class="row">
   <div class="col-md-6">
   <p style="font-size:16px;text-align: left;width:90%">
    <h1>Our contact details</h1>
   <address>
   	<b><?=$_SESSION["name"]?></b><br>
   <?=$_SESSION["address_1"]?></br> <?=$_SESSION["address_2"]?>,</br> <?=$_SESSION["city"]?>,</br> <?=$_SESSION["post_code"]?> </br>
   <br>
   email: <a href="mailto:<?=$_SESSION["email"]?>"><?=$_SESSION["email"]?></a><br>
   ph:<?=$_SESSION["phone"]?>
   </address>
  </p>
  </div>
  <div class="col-md-6" style="border:2px solid black;">
  <div id="map"  style="margin-top:20px;  width: 600px;  height: 450px; max-width: 100vw;"></div>
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlwjW1AnOla7-K0hV-mJV1nA-oRODlHIE&libraries=places&callback=initMap"
    async defer></script>
  <script type="text/javascript">
    function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: <?=$_SESSION["lat"]?>,
            lng: <?=$_SESSION["lng"]?>,
            mapTypeControl: true,
            mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
            position: google.maps.ControlPosition.BOTTOM_LEFT
            }
        },
        zoom: 18
    });
  }
  </script>
<?php include 'footer.htm';?>