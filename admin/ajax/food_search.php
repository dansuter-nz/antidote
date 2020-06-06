<?php 
define('root_dir',$_SERVER["DOCUMENT_ROOT"]);
require(root_dir.'/admin/functions.php');
//get URL and get Recipe food item values	
echo make_food_rows($_GET["s"]);
?>