<?php

if(!isset($_SESSION)) { session_start();} 
if ($_SESSION["id_people"]=="") 
	{header ("Location: /login.php");
	exit;}
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/header.php');
//echo "hello".isset($_SESSION["email"]);

$conn=open_conn();
if (isset($_SESSION["email"]))
{
	if (isset($_GET["r"])) {
		$sSQL="";
		$sSQL="Select count(*) 'fv_count' from people_recipe_favourites  where id_people=".$_SESSION["id_people"]." and id_recipe=".$_GET["r"]."";
		
		$result = $conn->query($sSQL) or die($conn->error);
		$fv_count=0;
		while($row = $result->fetch_assoc())
		{
			$fv_count=$row["fv_count"];
		}
		if ($fv_count==0)
		{
		$sSQL="";
		$sSQL="INSERT INTO people_recipe_favourites (id_people,id_recipe)";
		$sSQL=$sSQL." Select	".$_SESSION["id_people"].",".$_GET["r"].";";
		$result = $conn->query($sSQL) or die($conn->error);
		}
		//echo $sSQL;
		//echo "favourite added";
	}
}
$iMeal=0;
//this is the page where people get to comsume the recipe item that they have been viewing
//check out how many portion size options there are with this one.
//List as portion size options 
//show photo of this one + any other currently in cart...
//process person add recipes to profile.
//Show thanks you screen enjoy your meal.
//get the meal with suggested contirbution
//love_your_food.php ASAP
//One DanaOne per meal for make at home
//Cost Plus One DanaOne per meal for in commerical service
//DanaOne per meal for in home service
//Cost is not optional
//DanaOne is optional
//add on takeaway options and danaone as checkboxes.
//Add recipes to order using ajax
//first thing we are inserting into meal time so SQL that.
if ($_SESSION["id_helper"]=="") {$_SESSION["id_helper"]=$_SESSION["id_people"];}
if ($_GET["p"]=="" || !is_numeric($_GET["p"])) {
	//if $_GET["d"]="" then 	x=rwe("Invalid Portion Size"]
}
 
	if (isset($_GET["tbl"])) {
	 $_SESSION["table_id"]=$_GET["tbl"];
		$sSQL="Update_people_meal (".$_SESSION["id_meal"].",'".$_SESSION["table_id"]."')";
		$result = $conn->query($sSQL) or die($conn->error);
	}
 
if ($_SESSION["table_id"]=="") {$_SESSION["table_id"]="11";}
 
if (!$_GET["r"]=="") 
{	//check to see if there is a current meal for the customer?
	$sSQL="Select id_meal from people_meals where id_people=".$_SESSION["id_people"]." and consumed=0;";
	//echo $sSQL;
	$result = $conn->query($sSQL) or die($conn->error);
	if (mysqli_num_rows($result)==0)
		{
		$sSQL="call Insert_people_meal (".$_SESSION["id_people"].", ".$_SESSION["id_helper"].",'".$_GET["meal_comment"]."','".$_SESSION["table_id"]."')";
		echo $sSQL;
		$result = $conn->query($sSQL) or die($conn->error);		
		}
	else
		{
			while($row = $result->fetch_assoc())
			{$iMeal=$row["id_meal"];}
		}
	if (isset($_GET["recipe_comment"]))
		{$comment=$_GET["recipe_comment"];}
	$sSQL="Call Insert_people_eat (".$iMeal.",".$_GET["r"]." ,".$_GET["p"].", '".$comment."')";
	//echo $sSQL;
	$id_recipe=$_GET["r"];
	$result = $conn->query($sSQL) or die($conn->error);
}

$sSQL="Select id_meal from people_meals where id_people=".$_SESSION["id_people"]." and consumed=0;";
$result = $conn->query($sSQL) or die($conn->error);
$comment="";
while($row = $result->fetch_assoc())
	{$iMeal=$row["id_meal"];}
$_SESSION["id_meal"]=$iMeal;

 
if (isset($_GET["d"])) {
	$sSQL="Delete from people_meals_recipes where id_people_meals_recipes=".$_GET["d"].";";
	//x=rwb(sSQL)
	$result = $conn->query($sSQL) or die($conn->error);
	//check if there is nothing left in people eat then delete the meal as well.
	//now update the graph please.	
}
if ($id_recipe=="") {$id_recipe=0;}
?>

<form  id="eat_me" name="eat_me" action="/givewithlove.php">
<input type="hidden" name="idr" id="idr" value="<?=$id_recipe?>">
<h1>And medicine be thy food.</h1>
<?php
$conn2=open_conn();
$s="";
if ($_GET["l"]=="0")
	{$_SESSION["dine_in"]=false;}
if ($_GET["l"]=="1")
	{$_SESSION["dine_in"]=true;}

echo GetCurrentMeal($_SESSION["id_people"]);
	?>



		<div class="row row-centered" style="height:50px;vertical-align: bottom;">       
		    <div class="col-" style="height:50px;vertical-align: bottom;">
		    	<a href="/recipes.php">Add some more ......</a><br><br>
			</div>
		</div>
		<div style="max-width:550px;margin-left: auto;margin-right: auto;">
			<div class="row row-centered">  
			    <div class="col-" style="margin-right: auto;margin-left: auto;margin-bottom: 15px;">
					<span style="min-width:180px;" class="button where" id="eat_in" onlclick="eating_location(this.id)">Eat In</span>
				</div>
				<div class="col-" style="margin-right: auto;margin-left: auto;margin-bottom: 15px;">
					<span style="min-width:180px;" class="button where" id="takeaway">Takeaway</span>
				</div>
				<div class="col-" style="margin-right: auto;margin-left: auto;margin-bottom: 15px";>
					<span style="min-width:180px;" class="button where"  id="delivery" >Delivery</span>
				</div>
			</div>
		</div>
<!--This is where the autocomplete delivery code starts-->
		<div id="map_code" style="display:none">

	      <input type="hidden" name="t" id="t" value="address">
	      <input type="hidden" name="lng" id="lng">
	      <input type="hidden" name="lat" id="lat">
	      <input type="hidden" name="address_name" id="address_name">
	      <input type="hidden" name="car_time" id="car_time">
	      <input type="hidden" name="order_time" id="order_time">
	      <input type="hidden" name="km_delivery_rate" id="km_delivery_rate" value="<?=$_SESSION["delivery_charge_per_km"]?>">
	      <input type="hidden" name="delivery_surchage" id="delivery_surchage" value="<?=$_SESSION["delivery_charge_surcharge"]?>">
	      <input type="hidden" name="delivery_price" id="delivery_price" value=0>
	      <input type="hidden" name="eta" id="eta">

	      <div   style="padding-top:10px;padding-bottom:20px;width: 600px;">
	        <div class="pac-card" id="pac-card">
	          <div>
	            <div id="title">
	              Add a delivery address
	            </div>
	          </div>
	          <div id="pac-container" style="">
	            <input id="pac-input" type="text"
	                placeholder="Enter a location">
	          </div>
	        </div>
	        <div id="map"  style="margin-top:20px;  width: 600px;  height: 450px; max-width: 100vw;"></div>
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

		<div class="row-centered" style="padding-top:10px;">
			<input id="add_address_btn" type="button" class="button" value="Request delivery to this address." onclick="add_address();"/>
		</div>

	</div>

<script>
// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: -43.506794,
            lng: 172.728603,
            mapTypeControl: true,
	          mapTypeControlOptions: {
	          style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
	          position: google.maps.ControlPosition.BOTTOM_LEFT
          	}
        },
        zoom: 18
    });
    var card = document.getElementById('pac-card');
    var input = document.getElementById('pac-input');
    var types = document.getElementById('type-selector');
    var strictBounds = document.getElementById('strict-bounds-selector');
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
            //map.setZoom(15); // Why 17? Because it looks good.
            
        }
        map.setZoom(17); // Why 17? Because it looks good.
       
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
        document.getElementById("lat").value = place.geometry.location.lat();
        document.getElementById("lng").value = place.geometry.location.lng();
        document.getElementById("address_name").value = address;
        infowindowContent.children['place-icon'].src = place.icon;
        infowindowContent.children['place-name'].textContent = place.name;
        infowindowContent.children['place-address'].textContent = address;
        infowindow.open(map, marker);
        var origin = new google.maps.LatLng(-43.506794, 172.728603);
        var dest = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng())
        //See how far it is and the duration of the trip
        var service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix({
            origins: [origin],
            destinations: [dest],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC, //google.maps.UnitSystem.IMPERIAL
            avoidHighways: false,
            avoidTolls: false,
        }, callback);



        function callback(response, status) {
            if (status == 'OK') {
                var origins = response.originAddresses;
                var destinations = response.destinationAddresses;
                var siteDefault_ordertime = 10 //average current order time in minutes 
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
                var duration_min = 0
                if (n > -1) { //get hours as minutes
                    var subtract = 3;
                    if (n == 2) {
                        subtract = 2;
                    }
                    var duration_hours = duration.substr(n - subtract, 2);
                    var duration_min = parseInt(duration_hours) * 60;
                }
                var n = duration.indexOf("min");
                if (n == 2) {
                    n = 3;
                }
                //get the 3 digits before min they will be 2 char 1 space or 2 space 1 char then parseint form that.
                var duration_mins = duration.substr(n - 3, 2);
                duration_min = duration_min + parseInt(duration_mins);
                var dist=parseFloat(distance);
                var rate=parseFloat(document.getElementById("km_delivery_rate").value);
                var surcharge=parseFloat(document.getElementById("delivery_surchage").value);
                var delivery_cost=dist*rate+surcharge;
                delivery_cost=delivery_cost.toFixed(2);
                var eta_minutes = parseInt(siteDefault_ordertime) + duration_min;
                $("#result").html("<b>Ordering from:</b>" + from + ".<br><b>Delivering to</b> " + to + ".<br><b>Distance:</b> " + distance + "<br><b>Time to Travel Time by car:</b> " + duration_min + " minutes. <br><b>Current Avg Order time:</b> " + siteDefault_ordertime + " minutes." + "<br><b>Expected delivery time:</b> " + eta_minutes.toString() + " minutes."+ "<br><b>Delivery:</b> " + delivery_cost + " ");
                document.getElementById("car_time").value = duration_min;
                document.getElementById("order_time").value = siteDefault_ordertime;
                document.getElementById("eta").value = eta_minutes;
                document.getElementById("delivery_price").value = delivery_cost;
                document.getElementById("delivery_price_show").value = delivery_cost;
                refreshTotal();
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

$( "#delivery" ).click(function() {
  $("#map_code").show();
  $("#delivery_row").show();
  $("#pac-input").focus();
});
$( "#eat_in" ).click(function() {
  $("#map_code").hide();
  $("#delivery_row").hide();
  $("#dana_options").show();
});
$( "#takeaway" ).click(function() {
  $("#map_code").hide();
  $("#delivery_row").hide();
  $("#dana_options").show(500);
});

function refreshTotal()
{
	var total=0;
	//loop through all lines and calculate the cost of meals and delivery etc.
	var items = document.getElementsByClassName("line_price");
	for (var i = 0; i < items.length; i++) {
	   total=total+parseFloat(items.item(i).innerText);
	}

	//add shipping cost
	 total=total+parseFloat($("#delivery_price").val());
	 total=total.toFixed(2);
	 $("#delivery_price_show").html($("#delivery_price").val());
	 $("#total_price").html(total);
	 //update total

}

function add_address()
{
//so check to see that the fields are populated if they are add the address
if (!document.getElementById("street_number").value=="")
  {
    var url="/admin/ajax/save.php?";
    var post=$("#eat_me").serialize();
    $.post(url,post,function(data) 
        {$("#result").html(data);}
        )
    //add cost of delivery to the page
    $("#dana_options_delivery").show(500);
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

<!--This is autocomplete finish-->


<div id="dana_options_delivery" class="row row-centered hiddenrow" style="margin-top: 20px;margin-bottom: 20px;min-width: 400px">  
	<div class="col-">
		<a style="width:150px;" class="button eatme primary" href="/contribute/">Payment Options</a>
	</div>
</div>


<div class="row row-centered">  	 
    	<?php 
    	if ($_SESSION["dine_in"])
    	{
	    	//get $table $names;
	    	
	    	$sSQL="SELECT id_table,name FROM serving_tables order by id_table ASC;";
	    	$result2 = $conn2->query($sSQL) or die($conn2->error);
	    	$itbl=0;
	    	$s=$s.'<div class="col-"><b>Select Table</b></div>';
	    	while($row2 = $result2->fetch_assoc())
	    	{
	    		//make table buttons'
	    		$itbl=$itbl+1;
	    		$s=$s.'<div class="col-"><a style="min-width:70px;" class="button btable primary" href="/loveyourfood.php?tbl='.$row2["id_table"].'">&nbsp; '.$row2["name"].'&nbsp; </a></div>';
	    		if ($itbl % 12==0) 
	    			{echo '</div><div class="row row-centered">';}
	    		echo $s;
					$s="";
	    	}
    	}
    	?>
</div>
<div class="row">

    <div class="col- style="margin-top:10px">

      <p style="color:#999">If you have finished everything on the list above your vitamin and mineral consumption will be approximately that in the graph below. Note this is only to be used as a guide not a definitive reference.  For more information including health benefits and toxcity risks click on each vitamin listed.</p>
    </div>
	<div class="col-" style="width: 100%;margin-left: auto;margin-right: auto;">

     	<table style="border: 0px solid #000; ">
     	<?php
		echo '<tr style="height:40px;border-top:0px solid #000;border-bottom:0px solid #000;"><td style="white-space:nowrap;"><b>Vitamin / Mineral</b></td><td class="small-graph-name" align="center" ><b>(%)</b></td><td align="center"><b>Recommended Daily Intake. (100% is the goal for each mineral and vitamin)</b></td></tr>';
		//Graph added 7/09/2015 Dan.
		$sSQL="CALL Recipe_Vitamins_cache (".$id_recipe.");";
		//echo $sSQL."<br>";
		$result2 = $conn2->query($sSQL) or die($conn2->error);
		$sSQL="CALL Recipe_Vitamins_people_eat (".$iMeal.");";
		//echo $sSQL;
		$result2 = $conn2->query($sSQL) or die($conn2->error);						         
     	while($row2 = $result2->fetch_assoc())
     	{
     		$width=cint($row2["net_RDI"]);
     		if ($width>200) {$width=195;}
     		echo '<tr><td align="right"><a href="/vitamin.php?v='.$row2["id_vitamin"].'">'.$row2["name"].' &nbsp;</a></td><td class="small-graph-name">('.round($row2["net_RDI"],0).')</td><td style="width:100%"><a title="'.round($row2["net_RDI"],0).'% of your Recommended Daily Intake" href="/vitamin.php?v='.$row2["id_vitamin"].'"><div class="small-graph-line"  style="width:'.round(($width/2),0).'%;background-color:'.$row2["color"].'">&nbsp;</div></a></td></tr>';
     	}
     	?>
     </table>
	</div>
</div>
<div class="row">
	<div class="col-sm-12 col-xs-12" >
		*Note above percentages are based on USDA Figures, effects of cooking and juicing will affect these figures, use as a guideline only. For slow juicing Typically 20-30 percent is lost and 80-90% of the fibre is lost.
	</div>
</div>
</div>
</form>
<script>
function Delete_Recipe(d)
{location.href="/loveyourfood.php?d="+d}
</script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<?php include 'footer.htm';?>





