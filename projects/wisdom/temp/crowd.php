<?php
session_start();
$_SESSION["redirect_to"]=$_SERVER['HTTP_REFERER'];
//check to see if logged in if not redirect
$path="/var/www/html/antidote_apache";
$path .= "/header.php";
include_once($path);
$conn=open_conn();
$conn2=open_conn();
$stime=date("Y-m-d h:i:sa");
if ($_SESSION["menuposition"]=="" || instr($_SESSION["menuposition"],"/wisdom/crowd.php")==0) {
	$_SESSION["menuposition"]='<a href="/projects.php">Projects</a> - <a href="/projects/wisdom/crowd.php">Wisdom of the Crowd </a> ';
}
if ($_GET["f"]!="") {
	$_SESSION["menuposition"]='<a href="/projects.php">Projects</a> - <a href="/projects/wisdom/crowd.php">Wisdom of the Crowd </a> - <a href="/projects/wisdom/crowd.php?f='.$_GET["f"].'">'.strtoupper($_GET["f"])."</a>";
	if ($_GET["f1"]!="") {
		$_SESSION["menuposition"]='<a href="/projects.php">Projects</a> - <a href="/projects/wisdom/crowd.php">Wisdom of the Crowd </a> - <a href="/projects/wisdom/crowd.php?f='.$_GET["f"].'">'.strtoupper($_GET["f"]).'</a> - <a href="/projects/wisdom/crowd.php?f='.$_GET["f"]."&f1=".$_GET["f1"].'">'.$strtoupper($_GET["f1"])."</a>";
	}
} else {
	if ($_GET["a"]=="") {
		$_SESSION["menuposition"]='<a href="/projects.php">Projects</a> - <a href="/projects/wisdom/crowd.php">Wisdom of the Crowd </a> ';
	}
}?>
<!--#include virtual="/projects/position.php" -->
<div class="row row-centered">
	<div class="col-xs-12">
<?php
if ($_GET["ac"]!="") {
	
	$sSQL = "call adds.updateComments (".$_GET["a"].",'".$_GET["ac"]."')";
	//x=rwb(sSQL)
	$result = $conn->query($sSQL) or die($conn->error);
}
if (is_numeric($_GET["r"]) && ! $_GET["r"]=="") 
{
	$rows=$_GET["r"];
} else {
	$rows=20;
}
if (! $_GET["a"]=="") {
	if (is_numeric($_GET["a"])) {
		$article=$_GET["a"];
	} else {
		$rwe["invalid article id"];
	}
	
	$sSQL = "call adds.Get_results_by_ID (".$_GET["a"].",".$rows.")";
	//rwe(sSQL)
	$result = $conn->query($sSQL) or die($conn->error); 
	$sSql="";
	$irow=0;
	 if (mysqli_num_rows($result)>0)  
	 	{
	 	$row = $result -> fetch_row();
		//get first row and make a header comment 
		$h='<h1>Headline: <a href="'.$row["streamURL"].'">'.$row["streamTitle"]."</a></h1>";
		$h=$h.'<div class="row row-centered">';
		//if row("has_img") then
			$h=$h.'<div class="col-md-6 text-center"><img src="images/webimgs/stuff/'.$row["id"].'.jpg"></div>';
		//end if
		$h=$h.'<div class="col-md-6 text-center"><h3>In total '.$row["commentCount"]." comments were made and ".$row["vote_count"]." votes were cast.  So what is the crowd saying?</h3></div>";
		$h=$h."</div>";
		//check to see if login is admin?
		if ($_SESSION["can_authorize"]) 
			{
			$h=$h.'<form id="myForm" action="/projects/wisdom/crowd.php" method="post"><input type="hidden" name="a" id="a" value="'.$article.'"><div class="row"><div class="col-sm-11"><textarea cols="125" rows="8" name="ac" id="ac">'.$row["Antidote_comment"].'</textarea></div><div class="col-sm-1"> <input type="button" id="updateAdminComment" value="Update" onclick="updateComment()"></div></form>';
			} 
		else 
			{
				if (!$row["Antidote_comment"]) {
				$h=$h."<h3>Antidote Commentary</h3> <i>".$row["Antidote_comment"]."</i>";
			}
		}
		$h=$h."<table>";	
		$h=$h.'<tr><td colspan="2">';
		if ($_SESSION["image_path"]=="") {
			$sImage='<a href="http://www.antidote.org.nz/login.php">Login</a>';
		} else {
			$sImage='<img src="'.str_replace("/med/","/thumb/",$_SESSION["image_path"]).'">';
			$h=$h.'<div class="row row-centered"><div class="col-md-2 text-center">'.$sImage."</div>";
			$h=$h.'<div class="col-md-8 text-center"><textarea id="t" name="t" placeholder="Make your comment here." style="height: 150px;width:100%;"></textarea></div>';
			$h=$h.'<div class="col-md-2" id="dvSubmit" style=""><div class="row row-centered"><input name="submit" id="submit" value="Comment" type="submit" style=""></div></div>';
		}
	
		$h=$h."</div>";
		$h=$h."</td></tr>";
		
		$h=$h.'<tr class="trHead"><td>Comment </td>';
		$h=$h."<td>Total</td>";
		$h=$h."<td>Positive</td>";
		$h=$h."<td>Negative</td>";
		$h=$h."<td>Name</td></tr>";
	 	while($row = $result->fetch_row())
	 	{

			$h=$h."<tr><td>".$row["commentText"]."</td>";
			$h=$h.'<td class="tdRight bold">'.$row["totalVotes"]."</td>";
			$h=$h.'<td class="tdRight bold green">'.$row["posVotes"]."</td>";
			$h=$h.'<td class="tdRight bold red">'.$row["negVotes"]."</td>";
			$h=$h.'<td class="bold"><a href="/projects/wisdom/crowd.php?n='.$row["name"].'">'.$row["name"]."</a></td></tr>";
		}
		$h=$h."</table>";
		$h=$h.'<br>Showing last <input style="text-align:right;font-weight:bold;" size="3" id="topC" value="'.$rows.'"><input type="hidden" id="article" value="'.$article.'"> answers. need more? <input type="button" id="upBtn" value="Update" onclick="updateRows();"><br>';
		
		$sFoot="<script>CKEDITOR.replace('t');</script>";
	}
	echo $h;
} 
else 
{
	if ($_GET["n"]=="") 
	{
		?>
		<div class="row">
			<div class="col-sm-7">
				<span class="Span_Header">
				Our wisdom of the crowds project can be simply defined as being, "The potential of Crowd intelligence is vastly underultilized in our democratic system"
				One could argue to be truely democratic we should be harnessing the crowd intelligence or at least consulting this crowd for all major and important decisions.
				Using a system like the one built here demonstrates we do not need to spend tens of millions on referendums to get a democtratic answer to the common problems we face.
				Further reading:<br>
				<a href="https://en.wikipedia.org/wiki/Wisdom_of_the_crowd">https://en.wikipedia.org/wiki/Wisdom_of_the_crowd</a><br>

			</span>
			</div>
			<div class="col-sm-5">
				<iframe width="320" height="180" src="https://www.youtube.com/embed/iOucwX7Z1HU" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<?php
				$conn=open_conn(); 
				$sSQL="call adds.P_Sel_Get_stats ();";
				$result = $conn->query($sSQL) or die($conn->error); 
				$irow=0;
				if (mysqli_num_rows($result)>0) 
				{ 
					?>		
					Currently tracking <b><?=number_format($row["articles"],0)?></b> news articles. There have been <b><?=number_format($row["comments"],0)?></b> comments made and a total of <b><?=number_format($row["total_Votes"],0)?></b> votes cast on these comments as from 25th May 2018.  
				<?php
				}
				?>
			</div>
		</div>
	
				<form action="crowd.php" method="get">
			<div class="row">
				<div class="col-xs-4" >	
					<input name="s" id="s" type="text" value="<?=$_GET["s"]?>" placeholder="Search articles and comments.." class="searchBar">  
				</div>
				<div class="col-xs-2" >
				
				<input type="submit" name="sub" id="sub" value="Search">
				</div>
				<?php
				$conn=open_conn(); 
				$sSQL="call adds.P_Sel_Get_Filters ('','')";
				$result = $conn->query($sSQL) or die($conn->error);
				$irow=0;
				if (mysqli_num_rows($result)>0)  
				{
					?>	
				<div class="col-xs-6 alignRight" >
					<select  class="dropfilter" id="f" name="f" onchange="filter(this.value,'','')">
					<?php
					$h=$h.'<option value="">No Filter</option>';
					while($row = $result->fetch_row())
						if (!is_null($row[1])) {
							$h=$h.'<option value="'.$row[1].'"';
							if ($_GET["f"]==$row[1] && ! $_GET["f"]=="") {$h=$h." selected";}
							$h=$h.">".$row[1]." (".$row[0].")</option>";
						}
					echo $h;
				}
				$h="";
					?>
					</select>
					<select class="dropfilter" id="f1" name="f1" onchange="filter('',this.value,'')">
				<?php
				$conn=open_conn(); 
				$h=$h.'<option value="">No Filter</option>';
				if (! $_GET["f"]=="") {
					//x=rwe("")
					$sSQL="call adds.P_Sel_Get_Filters ('".$_GET["f"]."','');";
					//rwe(sSQL)
					$result = $conn->query($sSQL) or die($conn->error);
					$irow=0;
					if (mysqli_num_rows($result)>0) {
						while($row = $result->fetch_row());
						if (!is_null($row[1])) {
							$h=$h.'<option value="'.$row[1].'"';
							if ($_GET["f1"]==$row[1] && ! $_GET["f1"]=="") {$h=$h." selected";}
							$h=$h.">".$row[1]." (".$row[0].")</option>";
						}
						
					}					
				}
				echo $h;
				$h="";
					?>		
					</select>
					<select class="dropfilter" id="f2" name="f2" onchange="filter('','',this.value)">
				<?php
				$h=$h.'<option value="">No Filter</option>';
				if (! $_GET["f1"]=="") {
					$sSQL="call adds.P_Sel_Get_Filters ('".$_GET["f"]."','".$_GET["f1"]."')";
					$result = $conn->query($sSQL) or die($conn->error);
					$irow=0;
					if (mysqli_num_rows($result)>0)  {
						$h=$h.'<option value=""></option>';
						while($row = $result->fetch_row());
						if (! isnull($row[1])) {
							$h=$h.'<option value="'.$row[1].'"';
							if ($_GET["f2"]==$row[1] && ! $_GET["f2"]=="") {$h=$h." selected";}
							$h=$h.">".$row[1]." (".$row[0].")</option>";
						}
					}					
				}
				echo $h;
				$h="";
					?>		
					</select>	
				</form>				
			</div>
		</div>
		<?php
 		$sSQL = "Select ID,streamID,streamTitle,status,streamURL,categoryID,createdate,date_add,date_updated,commentCount,threadCount,vote_count,has_comments ";
		$sSQL=$sSQL." FROM adds.stuff_articles where length(streamTitle)>0 ";
		if ($_GET["f"]!="") {
			$sSQL=$sSQL." and streamID like 'stuff/".$_GET["f"]."%'";
		}
		if ($_GET["f1"]!="") {
			$sSQL=$sSQL." and streamID like 'stuff/".$_GET["f"]."/".$_GET["f1"]."%'";
		}
		if ($_GET["f2"]!="") {
			$sSQL=$sSQL." and streamID like 'stuff/".$_GET["f"]."/".$_GET["f1"]."/".$_GET["f2"]."%'";
		}
		if ($_GET["s"]!="") {
			$sSQL=$sSQL." and streamTitle like '%".$_GET["s"]."%' or streamID like '%".$_GET["s"]."%'";
		}
		$sSQL=$sSQL."order by ";
		switch ($_GET["o"]) {
			case "":
			$sSQL=$sSQL."commentCount";
			break;
			case "c":
			$sSQL=$sSQL."commentCount";
			break;
			case "v":
			$sSQL=$sSQL."vote_count";		
			break;
			case "d":
			$sSQL=$sSQL."date_add";
			break;
		}
		if ($_GET["d"]=="a") {
			$sSQL=$sSQL." ASC ";
		} else {
			$sSQL=$sSQL." DESC ";
		}
		$sSQL=$sSQL." limit ".$rows.";";
		//rwe(sSQL)
		//echo $sSQL;
		//Select ID,streamID,streamTitle,status,streamURL,categoryID,createdate,date_add,date_updated,commentCount,threadCount,vote_count,has_comments FROM adds.stuff_articles where length(streamTitle)>0 and streamID like 'stuff/national%'order by commentCount DESC limit 20;
		$conn=open_conn();
		$result = $conn->query($sSQL) or die($conn->error);
		$irow=0;
		if (mysqli_num_rows($result)>0) {
			if ($irow % 2==1) 
		    	{$strClass="light_blue_row";} 
		 	else 
		    	{ $strClass="white_row";}
			$h='<table id="tblComments">';	
			$h=$h.'<tr class="trHead">';
			$h=$h."<td class='pl10'> Article titile</td>";
			if ($_GET["d"]=="" || $_GET["d"]=="a") {
				$h=$h.'<td><a class="pr10 pl10" href="/projects/wisdom/crowd.php?o=c&d=d">Comments</a></td>';
				$h=$h.'<td><a class="pr10 pl10" href="/projects/wisdom/crowd.php?o=v&d=d">Votes</td>';
				$h=$h.'<td><a class="pr10 pl10" href="/projects/wisdom/crowd.php?o=d&d=d">Date added</td></tr>';
			} else {
				$h=$h.'<td><a href="/projects/wisdom/crowd.php?o=c&d=a">Comments</a></td>';
				$h=$h.'<td><a href="/projects/wisdom/crowd.php?o=v&d=a">Votes</td>';
				$h=$h.'<td><a href="/projects/wisdom/crowd.php?o=d&d=a">Date added</td></tr>';	
			}	
			//rwe(h)
			while($row = $result->fetch_assoc())
				{
				if ($irow % 2==1) 
			    	{$strClass="light_blue_row";} 
			 	else 
			    	{ $strClass="white_row";}				
				$h=$h.'<tr class="'.$strClass.'"> <td><a href="http://www.antidote.org.nz/projects/wisdom/crowd.php?a='.$row["ID"].'">'.$row["streamTitle"]."</a></td>";
				$h=$h.'<td class="tdRight bold pr10 pl10">'.$row["commentCount"]."</td>";
				$h=$h.'<td class="tdRight bold pr10 pl10">'.$row["vote_count"]."</td>";
				$h=$h.'<td class="tdRight nowrap pr10 pl10">'.$row["date_add"]."</td></tr>";
		//x=rw(datediff("m",now(),stime)&" seconds")	
	 	// rwe(sSQL)
				if ($irow==-1) 
					{ 
					$sSQL = "call adds.Get_results_by_ID (".$row[0].",1)";
					$x=$rwb[$sSQL];
					$result = $conn->query($sSQL) or die($conn->error);

					while($row = $result->fetch_row())
						{
						$h=$h.'<tr><td></td><td colspan="4"><b>Top Comment from '.$rowA["name"]." who wrote:</b> ".$rowA["commentText"]."</td>";
						}
						
					}		
				$irow=$irow+1;
				}
			$h=$h."</table>";
		}
		
		
	
		
	} 
	else 
	{
		$h="S";
		$sSQL = "call adds.Get_results_by_name ('".$_GET["n"]."',".$rows.");";
		//rwe(sSQl)
		$result = $conn->query($sSQL) or die($conn->error);
		$irow=0;
		if (mysqli_num_rows($result)>0) 
			{

			//get first row and make a header comment 
			$h='<h1>User: <a href="/projects/wisdom/crowd.php?n='.$_GET["n"].'">'.$_GET["n"]."</a> Comments and Votes</h1>";
			$h=$h."<h2>In total ".$row["Total_Comments"].' comments. <span class="green">'.$row["Total_posVotes"].'</span> -  <span class="red">'.$row["Total_negVotes"]."</span> = ".'<span class="black">'.$row["Total_Votes"].'</span> (Overall Rank <a title="show rankings" href="/projects/wisdom/crowdranking.php">#'.$row["Overall_Rank"]." of ".$row["Total_people"].')</a> people who have commented.  So what is "'.$_GET["n"].'" saying?</h2>';
			//check to see if login is admin?
			if ($_SESSION["can_authorize"]) 
				{
					$h=$h.'<form id="myForm" action="/projects/wisdom/crowd.php" method="post"><input type="hidden" name="a" id="a" value="'.$article.'"><div class="row"><div class="col-sm-11"><textarea cols="125" rows="8" name="ac" id="ac">'.$row["Antidote_comment"].'</textarea></div><div class="col-sm-1"> <input type="button" id="updateAdminComment" value="Update" onclick="updateComment()"></div></form>';
					?>
				
			    <?php
				} 
			else 
				{

				if (! isnull($row["Antidote_comment"]))
					{
						$h=$h."<h3><b>Antidote Commentary.</b> ".$row["Antidote_comment"]."</h3>";
					}
				}
			$h=$h."<table>";	
			
			$h=$h.'<tr class="trHead">';
			$h=$h."<td>Comment</td>";
			$h=$h."<td>Total</td>";
			$h=$h."<td>Positive</td>";
			$h=$h."<td>Negative</td>";
			$h=$h."<td>Rank</td></tr>";
			while($row = $result->fetch_row())
				{
				$h=$h.'<tr><td colspan="5"><a href="http://www.antidote.org.nz/projects/wisdom/crowd.php?a='.$row["id"].'">'.$row["streamTitle"]."</td></tr>";
				$h=$h."<tr><td>".$row["commentText"]."</td>";
				$h=$h.'<td class="tdRight bold">'.$row["totalVotes"]."</td>";
				$h=$h.'<td class="tdRight bold green">'.$row["posVotes"]."</td>";
				$h=$h.'<td class="tdRight bold red">'.$row["negVotes"]."</td>";
				$sSQL="call adds.get_rank_person_article('".$row["name"]."','".$row["streamID"]."',".$row["totalVotes"].")";
				$result = $conn2->query() or die($conn->error);
				while($row = $result->fetch_row())
					{
					$irank=$rowA[0];
					}

					$h=$h.'<td class="bold">'.$irank."</a>/".$row["commentCount"]."</td></tr>";		
				}
			$h=$h."</table>";
			
		}
	}
	$h=$h.'<br>Showing last <input style="text-align:right;font-weight:bold;" size="3" id="topC" value="'.$rows.'"><input type="hidden" id="article" value="'.$article.'"> answers. need more? <input type="button" id="upBtn" value="Update" onclick="updateRows();"><br>';
	
}
echo $h;
//x=rwe(sSQL)
?>		
	</div>
</div>
</div>
</div>
<hr>
<div class="row row-centered">
	<div class="col-md-12 text-center" >
		<img src="/images/socrates_change.jpg">
	</div>
</div>		
</br>
<!--#include virtual="/footer.php" -->
<style>

</style>
<?=$sFoot?>
<script>
function updateRows()
{
	var r=document.getElementById("topC").value;
	var a=document.getElementById("article").value;
	location.href="/projects/wisdom/crowd.php?a="+a+"&r="+r	;
}
<?php if ($_SESSION["can_authorize"]) {?>
function updateComment()
{
	for ( instance in CKEDITOR.instances )
	    CKEDITOR.instances[instance].updateElement();
	var c=document.getElementById("ac").value;
	var a=document.getElementById("article").value;
	//alert (encodeURI(c))
	//document.location="crowd.php?a="+a+"&c="+c;
	document.getElementById("myForm").submit();
}
<?php
}
?>
function filter(f,f1,f2)
{
	var url=window.location.href 
	if (url.indexOf("?")>0)
		{url=url+"&";}
	else
		{url=url+"?";}
	if (f!="")
		{//check to see if &f= is already in string if so truncate and build new string.
			if (url.indexOf("&f=")>0)
				{url=url.substring(0,url.indexOf("&f=")+1)}
			url=url+"f="+f;}
	if (f1!="")
		{		
			if (url.indexOf("&f1=")>0)
				{url=url.substring(0,url.indexOf("&f1=")+1)}
			url=url+"f1="+f1;
		}
	if (f2!="")
		{if (url.indexOf("&f2=")>0)
			{url=url.substring(0,url.indexOf("&f2=")+1)}
			url=url+"f2="+f2;
		}
	window.location.href =url;
}



</script>
<script src="/files/ckeditor/ckeditor.js"></script>
<script>

	
CKEDITOR.replace("ac");
CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] },
		{ name: 'tools', groups: [ 'tools' ] }
	];

	config.removeButtons = 'Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Strike,Subscript,Superscript,CopyFormatting,RemoveFormat,CreateDiv,ShowBlocks,Maximize,About,Flash,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Format,Styles,Font,FontSize';
};
</script>
