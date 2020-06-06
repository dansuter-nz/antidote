
<!--#include virtual="/connection.asp"-->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/header.asp" -->

<div class="row row-centered">
	<div class="col-xs-12">
<%
x=rwe("")
if isnumeric(request("r")) and not request("r")="" then
	rows=request("r")
else
	rows=100
end if
h="S"
sSQL = "[ripdata].[dbo].Get_voting_rankings ,"&rows
rwb(sSQl)
x=openRS(sSQL)
irow=0
if not rsTemp.eof then
	'get first row and make a header comment 
	h="<h1>People ranked by most total positive votes</h1>"
	'check to see if login is admin?
	if session("can_authorize") then
		h=h&"<form id=""myForm"" action=""/wisdom/crowd.asp"" method=""post""><input type=""hidden"" name=""a"" id=""a"" value="""&article&"""><div class=""row""><div class=""col-sm-11""><textarea cols=""125"" rows=""8"" name=""ac"" id=""ac"">"&rsTemp("Antidote_comment")&"</textarea></div><div class=""col-sm-1""> <input type=""button"" id=""updateAdminComment"" value=""Update"" onclick=""updateComment()""></div></form>"
		%>
	
    <%
	else
		if not isnull(rsTemp("Antidote_comment")) then
			h=h&"<h3><b>Antidote Commentary.</b> "&rsTemp("Antidote_comment")&"</h3>"
		end if
	end if
	h=h&"<table>"	
	h=h&"<tr class=""trHead"">"
	h=h&"<td>Name</td>"
	h=h&"<td>Rank #</td>"
	h=h&"<td>Total</td>"
	h=h&"<td>Positive</td>"
	h=h&"<td>Negative</td>"
	h=h&"<td>Ratio</td></tr>"
	do until rsTemp.eof
		h=h&"<tr><td colspan=""5""><a href=""http://www.antidote.org.nz/wisdom/crowd.asp?n="&rstemp("name")&""">"&rstemp("name")&"</td></tr>"
		h=h&"<td class=""tdRight bold red"">"&rstemp("oRank")&"</td>"
		h=h&"<td class=""tdRight bold"">"&rstemp("tvotes")&"</td>"
		h=h&"<td class=""tdRight bold green"">"&rstemp("pvotes")&"</td>"
		h=h&"<td class=""tdRight bold red"">"&rstemp("nvotes")&"</td>"
		h=h&"<td class=""bold"">"&rstemp("ratio")&"</td></tr>"
		rsTemp.movenext
	loop
	h=h&"</table>"
	h=h&"<br>Showing last <input style=""text-align:right;font-weight:bold;"" size=""3"" id=""topC"" value="""&rows&"""><input type=""hidden"" id=""article"" value="""&article&"""> answers. need more? <input type=""button"" id=""upBtn"" value=""Update"" onclick=""updateRows();""><br>"
end if
rw(h)
%>		
	</div>
</div>

<hr>
<div class="row row-centered">
	<div class="col-md-12 text-center" >
		<img src="/images/socrates_change.jpg">
	</div>
</div>		
</br>
<!--#include virtual="/footer.asp" -->
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
.trHead {background-color:#3B5998;color:#fff;font-size:12px;font-weight:bold;text-align:center;width:100%;border: 1px solid #ccc;white-space:nowrap;}
.tdRight {text-align:right;font-size:10px;font-weight:normal;} 
.nowrap {white-space:nowrap;}
</style>
<script>
function updateRows()
{
	var r=document.getElementById("topC").value;
	var a=document.getElementById("article").value;
	location.href="/wisdom/crowd.asp?a="+a+"&r="+r	;
}
<%if session("can_authorize") then%>
function updateComment()
{
	for ( instance in CKEDITOR.instances )
	    CKEDITOR.instances[instance].updateElement();
	var c=document.getElementById("ac").value;
	var a=document.getElementById("article").value;
	//alert (encodeURI(c))
	//document.location="crowd.asp?a="+a+"&c="+c;
	document.getElementById("myForm").submit();
}
<%end if%>
</script>
<script src="/files/ckeditor/ckeditor.js"></script>
<script>
CKEDITOR.replace("ac");
</script>