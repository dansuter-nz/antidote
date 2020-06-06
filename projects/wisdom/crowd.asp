<%
stime=now()
spage=Request.ServerVariables("script_name")&"?"&Request.ServerVariables("query_string")
'spage="/projects/wisdom/crowd.asp?a="&request("a")
session("redirect_to")=spage
%>

<!-- #include virtual="/connection.asp"-->
<!--#include virtual="/functions.asp" -->
<!--#include virtual="/header.asp" -->
<%
if session("menuposition")="" or instr(session("menuposition"),"/wisdom/crowd.asp")=0 then
	session("menuposition")="<a href=""/projects.asp"">Projects</a> - <a href=""/projects/wisdom/crowd.asp"">Wisdom of the Crowd </a> "
end if
if request("f")<>"" then
	session("menuposition")="<a href=""/projects.asp"">Projects</a> - <a href=""/projects/wisdom/crowd.asp"">Wisdom of the Crowd </a> - <a href=""/projects/wisdom/crowd.asp?f="&request("f")&""">"& capitalize(request("f"))&"</a>"
	if request("f1")<>"" then
		session("menuposition")="<a href=""/projects.asp"">Projects</a> - <a href=""/projects/wisdom/crowd.asp"">Wisdom of the Crowd </a> - <a href=""/projects/wisdom/crowd.asp?f="&request("f")&""">"&capitalize(request("f"))&"</a> - <a href=""/projects/wisdom/crowd.asp?f="&request("f")&"&f1="&request("f1")&""">"&capitalize(request("f1"))&"</a>"
	end if
else
	if request("a")="" then
		session("menuposition")="<a href=""/projects.asp"">Projects</a> - <a href=""/projects/wisdom/crowd.asp"">Wisdom of the Crowd </a> "
	end if
end if%>
<!--#include virtual="/projects/position.asp" -->
<div class="row row-centered">
	<div class="col-xs-12">
<%
if request("ac")<>"" then
	
	sSQL = "call adds.updateComments ("&request("a")&",'"&request("ac")&"')"
	'x=rwb(sSQL)
	openRS(sSQL)
	closeRS()
end if
if isnumeric(request("r")) and not request("r")="" then
	rows=request("r")
else
	rows=20
end if
if not request("a")="" then
	if isnumeric(request("a")) then
		article=request("a")
	else
		rwe("invalid article id")
	end if
	
	sSQL = "call adds.Get_results_by_ID ("&request("a")&","&rows&")"
	'rwe(sSQL)
	x=openRS(sSQL)
	sSql=""
	irow=0
	if not rsTemp.eof then
		'get first row and make a header comment 
		h="<h1>Headline: <a href="""&rsTemp("streamURL")&""">"&rsTemp("streamTitle")&"</a></h1>"
		h=h&"<div class=""row row-centered"">"
		'if rsTemp("has_img") then
			h=h&"<div class=""col-md-6 text-center""><img src=""images/webimgs/stuff/"&rsTemp("id")&".jpg""></div>"
		'end if
		h=h&"<div class=""col-md-6 text-center""><h3>In total "&rsTemp("commentCount")&" comments were made and "&rsTemp("vote_count")&" votes were cast.  So what is the crowd saying?</h3></div>"
		h=h&"</div>"
		'check to see if login is admin?
		if session("can_authorize") then
			h=h&"<form id=""myForm"" action=""/projects/wisdom/crowd.asp"" method=""post""><input type=""hidden"" name=""a"" id=""a"" value="""&article&"""><div class=""row""><div class=""col-sm-11""><textarea cols=""125"" rows=""8"" name=""ac"" id=""ac"">"&rsTemp("Antidote_comment")&"</textarea></div><div class=""col-sm-1""> <input type=""button"" id=""updateAdminComment"" value=""Update"" onclick=""updateComment()""></div></form>"
			%>
		
	    <%
		else
			if not isnull(rsTemp("Antidote_comment")) then
				h=h&"<h3>Antidote Commentary</h3> <i>"&rsTemp("Antidote_comment")&"</i>"
			end if
		end if
		h=h&"<table>"	
		h=h&"<tr><td colspan=""2"">"
		if session("image_path")="" then
			sImage="<a href=""http://www.antidote.org.nz/login.asp"">Login</a>"
		else
			sImage="<img src="""&replace(session("image_path"),"/med/","/thumb/")&""">"
			h=h&"<div class=""row row-centered""><div class=""col-md-2 text-center"">"&sImage&"</div>"
			h=h&"<div class=""col-md-8 text-center""><textarea id=""t"" name=""t"" placeholder=""Make your comment here."" style=""height: 150px;width:100%;""></textarea></div>"
			h=h&"<div class=""col-md-2"" id=""dvSubmit"" style=""""><div class=""row row-centered""><input name=""submit"" id=""submit"" value=""Comment"" type=""submit"" style=""""></div></div>"
		end if
	
		h=h&"</div>"
		h=h&"</td></tr>"
		
		h=h&"<tr class=""trHead""><td>Comment </td>"
		h=h&"<td>Total</td>"
		h=h&"<td>Positive</td>"
		h=h&"<td>Negative</td>"
		h=h&"<td>Name</td></tr>"
		do until rsTemp.eof
			h=h&"<tr><td>"&rstemp("commentText")&"</td>"
			h=h&"<td class=""tdRight bold"">"&rstemp("totalVotes")&"</td>"
			h=h&"<td class=""tdRight bold green"">"&rstemp("posVotes")&"</td>"
			h=h&"<td class=""tdRight bold red"">"&rstemp("negVotes")&"</td>"
			h=h&"<td class=""bold""><a href=""/projects/wisdom/crowd.asp?n="&rstemp("name")&""">"&rstemp("name")&"</a></td></tr>"
			rsTemp.movenext
		loop
		h=h&"</table>"
		h=h&"<br>Showing last <input style=""text-align:right;font-weight:bold;"" size=""3"" id=""topC"" value="""&rows&"""><input type=""hidden"" id=""article"" value="""&article&"""> answers. need more? <input type=""button"" id=""upBtn"" value=""Update"" onclick=""updateRows();""><br>"
		
		sFoot="<script>CKEDITOR.replace('t');</script>"
	end if
	rw(h)
else
	if request("n")="" then
		%>
		<div class="row">
			<div class="col-sm-7">
				<span class="Span_Header">
				Our wisdom of the crowds project can be simply defined as being, "The potential of Crowd intelligence is vastly underultilized in our democratic system"
				One could argue to be truely democratic we should be harnessing the crowd intelligence or at least consulting this crowd for all major and important decisions.
				Using a system like the one built here demonstrates we don't need to spend tens of millions on referendums to get a democtratic answer to the common problems we face.
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
				<%sSQL="call adds.P_Sel_Get_stats ();"
				x=openRS(sSQL)
				irow=0
				if not rsTemp.eof then%>		
			Currently tracking <b><%=formatnumber(rsTemp("articles"),0)%></b> news articles. There have been <b><%=formatnumber(rsTemp("comments"),0)%></b> comments made and a total of <b><%=formatnumber(rsTemp("total_Votes"),0)%></b> votes cast on these comments as from 25th May 2018.  
				<%end if%>
			</div>
		</div>
	<div class="row">
			
				
				<form action="crowd.asp" method="get">
				<div class="col-sm-3" >	
					<input name="s" id="s" type="text" value="<%=request("s")%>" placeholder="Search articles and comments.." class="searchBar">  
				</div>
				<div class="col-sm-2" >
				
				<input type="submit" name="sub" id="sub" value="Search">
				</div>
				<%sSQL="call adds.P_Sel_Get_Filters ('','')"
				
				x=openRS(sSQL)
				irow=0
				if not rsTemp.eof then%>	
				<div class="col-sm-7 alignRight" >
					<select  class="dropfilter" id="f" name="f" onchange="filter(this.value,'','')">
					<%
					h=h&"<option value="""">No Filter</option>"
					do until rsTemp.eof
						if not isnull(rsTemp(1)) then
							h=h&"<option value="""&rsTemp(1)&""""
							if request("f")=rsTemp(1) and not request("f")="" then h=h&" selected"
							h=h&">"&rsTemp(1)&" ("&rsTemp(0)&")</option>"
						end if
					rsTemp.movenext
					loop
					x=rw(h)
					x=closeRS()
				end if
				h=""
					%>
					</select>
					<select class="dropfilter" id="f1" name="f1" onchange="filter('',this.value,'')">
				<%
				h=h&"<option value="""">No Filter</option>"
				if not request("f")="" then
					'x=rwe("")
					sSQL="call adds.P_Sel_Get_Filters ('"&request("f")&"','');"
					'rwe(sSQL)
					x=openRS(sSQL)
					irow=0
					if not rsTemp.eof then
						do until rsTemp.eof
						if not isnull(rsTemp(1)) then
							h=h&"<option value="""&rsTemp(1)&""""
							if request("f1")=rsTemp(1) and not request("f1")="" then h=h&" selected"
							h=h&">"&rsTemp(1)&" ("&rsTemp(0)&")</option>"
						end if
						rsTemp.movenext
						loop
						
					end if					
				end if
				x=rw(h)
				h=""
					%>		
					</select>
					<select class="dropfilter" id="f2" name="f2" onchange="filter('','',this.value)">
				<%
				h=h&"<option value="""">No Filter</option>"
				if not request("f1")="" then
					sSQL="call adds.P_Sel_Get_Filters ('"&request("f")&"','"&request("f1")&"')"
					rw(sSQL)
					x=openRS(sSQL)
					irow=0
					if not rsTemp.eof then
						h=h&"<option value=""""></option>"
						do until rsTemp.eof
						if not isnull(rsTemp(1)) then
							h=h&"<option value="""&rsTemp(1)&""""
							if request("f2")=rsTemp(1) and not request("f2")="" then h=h&" selected"
							h=h&">"&rsTemp(1)&" ("&rsTemp(0)&")</option>"
						end if
						rsTemp.movenext
						loop
						
					end if					
				end if
				x=rw(h)
				h=""
					%>		
					</select>	
				</form>				
				<%
				x=closeRS()
				%>
			</div>
		</div>
		<%

		sSQL = "Select ID,streamID,streamTitle,status,streamURL,categoryID,createdate,date_add,date_updated,commentCount,threadCount,vote_count,has_comments "
		sSQL=sSQL& " FROM adds.Stuff_Articles where length(streamTitle)>0 "
		if request("f")<>"" then
			sSQL=sSQL& " and streamID like 'stuff/"&request("f")&"%'"
		end if
		if request("f1")<>"" then
			sSQL=sSQL& " and streamID like 'stuff/"&request("f")&"/"&request("f1")&"%'"
		end if
		if request("f2")<>"" then
			sSQL=sSQL& " and streamID like 'stuff/"&request("f")&"/"&request("f1")&"/"&request("f2")&"%'"
		end if
		if request("s")<>"" then
			sSQL=sSQL& " and streamTitle like '%"&request("s")&"%' or streamID like '%"&request("s")&"%'"
		end if
		sSQL=sSQL& "order by "
		Select case request("o")
			case "","c"
			sSQL=sSQL& "commentCount"
			case "v"
			sSQL=sSQL& "vote_count"		
			case "d"
			sSQL=sSQL& "date_add"
		end select
		if request("d")="a" then
			sSQL=sSQL& " ASC "
		else
			sSQL=sSQL& " DESC "
		end if
		sSQl=sSQL&" limit "&rows&";"
		'rwe(sSQL)
		x=openRS(sSQL)
		irow=0
		if not rsTemp.eof then
			h="<table id=""tblComments"">"	
			h=h&"<tr class=""trHead""><td>Original</td>"
			h=h&"<td>View which comments were most voted for</td>"
			if request("d")="" or request("d")="a" then
				h=h&"<td><a href=""/projects/wisdom/crowd.asp?o=c&d=d"">Comments</a></td>"
				h=h&"<td><a href=""/projects/wisdom/crowd.asp?o=v&d=d"">Votes</td>"
				h=h&"<td><a href=""/projects/wisdom/crowd.asp?o=d&d=d"">Date added</td></tr>"
			else
				h=h&"<td><a href=""/projects/wisdom/crowd.asp?o=c&d=a"">Comments</a></td>"
				h=h&"<td><a href=""/projects/wisdom/crowd.asp?o=v&d=a"">Votes</td>"
				h=h&"<td><a href=""/projects/wisdom/crowd.asp?o=d&d=a"">Date added</td></tr>"	
			end if	
			'rwe(h)
			do until rsTemp.eof
				h=h&"<tr><td><a href="""&rstemp("streamURL")&""">Stuff</a></td>"
				h=h&"<td><a href=""http://www.antidote.org.nz/projects/wisdom/crowd.asp?a="&rstemp("id")&""">"&rstemp("streamTitle")&"</a></td>"
				h=h&"<td class=""tdRight bold"">"&rstemp("commentCount")&"</td>"
				h=h&"<td  class=""tdRight bold"">"&rstemp("vote_count")&"</td>"
				h=h&"<td  class=""tdRight nowrap"">"&rstemp("date_add")&"</td></tr>"
		'x=rw(datediff("m",now(),stime)&" seconds")	
	 	' rwe(sSQL)
				if irow=-1 then 
					sSQL = "call adds.Get_results_by_ID ("&rsTemp(0)&",1)"
					x=rwb(sSQL)
					openRSA(sSQL)
					do until rsTempA.eof
						h=h&"<tr><td></td><td colspan=""4""><b>Top Comment from "&rstempA("name")&" who wrote:</b> "&rstempA("commentText")&"</td>"
						rsTempA.movenext
					loop
					closeRSA()
				end if		
				irow=irow+1
				rsTemp.movenext
			loop
			h=h&"</table>"
		end if
		
		
	
		
	else
		h="S"
		sSQL = "call adds.Get_results_by_name ('"&request("n")&"',"&rows&");"
		'rwe(sSQl)
		x=openRS(sSQL)
		irow=0
		if not rsTemp.eof then
			'get first row and make a header comment 
			h="<h1>User: <a href=""/projects/wisdom/crowd.asp?n="&request("n")&""">"&request("n")&"</a> Comments and Votes</h1>"
			h=h&"<h2>In total "&rsTemp("Total_Comments")&" comments. <span class=""green"">"&rsTemp("Total_posVotes")&"</span> -  <span class=""red"">"&rsTemp("Total_negVotes")&"</span> = "& "<span class=""black"">"&rsTemp("Total_Votes")&"</span> (Overall Rank <a title=""show rankings"" href=""/projects/wisdom/crowdranking.asp"">#"&rsTemp("Overall_Rank")&" of "&rsTemp("Total_people")&")</a> people who have commented.  So what is """&request("n")&""" saying?</h2>"
			'check to see if login is admin?
			if session("can_authorize") then
				h=h&"<form id=""myForm"" action=""/projects/wisdom/crowd.asp"" method=""post""><input type=""hidden"" name=""a"" id=""a"" value="""&article&"""><div class=""row""><div class=""col-sm-11""><textarea cols=""125"" rows=""8"" name=""ac"" id=""ac"">"&rsTemp("Antidote_comment")&"</textarea></div><div class=""col-sm-1""> <input type=""button"" id=""updateAdminComment"" value=""Update"" onclick=""updateComment()""></div></form>"
				%>
			
		    <%
			else
				if not isnull(rsTemp("Antidote_comment")) then
					h=h&"<h3><b>Antidote Commentary.</b> "&rsTemp("Antidote_comment")&"</h3>"
				end if
			end if
			h=h&"<table>"	
			
			h=h&"<tr class=""trHead"">"
			h=h&"<td>Comment</td>"
			h=h&"<td>Total</td>"
			h=h&"<td>Positive</td>"
			h=h&"<td>Negative</td>"
			h=h&"<td>Rank</td></tr>"
			do until rsTemp.eof
				h=h&"<tr><td colspan=""5""><a href=""http://www.antidote.org.nz/projects/wisdom/crowd.asp?a="&rstemp("id")&""">"&rstemp("streamTitle")&"</td></tr>"
				h=h&"<tr><td>"&rstemp("commentText")&"</td>"
				h=h&"<td class=""tdRight bold"">"&rstemp("totalVotes")&"</td>"
				h=h&"<td class=""tdRight bold green"">"&rstemp("posVotes")&"</td>"
				h=h&"<td class=""tdRight bold red"">"&rstemp("negVotes")&"</td>"
				openRSA("call adds.get_rank_person_article('"&rsTemp("name")&"','"&rsTemp("streamID")&"',"&rstemp("totalVotes")&")")
				irank=rsTempA(0)
				closeRSA()
				h=h&"<td class=""bold"">"&irank&"</a>/"&rstemp("commentCount")&"</td></tr>"
				rsTemp.movenext
			loop
			h=h&"</table>"
			
		end if
	end if
	h=h&"<br>Showing last <input style=""text-align:right;font-weight:bold;"" size=""3"" id=""topC"" value="""&rows&"""><input type=""hidden"" id=""article"" value="""&article&"""> answers. need more? <input type=""button"" id=""upBtn"" value=""Update"" onclick=""updateRows();""><br>"
	rw(h)
end if
'x=rwe(sSQL)
%>		
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
<!--#include virtual="/footer.asp" -->
<style>

</style>
<%=sFoot%>
<script>
function updateRows()
{
	var r=document.getElementById("topC").value;
	var a=document.getElementById("article").value;
	location.href="/projects/wisdom/crowd.asp?a="+a+"&r="+r	;
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