<!DOCTYPE html>
<html>
  <head>
    <title>Place Autocomplete</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
      }

      #infowindow-content .title {
        font-weight: bold;
      }

      #infowindow-content {
        display: none;
      }

      #map #infowindow-content {
        display: inline;
      }

      .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
      }

      #pac-container {
        padding-top: 6px;
        padding-bottom: 6px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
        width:300px;
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 380px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 14px;
        font-weight: 500;
        padding: 2px 12px;
      }
    </style>
  </head>
  <body>
    <form id="add_address" name="frm_address" action="/admin/ajax/save.php">
      <input type="hidden" name="t" id="t" value="address">
      <input type="hidden" name="lng" id="lng">
      <input type="hidden" name="lat" id="lat">
      <input type="hidden" name="address_name" id="address_name">
      <input type="hidden" name="car_time" id="car_time">
      <input type="hidden" name="order_time" id="order_time">
      <input type="hidden" name="eta" id="eta">

      <div  style="width:660px;height:400px;">
        <div class="pac-card" id="pac-card">
          <div>
            <div id="title">
              Add a delivery address
            </div>
          </div>
          <div id="pac-container">
            <input id="pac-input" type="text"
                placeholder="Enter a location">
          </div>
        </div>
        <div id="map"></div>
        <div id="infowindow-content">
          <img src="" width="16" height="16" id="place-icon">
          <span id="place-name"  class="title"></span><br>
          <span id="place-address"></span>
        </div>
      </div>
       <table id="address">
        <tr>
          <td class="label">Street address</td>
          <td class="slimField"><input class="field" id="street_number" name="street_number" disabled="true"/></td>
          <td class="wideField" colspan="2"><input class="field" id="route" name="route" disabled="true"/></td>
        </tr>
        <tr>
          <td class="label">City</td>
          <td class="wideField" colspan="3"><input class="field" id="locality" name="locality" disabled="true"/></td>
        </tr>
        <tr>
          <td class="label">State</td>
          <td class="slimField"><input class="field" id="administrative_area_level_1" name="administrative_area_level_1" disabled="true"/></td>
          <td class="label">Zip code</td>
          <td class="wideField"><input class="field" id="postal_code"  name="postal_code" disabled="true"/></td>
        </tr>
        <tr>
          <td class="label">Country</td>
          <td class="wideField" colspan="3"><input class="field" id="country" name="country" disabled="true"/></td>
        </tr>
      </table>
    </form>
      <div class="row-centered">
         <div  id="result"/>
        </div>
        <div  id="result_delivery"/>
        </div>
      </div> 
      <div class="row-centered">
        <input id="add_address" type="button" class="button" value="Confirm Order" onclick="add_address();"/>
      </div>
<script>
// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

function initMap() 
{
  var map = new google.maps.Map(document.getElementById('map'), {
    center: {lat: -43.506794,  lng: 172.728603},
    zoom: 13
  });
  var card = document.getElementById('pac-card');
  var input = document.getElementById('pac-input');
  var types = document.getElementById('type-selector');
  var strictBounds = document.getElementById('strict-bounds-selector');

  map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);

  var autocomplete = new google.maps.places.Autocomplete(input);

  // Bind the map's bounds (viewport) property to the autocomplete object,
  // so that the autocomplete requests use the current map bounds for the
  // bounds option in the request.
  autocomplete.bindTo('bounds', map);

  // Set the data fields to return when the user selects a place.
  autocomplete.setFields(
      ['address_components', 'geometry', 'icon', 'name']);

  var infowindow = new google.maps.InfoWindow();
  var infowindowContent = document.getElementById('infowindow-content');
  infowindow.setContent(infowindowContent);
  var marker = new google.maps.Marker({
    map: map,
    anchorPoint: new google.maps.Point(0, -29)
  });

  autocomplete.addListener('place_changed', function() {
    infowindow.close();
    marker.setVisible(false);
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      // User entered the name of a Place that was not suggested and
      // pressed the Enter key, or the Place Details request failed.
      window.alert("No details available for input: '" + place.name + "'");
      return;
    }

    // If the place has a geometry, then present it on a map.
    if (place.geometry.viewport) {
      map.fitBounds(place.geometry.viewport);
    } else {
      map.setCenter(place.geometry.location);
      map.setZoom(17);  // Why 17? Because it looks good.
    }
    marker.setPosition(place.geometry.location);
    marker.setVisible(true);

    var address = '';
    if (place.address_components) {
      address = [
        (place.address_components[0] && place.address_components[0].short_name || ''),
        (place.address_components[1] && place.address_components[1].short_name || ''),
        (place.address_components[2] && place.address_components[2].short_name || '')
      ].join(' ');
    }
   for (var component in componentForm) {
      document.getElementById(component).value = '';
      document.getElementById(component).disabled = false;
    }

    // Get each component of the address from the place details,
    // and then fill-in the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType).value = val;
      }
    }
    //fill in long and lat values to hidden form
    //bit confusing as there are a couple of values for long and lat assume they are start and finish values
    document.getElementById("lat").value=place.geometry.location.lat();
    document.getElementById("lng").value=place.geometry.location.lng();
    document.getElementById("address_name").value=address;
    infowindowContent.children['place-icon'].src = place.icon;
    infowindowContent.children['place-name'].textContent = place.name;
    infowindowContent.children['place-address'].textContent = address;
    infowindow.open(map, marker);
    var origin = new google.maps.LatLng(-43.506794,172.728603);
    var dest = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());
   

    var service = new google.maps.DistanceMatrixService();
    service.getDistanceMatrix(
      {
        origins: [origin],
        destinations: [dest],
        travelMode: 'DRIVING',
        unitSystem: google.maps.UnitSystem.METRIC,//google.maps.UnitSystem.IMPERIAL
        avoidHighways: false,
        avoidTolls: false,
      }, callback);

      function callback(response, status) 
      {
        if (status == 'OK') {
          var origins = response.originAddresses;
          var destinations = response.destinationAddresses;
          var siteDefault_ordertime=10 //average current order time in minutes 
          for (var i = 0; i < origins.length; i++) {
            var results = response.rows[i].elements;
            for (var j = 0; j < results.length; j++) {
              var element = results[j];
              var distance = element.distance.text;
              var duration = element.duration.text;
              var from = origins[i];
              var to = destinations[j];
            }
          }
          //duration is a string in minutes and hours
          var n = duration.indexOf("hour");
          var duration_min=0
          if (n>-1)
            {//get hours as minutes
            var subtract=3;
            if (n==2){subtract=2;}
            var duration_hours = duration.substr(n-subtract, 2);
            var duration_min=parseInt(duration_hours)*60;
            }
          var n = duration.indexOf("min");
          if (n==2){n=3;}
          //get the 3 digits before min they will be 2 char 1 space or 2 space 1 char then parseint form that.
          var duration_mins = duration.substr(n-3, 2);
          duration_min=duration_min+parseInt(duration_mins);
          var eta_minutes=parseInt(siteDefault_ordertime)+duration_min;
          $("#result").html("<b>Ordering from:</b>"+from+".<br><b>Delivering to</b> "+to+".<br><b>Distance:</b> "+distance+"<br><b>Time to Travel Time by car:</b> "+duration_min+" minutes. <br><b>Current Avg Order time:</b> "+siteDefault_ordertime+" minutes."+"<br><b>Expected delivery time:</b> "+eta_minutes.toString()+" minutes.");
          document.getElementById("car_time").value=duration_min;
          document.getElementById("order_time").value=siteDefault_ordertime;
          document.getElementById("eta").value=eta_minutes;
        }
      }
  });

}
</script>
<script>
// This sample uses the Autocomplete widget to help the user select a
// place, then it retrieves the address components associated with that
// place, and then it populates the form fields with those details.
// This sample requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script
// src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

var placeSearch, autocomplete;

var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};


function add_address()
{
//so check to see that the fields are populated if they are add the address
if (!document.getElementById("street_number").value=="")
  {
    url="/admin/ajax/save.php?"
     post=$("#add_address").serialize()
    $.post(url,post,function(data) 
        {$("#result").html(data);}
        )
  }
else
  {alert("must have a valid street number");}

}
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function geolocate() 
{
if (navigator.geolocation) 
  {
    navigator.geolocation.getCurrentPosition(function(position) 
    {
      var geolocation = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };
      var circle = new google.maps.Circle(
          {center: geolocation, radius: position.coords.accuracy});
      autocomplete.setBounds(circle.getBounds());
    });
  }
}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlwjW1AnOla7-K0hV-mJV1nA-oRODlHIE&libraries=places&callback=initMap"
        async defer></script>
  </body>
</html>