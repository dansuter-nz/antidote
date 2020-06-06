<?$spage=$_SERVER["PHP_SELF"]."?".Request.ServerVariables("query_string");
//spage="/projects/wisdom/crowd.asp?a="&request("a")
session("redirect_to")=$spage?>
<!-- #include virtual="/connection.asp"-->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/header.asp" -->
<?
if (request("d")!="") {
	session("menuposition")='<a href="/projects.asp">Projects</a> - <a href="/projects/directdemocracy/now.asp">Direct Democracy </a> - <a href="/projects/directdemocracy/now.asp?d='.request("d").'">'.request("d")."</a>";
} else {
	session("menuposition")='<a href="/projects.asp">Projects</a> - <a href="/projects/directdemocracy/now.asp">Direct Democracy </a>';
}?>
<!--#include virtual="/projects/position.asp" -->

<div class="row row-centered">
	<div class="col-xs-12">
		<span class="">
<?
 
if (request("y")!="") {
	if (is_numeric(request("y"))) {
		session("budget_year")=request("y");
	}
}
if (session("budget_year")=="") {session("budget_year")=2017;}
$sTitle='Spending by Department <a href="/projects/directdemocracy/now.asp?y='.session("budget_year").'">'.session("budget_year")."</a>";
$sOby="department asc";
if (request("d")!="") {
	$sOby=" sum(amount) desc";
	$sSQL = "CALL adds.getDepartmentSpendingbyCategory ('".request("d")."','".$sOby."',".session("budget_year").")";
	//rwe(sSQL)
	$x=$openRS[$sSQL];
	if (not $rsTemp.$eof) {
		$h="<h1>Our spending by '".request("d")."' for ".session("budget_year")."</h1>";
		$h=$h.'<table><tr class="trHead"><td>Category and ';
		$h=$h." Current Scope</td>";
		$h=$h."<td>Details of this category</td></tr>";
		$irow=0;
		do until $rsTemp.$eof; 
			if ($irow $mod 2==1) {$strClass="light_blue_row" } else { $strClass="white_row";}
			$irow=$irow+1;
			$sCat=$rstemp["categoryName"];
			$h=$h.'<tr><td style="width:50%;vertical-align:text-top;"><div style="text-align:top;min-height:135px"><a href="comment.asp?c='.$rstemp["id_category"].'">'.$sCat."</a>. ";
			$h=$h."".$rstemp["Current_Scope"].'</div><div style="text-align:bottom;">Comments <b>'.$rstemp["comments"]."</b> Votes <b>".$rstemp["votes"].'</b> <a href="comment.asp?c='.$rstemp["id_category"].'">Comment or Vote on this.</a></div></td>';
			$h=$h.'<td style="width:50%;vertical-align:text-top;"><b>Classification: </b>'.$rstemp["Functional_Classification"]."</br>";
			$h=$h."<b>Amount Type:</b> ".$rstemp["amount_type"]."</br>";
			$h=$h."<b>Restrictions: </b>".$rstemp["Restriction_Type"]."</br>";
			$h=$h."<b>Group Type: </b>".$rstemp["group_type"]."</br>";
			$h=$h."<b>Reason Name: </b>".$rstemp["AppropriationName"]."</br>";
			$h=$h."<b>Reason Category: </b>".$rstemp["Appropriation_or_Category_Type"]."</br>";
			$h=$h."<b>Owned By: </b>".$rstemp["Portfolio_Name"]."</br>";
			$h=$h.'<span class="blackBold">Total Budget $'.formatNumber($rstemp["total_budget"],0).",000</span></td></tr>";
			//itotal=itotal+int(rstemp("total_budget"))
			$rsTemp.$movenext;
		loop;
 
		
	}
} else {
	$sSQL = "SELECT department, count(*) 'spending categories',sum(amount) 'total_budget' FROM adds.budget_figures ";
	$sSQl=$sSQL." Where year=".session("budget_year")." ";
	if (request("f")!="") {
		$sSQl=$sSQL." and 1=1 ";
	}
	$sSQl=$sSQL." group by department order by ".$sOby;
	$x=$openRS[$sSQL];
	//x=rwe(sSQL)
	$inextyear=session("budget_year")+1;
	$ilastyear=session("budget_year")-1;
	$h='<h1>Our spending by department for the <a href="/projects/directdemocracy/now.asp?y='.$iLastYear.'"><<</a> '.session("budget_year").'</a> Year <a href="/projects/directdemocracy/now.asp?y='.$inextyear.'">>></a></h1>';
		$h=$h.'<h2>Data as from <a href="https://www.budget.govt.nz/budget/2018/data-library.htm">https://www.budget.govt.nz/budget/2018/data-library.htm</a></h2>';
		$h=$h."<h3>Please select the area of goverment that you are interested in by clicking on the department title.</h3>";
		$h=$h.'<table><tr class="trHead"><td>Department</td>';
		$h=$h."<td>Spending Categories</td>";
		$h=$h."<td>Total</td></tr>";
		$irow=0;
	if (not $rsTemp.$eof) {
		do until $rsTemp.$eof; 
			if ($irow $mod 2==1) {$strClass="light_blue_row" } else { $strClass="white_row";}
			$irow=$irow+1;
			$sDept=$rstemp["department"];
			$h=$h.'<tr><td><a href="now.asp?d='.server.$urlEncode[$sDept].'">';
			$h=$h.$sDept."</a></td>";
			$h=$h.'<td class="tdRight bold">'.$rstemp["spending categories"]."</td>";
			$h=$h.'<td class="tdRight bold">'.formatNumber($rstemp["total_budget"],0).",000</td></tr>";
			$itotal=$itotal+floor(str_replace(",","",$rstemp["total_budget"]));
			$icats=$icats+CINT($rstemp["spending categories"]);
			$rsTemp.$movenext;
		loop;
		$h=$h.'<tr><td style="width:50%;vertical-align:text-top;"><div><b>Total Spending</b>.</div><div style="text-align:bottom;"></td>';
		$h=$h.'<td class="tdRight bold"> '.$icats."</td>";
		$h=$h.'<td class="tdRight"><span class="blackBold ">** $'.formatNumber($itotal,0).",000</span></td></tr>";
	}
}
//rwb(sSQL)
$h=$h.'<tr><td colspan="3"><b>**</b> Data source <a href="https://www.budget.govt.nz/budget/excel/data/b18-expenditure-data.xls">https://www.budget.govt.nz/budget/excel/data/b18-expenditure-data.xls</a></td></tr>';
$h=$h."<table>";
$rw[$h];
 
?>		
		
		</span>
	</div>
</div>
<style>
table {
    color: black;
    text-align: left;
}
h1{font-size:14pt;font-weight:bold}
h2{font-size:13pt;font-weight:normal}
h3{font-size:12pt;font-weight:normal}
.bold{font-weight:bold}
.green{color:green;}
.red{color:red;}
.black{color:black;}
.blackBold{color:black;font-weight:bold;}
.Span_Header{font-size:11pt}
a{font-weight:bold;}
td{padding-right:10px;padding-left:10px;padding-top:3px;padding-bottom:3px;font-size:10pt}
tr{border: 1px solid #ccc}
textarea{font-size:10px}
.trHead {background-color:#63a504;color:#fff;font-size:13px;font-weight:bold;text-align:center;width:100%;border: 1px solid #ccc;white-space:nowrap;}
.tdRight {text-align:right;font-size:12px;font-weight:normal;} 
.nowrap {white-space:nowrap;}
.right {text-align:right;}


</style>

<hr>
<div class="row row-centered">
	<div class="col-md-12 text-center" >
		<img src="/images/socrates_change.jpg">
	</div>
</div>		
</br>
<!--#include virtual="/footer.asp" -->
