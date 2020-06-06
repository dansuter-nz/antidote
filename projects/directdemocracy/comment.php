<?php
$_SESSION["redirect_to"]="/projects/directdemocracy/comment.php?c=".urlencode($_GET["c"]);
session_start();
if ($_SESSION["id_people"]=="") 
	{header ("Location: /login.php");
	exit;}

include_once("/var/www/html/antidote_apache/header.php");
include_once("/var/www/html/antidote_apache/projects/position.php");

?>
<form name="comments" action="comment.php?c=<?=urlencode($_GET["c"])?>" method="post">
<div class="row row-centered">
	<div class="col-xs-12">
<?php
$alert="";
if ($_POST["t"]!="") 
	{
	$conn=open_conn();
	//submitting a comment
	$y=2018;
	$t=str_replace("'","''",$_POST["t"]);
	//$t=str_replace(chr(13),"<br>",$t);
	$p=$_SESSION["id_people"];
	$c=$_POST["c"];
	$e=$_POST["e"];
	$sSQL="CALL adds.P_Ins_Comment ('".$t."',".$c.",".$p.",".$y.",".$e.")";
	//echo "comment inserted ".$sSQL;
	$result = $conn->query($sSQL) or die($conn->error);
	while($row = $result->fetch_row())
	{$alert="<tr><td>!".$rsTemp[0]."!</td></tr>";}
	$result->close();
	}

$sYear=2018;
$irow=0;
$sTitle="Spending by Department ".$sYear;
$sOby="department asc";
mysqli_free_result($result);
if ($_GET["c"]!="") 
	{
	$sOby=" sum(amount) desc";
	$sSQL = "CALL adds.getCategorySpendingbyID ('".$_GET["c"]."',".$sYear.")";
	//echo $sSQL;
	//exit;
	$conn=open_conn();
	$result = $conn->query($sSQL) or die($conn->error);

	if (!mysqli_num_rows($result)==0)
	 	{
	 	$rstemp = $result->fetch_assoc();
		$h='';
		$h=$h.'<input type="hidden" value="'.$_GET["c"].'" id="c" name="c">';
		$h=$h.'<input type="hidden" value="0" id="e" name="e">';
		$h=$h."<h1>Our spending by '".$rstemp["CategoryName"]."' for ".$sYear."</h1>";
		$h=$h.'<table><tr class="trHead"><td>Category and ';
		$h=$h." Current Scope</td>";
		$h=$h."<td>Details of this category</td></tr>";
		$irow=0;	 
	 	} 
	while($rstemp = $result->fetch_assoc())
		{
		if ($irow % 2==1) {$strClass="light_blue_row";} else { $strClass="white_row";}
		$irow=$irow+1;
		$sCat="";
		if (! is_null($rstemp["CategoryName"])) 
			{$sCat=$rstemp["CategoryName"];}
		$h=$h.'<tr><td style="width:50%;vertical-align:text-top;"><div style="text-align:left;"><a href="now.php?c='.$_GET["c"].'">'.$sCat."</a>";
		$h=$h."".$rstemp["Current_Scope"]."</div></td>\n";
		$h=$h.'<td style="width:50%;vertical-align:text-top;"><b>Classification: </b>'.$rstemp["Functional_Classification"]."<br>\n";
		$h=$h."<b>Amount Type:</b> ".$rstemp["amount_type"]."<br>\n";
		$h=$h."<b>Restrictions: </b>".$rstemp["Restriction_Type"]."<br>\n";
		$h=$h."<b>Group Type: </b>".$rstemp["group_type"]."<br>\n";
		$h=$h."<b>Reason Name: </b>".$rstemp["AppropriationName"]."<br>\n";
		$h=$h."<b>Reason Category: </b>".$rstemp["Appropriation_or_Category_Type"]."<br>\n";
		$h=$h."<b>Owned By: </b>".$rstemp["Portfolio_Name"]."<br>";
		$h=$h.'<span class="blackBold">Total Budget $'.number_format($rstemp["total_budget"],0).",000</span></td></tr>\n";
		if ($irow==1) 
			{
			$c='<tr><td colspan="2" style="width:50%;vertical-align:text-top;">'."\n";
			$c=$c.'<div style="text-align:left;margin-top:20px;margin-bottom:20px;">This is your chance to have your say on '.$sCat.'\'s spending of <span class="blackBold">'."\n";
			$c=$c."$".number_format($rstemp["total_budget"],0).',000.</span></div><div style="text-align:left;margin-top:20px;margin-bottom:20px;">Comments <b>'.$rstemp["Comments"]."</b> Votes <b>".$rstemp["Votes"]."</b></div></td></tr>\n ";
			}
		}
	}
$h=$h.$c;
$h=$h.$alert;
$h=$h.'<tr><td colspan="2">';
if ($_SESSION["image_path"]=="") {
	$sImage='<a href="http://www.antidote.org.nz/login.php">Login</a>';
} else {
	$sImage='<img alt="person image" src="'.str_replace("/med/","/thumb/",$_SESSION["image_path"]).'">';
}
$h=$h.'<div class="row row-centered">';
$h=$h.'<div class="col-md-2 text-center">'.$sImage."</div>";
$h=$h.'<div class="col-md-8 text-center"><textarea id="t" name="t" placeholder="Make your comment here." style="height: 150px;width:100%;"></textarea></div>';
$h=$h.'<div class="col-md-2" id="dvSubmit" style=""><input name="submit" id="submit" value="Comment" type="submit" style=""></div>';
$h=$h."</div>";
$h=$h."</td></tr>";
$sSQL = "CALL adds.getCommentsByCategoryID ('".$_GET["c"]."')";
echo $sSQL;
mysqli_free_result($result);
$conn=open_conn();
$result = $conn->query($sSQL) or die($conn->error);
if (!mysqli_num_rows($result)==0)
 	{
	while($rstemp = $result->fetch_assoc())
		{
		$h=$h."<tr><td colspan='2'><div class='row row-centered'>\n";
		$h=$h.'<div class="col-md-2 text-center"><img alt="photo" src="'.str_replace("/med/","/thumb/",$rstemp["image_path"]).'"></div>'."\n";
		$dAdd=$rstemp["date_added"];
		if (left($dAdd,6)=="0 days") { 
			$dAdd=right($dAdd,strlen($dAdd)-7);
		} else {
			$dAdd=left($dAdd,6);
		}
		if (left($dAdd,7)=="0 hours") { 
			$dAdd=right($dAdd,strlen($dAdd)-8);
		} else {
			$dAdd=left($dAdd,7);
		}
		if (left($dAdd,6)=="0 hours") {$dAdd=right($dAdd,strlen($dAdd)-7);}	
		$h=$h.'<div class="col-md-8">'."\n";
		$h=$h.'<div class="row row-centered">'."\n";
		$h=$h.'	<div class="col-md-12 commentedBy" >'.$rstemp["name"]." ".$dAdd." ago</div>\n ";
		$h=$h.'	<div id="c'.$rstemp["id_comment"].'" class="col-md-12 bgCommentText">'.$rstemp["text"]."</div>\n";
		if ($_SESSION["id_people"]==$rstemp["id_people"]) {
			$h=$h.'	<div class="col-md-12"><a href="#" onclick="return editText('.$rstemp["id_comment"].')">Edit</a></div>'."\n";
		}
		$h=$h."</div>\n";
		$h=$h."</div>\n";
		$h=$h.'<div class="col-md-2 text-center nowrap" >'."\n";
		$h=$h."<div class='vote'>\n";
		$h=$h."<a class='vote-up-off' title='This question shows research effort; it is useful and clear' onclick='vote(".$rstemp["id_comment"].",1)'>up vote</a>\n";
		if (is_null($rstemp["Votes"])) {
			$iVote=0;
		} else {
			$iVote=$rstemp["Votes"];
		}
		$h=$h."<span class='upvoteCount vote-count-post'>".$iVote."</span>\n";
		$h=$h.'<a class="vote-down-off" title="This question does not show any research effort; it is unclear or not useful" onclick="vote('.$rstemp["id_comment"].',-1)">down vote</a>'."\n";
		$h=$h."</div>\n";
		$h=$h."</div>\n</div>\n</td>\n</tr>\n";
		}
			
 	} 
//rw(h)
$h=$h."</table>";
echo $h;
//rwb(sSQL)

?>		
	</div>
</div>
</form>
</div>
 <script>
  CKEDITOR.replace('t');
</script>
<hr>
<div class="row row-centered">
	<div class="col-md-12 text-center" >
		<img alt="be the change" src="/images/socrates_change.jpg">
	</div>
</div>		
<br>

