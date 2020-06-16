	</div>
	</div>
</div>	
	
           
<div class="navbar" id="bottomnav" style="width:100%;margin-top:20px;">     
	<div id="page">
		<div id="footer">
			<div class='wsite-elements wsite-footer'>
				<div class="paragraph" style="text-align:center;"><p style="font-color:white;"></p><?=$_SESSION["restaurant_name"]?>
					<br /><?=$_SESSION["address_1"]?>, ><?=$_SESSION["address_2"]?>, ><?=$_SESSION["city"]?>, New Zealand
				</div>
			</div>
		</div>
	</div>
</div>
	





</body>
<?php 
//echo $sScriptName;
//exit;
if ($sScriptName=="ADD_RECIPE.PHP" ||  $sScriptName=="ADD_FOOD.PHP" || $sScriptName=="ME.PHP") 
  {
?>
<script>
$(".editor").jqte();
</script>
<?php
	}
?>
</html>