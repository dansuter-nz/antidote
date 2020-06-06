<?php
if(!isset($_SESSION)) { session_start();} 
if ($_SESSION["id_people"]=="") 
	{header ("Location: /login.php");
	exit;}
define('root_dir',"/var/www/html/antidote_apache");
require(root_dir.'/header.php');
//echo "hello".isset($_SESSION["email"]);

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

if (!$_GET["p"]=="")
{
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
<div id="dana_options" style="align-content: center; margin-top: 20px;margin-bottom: 20px;">  
	<div class="row row-centered" style="width: 100%;">
		<div class="col-" style="width: 320px;margin-left:auto;margin-right: auto;">
			
				<h2 >How would you like to contribute for your meal?</h2>
			
			<ul  style="list-style: none; ">
				<li>
					<a  style="width:150px;margin: 10px 30px;" class="button contribute_dana" href="/contribute/?p=1&t=3">Dana</a>	
				</li>
				<li>
					<a style="width:150px;margin: 10px 30px;" class="button contribute_cash" href="/contribute/?p=2&t=2">Cash on Delivery</a>
				</li>
				<li>
					<a style="width:150px;margin: 10px 30px;" class="button contribute_eftpos_disabled" href="/contribute/?p=3&t=1">Mobile Eftpos</a>
				</li>
				<li>
					<a style="width:150px;margin: 10px 30px;" class="button contribute_cconline " href="/contribute/stripe">Credit Card Online</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<?php 
}
else
{
// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
?>

<script src="https://js.stripe.com/v3/"></script>

<div id="stripe_pay" style="width:400px; margin-right: 50%;margin-left: 50%">
	<form action="/contribute/onlinecc.php" method="post" id="payment-form">
	<div id="card-element">
	  <!-- Elements will create input elements here -->
	</div>

	<!-- We'll put the error messages in this element -->
	<div id="card-errors" role="alert"></div>

	<button id="card-button" data-secret="<?= $intent->client_secret ?>">
	    Pay
	</button>
	</form>
</div>

<?php
}
?>

 

